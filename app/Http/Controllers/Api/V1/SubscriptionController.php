<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreSubscriptionRequest;
use App\Http\Requests\Api\V1\UpdateSubscriptionRequest;
use App\Http\Resources\Api\V1\SubscriptionResource;
use App\Models\Subscription;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para gestión de suscripciones
 *
 * Rutas:
 * - GET /api/v1/subscriptions - Listar suscripciones (por inscripción)
 * - GET /api/v1/subscriptions/{subscription} - Mostrar suscripción específica
 * - POST /api/v1/subscriptions - Crear nueva suscripción
 * - PUT /api/v1/subscriptions/{subscription} - Actualizar suscripción
 * - DELETE /api/v1/subscriptions/{subscription} - Eliminar suscripción
 *
 * Permisos: auth:sanctum
 */
class SubscriptionController extends Controller
{
    /**
     * Listar todas las suscripciones con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Subscription::with(['transportContract']);

        // Filtros
        if ($request->filled('contract_id')) {
            $query->where('contract_id', $request->contract_id);
        }

        if ($request->filled('subscription_status')) {
            $query->where('subscription_status', $request->subscription_status);
        }

        if ($request->filled('billing_cycle')) {
            $query->where('billing_cycle', $request->billing_cycle);
        }

        if ($request->filled('date_from')) {
            $query->where('next_billing_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('next_billing_date', '<=', $request->date_to);
        }

        // Ordenamiento
        $query->orderBy('next_billing_date', 'desc');

        // Paginación
        $subscriptions = $query->paginate($request->get('per_page', 15));

        return SubscriptionResource::collection($subscriptions);
    }

    /**
     * Mostrar una suscripción específica
     */
    public function show(Subscription $subscription): SubscriptionResource
    {
        $subscription->load(['enrollment', 'payments']);
        return new SubscriptionResource($subscription);
    }

    /**
     * Crear una nueva suscripción para una inscripción
     */
    public function store(StoreSubscriptionRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Verificar que el contrato no tenga ya una suscripción activa
        $existingSubscription = Subscription::where('contract_id', $validated['contract_id'])
            ->whereIn('subscription_status', ['active', 'paused'])
            ->first();

        if ($existingSubscription) {
            return response()->json([
                'message' => 'La inscripción ya tiene una suscripción activa',
            ], 422);
        }

        // Obtener el contrato para el precio snapshot
        $contract = StudentTransportContract::with(['pickupRoute', 'dropoffRoute'])->find($validated['contract_id']);

        // Si no se proporciona price_snapshot, usar el precio actual del contrato
        if (!isset($validated['price_snapshot'])) {
            $validated['price_snapshot'] = $contract->monthly_fee;
        }

        // Si no se proporciona platform_fee_rate, usar el default del proveedor
        if (!isset($validated['platform_fee_rate'])) {
            $validated['platform_fee_rate'] = $contract->provider->default_commission_rate ?? 5.00;
        }

        $subscription = Subscription::create($validated);
        $subscription->load(['transportContract']);

        return response()->json([
            'message' => 'Suscripción creada exitosamente',
            'subscription' => new SubscriptionResource($subscription),
        ], 201);
    }

    /**
     * Actualizar una suscripción existente
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription): JsonResponse
    {
        $validated = $request->validated();

        $subscription->update($validated);
        $subscription->load(['enrollment', 'payments']);

        return response()->json([
            'message' => 'Suscripción actualizada exitosamente',
            'subscription' => new SubscriptionResource($subscription),
        ]);
    }

    /**
     * Eliminar una suscripción
     */
    public function destroy(Subscription $subscription): JsonResponse
    {
        // Verificar si tiene pagos antes de eliminar
        if ($subscription->payments()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar la suscripción porque tiene pagos asociados',
            ], 422);
        }

        $subscription->delete();

        return response()->json([
            'message' => 'Suscripción eliminada exitosamente',
        ]);
    }
}
