<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StorePaymentRequest;
use App\Http\Resources\Api\V1\PaymentResource;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para gestión de pagos
 *
 * Rutas:
 * - GET /api/v1/payments - Listar pagos (por suscripción/proveedor/fecha)
 * - GET /api/v1/payments/{payment} - Mostrar pago específico
 * - POST /api/v1/payments - Crear nuevo pago
 * - POST /api/v1/payments/webhook - Webhook para callbacks de pasarela
 *
 * Permisos: auth:sanctum
 */
class PaymentController extends Controller
{
    /**
     * Listar todos los pagos con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Payment::with(['subscription.enrollment.route.provider']);

        // Filtros
        if ($request->filled('subscription_id')) {
            $query->where('subscription_id', $request->subscription_id);
        }

        if ($request->filled('provider_id')) {
            $query->whereHas('subscription.enrollment.route', function ($q) use ($request) {
                $q->where('provider_id', $request->provider_id);
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('date_from')) {
            $query->where('period_start', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('period_end', '<=', $request->date_to);
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        // Paginación
        $payments = $query->paginate($request->get('per_page', 15));

        return PaymentResource::collection($payments);
    }

    /**
     * Mostrar un pago específico
     */
    public function show(Payment $payment): PaymentResource
    {
        $payment->load(['subscription.enrollment.route.provider']);
        return new PaymentResource($payment);
    }

    /**
     * Crear un nuevo pago con cálculo automático de fees
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Obtener la suscripción para calcular fees
        $subscription = Subscription::with('enrollment.route.provider')->find($validated['subscription_id']);

        // Calcular fees automáticamente
        $platformFee = $validated['amount_total'] * ($subscription->platform_fee_rate / 100);
        $providerAmount = $validated['amount_total'] - $platformFee;

        // Crear el pago con los fees calculados
        $payment = Payment::create([
            'subscription_id' => $validated['subscription_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'amount_total' => $validated['amount_total'],
            'platform_fee' => round($platformFee, 2),
            'provider_amount' => round($providerAmount, 2),
            'payment_method' => $validated['payment_method'],
            'payment_status' => $validated['payment_status'] ?? 'pending',
        ]);

        $payment->load(['subscription.enrollment.route.provider']);

        return response()->json([
            'message' => 'Pago creado exitosamente',
            'payment' => new PaymentResource($payment),
            'fee_breakdown' => [
                'amount_total' => $validated['amount_total'],
                'platform_fee_rate' => $subscription->platform_fee_rate . '%',
                'platform_fee' => round($platformFee, 2),
                'provider_amount' => round($providerAmount, 2),
            ],
        ], 201);
    }

    /**
     * Webhook para callbacks de pasarela de pagos
     *
     * Este es un placeholder donde se integraría la lógica real
     * de la pasarela de pagos (Stripe, PayPal, etc.)
     */
    public function webhook(Request $request): JsonResponse
    {
        // Validar que la request venga de la pasarela (firma, headers, etc.)
        // Esta es la implementación básica - en producción se debe implementar
        // la validación específica de la pasarela elegida

        $payload = $request->all();

        // Ejemplo de procesamiento de webhook
        if (isset($payload['event_type'])) {
            switch ($payload['event_type']) {
                case 'payment.succeeded':
                    // Procesar pago exitoso
                    if (isset($payload['data']['id'])) {
                        $payment = Payment::where('external_payment_id', $payload['data']['id'])->first();
                        if ($payment) {
                            $payment->update([
                                'payment_status' => 'paid',
                                'paid_at' => now(),
                            ]);
                        }
                    }
                    break;

                case 'payment.failed':
                    // Procesar pago fallido
                    if (isset($payload['data']['id'])) {
                        $payment = Payment::where('external_payment_id', $payload['data']['id'])->first();
                        if ($payment) {
                            $payment->update(['payment_status' => 'failed']);
                        }
                    }
                    break;

                case 'payment.refunded':
                    // Procesar reembolso
                    if (isset($payload['data']['id'])) {
                        $payment = Payment::where('external_payment_id', $payload['data']['id'])->first();
                        if ($payment) {
                            $payment->update(['payment_status' => 'refunded']);
                        }
                    }
                    break;
            }
        }

        return response()->json([
            'message' => 'Webhook procesado exitosamente',
            'status' => 'success',
        ]);
    }

    /**
     * Procesar pago de una suscripción específica
     * Endpoint personalizado para generar pagos desde suscripciones
     */
    public function processSubscriptionPayment(Request $request, Subscription $subscription): JsonResponse
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'payment_method' => 'required|string|in:card,pse,nequi,daviplata',
        ]);

        // Calcular el monto basado en el ciclo de facturación
        $amountTotal = $this->calculatePeriodAmount($subscription, $request->period_start, $request->period_end);

        // Calcular fees
        $platformFee = $amountTotal * ($subscription->platform_fee_rate / 100);
        $providerAmount = $amountTotal - $platformFee;

        // Crear el pago
        $payment = Payment::create([
            'subscription_id' => $subscription->subscription_id,
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'amount_total' => $amountTotal,
            'platform_fee' => round($platformFee, 2),
            'provider_amount' => round($providerAmount, 2),
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
        ]);

        $payment->load(['subscription.enrollment.route.provider']);

        return response()->json([
            'message' => 'Pago de suscripción procesado exitosamente',
            'payment' => new PaymentResource($payment),
        ], 201);
    }

    /**
     * Calcular el monto del período basado en el ciclo de facturación
     */
    private function calculatePeriodAmount(Subscription $subscription, string $periodStart, string $periodEnd): float
    {
        $startDate = \Carbon\Carbon::parse($periodStart);
        $endDate = \Carbon\Carbon::parse($periodEnd);
        $days = $startDate->diffInDays($endDate) + 1;

        // Calcular monto proporcional basado en el ciclo de facturación
        switch ($subscription->billing_cycle) {
            case 'monthly':
                $monthlyRate = $subscription->price_snapshot;
                $monthlyDays = 30;
                break;
            case 'quarterly':
                $monthlyRate = $subscription->price_snapshot / 3;
                $monthlyDays = 30;
                break;
            case 'semiannual':
                $monthlyRate = $subscription->price_snapshot / 6;
                $monthlyDays = 30;
                break;
            case 'annual':
                $monthlyRate = $subscription->price_snapshot / 12;
                $monthlyDays = 30;
                break;
            default:
                $monthlyRate = $subscription->price_snapshot;
                $monthlyDays = 30;
        }

        return round(($monthlyRate / $monthlyDays) * $days, 2);
    }
}
