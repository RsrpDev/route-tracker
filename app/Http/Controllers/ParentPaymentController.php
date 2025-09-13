<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'Perfil de padre no encontrado');
        }

        // Obtener pagos del padre con filtros
        $query = Payment::whereHas('subscription.transportContract.student.parentProfile', function($q) use ($parent) {
            $q->where('parent_id', $parent->parent_id);
        })->with([
            'subscription.transportContract.student',
            'subscription.transportContract.pickupRoute',
            'subscription.transportContract.provider'
        ]);

        // Aplicar filtros
        if ($request->filled('student_filter')) {
            $query->whereHas('subscription.transportContract.student', function($q) use ($request) {
                $q->where('student_id', $request->student_filter);
            });
        }

        if ($request->filled('status_filter')) {
            $query->where('payment_status', $request->status_filter);
        }

        if ($request->filled('date_filter')) {
            $dateFilter = $request->date_filter;
            $now = now();

            switch ($dateFilter) {
                case 'this_month':
                    $query->whereMonth('paid_at', $now->month)
                          ->whereYear('paid_at', $now->year);
                    break;
                case 'last_month':
                    $lastMonth = $now->subMonth();
                    $query->whereMonth('paid_at', $lastMonth->month)
                          ->whereYear('paid_at', $lastMonth->year);
                    break;
                case 'this_year':
                    $query->whereYear('paid_at', $now->year);
                    break;
                case 'last_year':
                    $query->whereYear('paid_at', $now->year - 1);
                    break;
            }
        }

        $payments = $query->orderBy('paid_at', 'desc')->paginate(15);

        // Calcular estadísticas
        $totalPaid = Payment::whereHas('subscription.transportContract.student.parentProfile', function($q) use ($parent) {
            $q->where('parent_id', $parent->parent_id);
        })->where('payment_status', 'paid')->sum('amount_total');

        $totalPending = Payment::whereHas('subscription.transportContract.student.parentProfile', function($q) use ($parent) {
            $q->where('parent_id', $parent->parent_id);
        })->where('payment_status', 'pending')->sum('amount_total');

        $thisMonth = Payment::whereHas('subscription.transportContract.student.parentProfile', function($q) use ($parent) {
            $q->where('parent_id', $parent->parent_id);
        })->where('payment_status', 'paid')
          ->whereMonth('paid_at', now()->month)
          ->whereYear('paid_at', now()->year)
          ->sum('amount_total');

        $totalInvoices = Payment::whereHas('subscription.transportContract.student.parentProfile', function($q) use ($parent) {
            $q->where('parent_id', $parent->parent_id);
        })->count();

        // Obtener estudiantes del padre para el filtro
        $students = $parent->students;

        return view('parent.payments.index', compact(
            'payments',
            'totalPaid',
            'totalPending',
            'thisMonth',
            'totalInvoices',
            'students'
        ));
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'Perfil de padre no encontrado');
        }

        // Verificar que el pago pertenece al padre
        $payment->load([
            'subscription.transportContract.student.parentProfile',
            'subscription.transportContract.pickupRoute',
            'subscription.transportContract.dropoffRoute',
            'subscription.transportContract.provider'
        ]);

        if ($payment->subscription->transportContract->student->parentProfile->parent_id !== $parent->parent_id) {
            abort(403, 'No tienes acceso a este pago');
        }

        return view('parent.payments.show', compact('payment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'Perfil de padre no encontrado');
        }

        // Obtener suscripciones activas del padre
        $subscriptions = $parent->subscriptions()
            ->where('status', 'active')
            ->with(['transportContract.student', 'transportContract.pickupRoute'])
            ->get();

        return view('parent.payments.create', compact('subscriptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'Perfil de padre no encontrado');
        }

        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,subscription_id',
            'amount_total' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'description' => 'nullable|string|max:255'
        ]);

        // Verificar que la suscripción pertenece al padre
        $subscription = $parent->subscriptions()
            ->where('subscription_id', $request->subscription_id)
            ->first();

        if (!$subscription) {
            return back()->withErrors(['subscription_id' => 'La suscripción no pertenece a este padre']);
        }

        $payment = Payment::create([
            'subscription_id' => $request->subscription_id,
            'amount_total' => $request->amount_total,
            'payment_method' => $request->payment_method,
            'description' => $request->description ?? 'Pago manual de transporte escolar',
            'payment_status' => 'pending',
            'paid_at' => now()
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Pago creado exitosamente');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'Perfil de padre no encontrado');
        }

        // Verificar que el pago pertenece al padre
        $payment->load([
            'subscription.transportContract.student.parentProfile',
            'subscription.transportContract.pickupRoute',
            'subscription.transportContract.provider'
        ]);

        if ($payment->subscription->transportContract->student->parentProfile->parent_id !== $parent->parent_id) {
            abort(403, 'No tienes acceso a este pago');
        }

        if ($payment->payment_status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Solo se pueden editar pagos pendientes');
        }

        return view('parent.payments.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'Perfil de padre no encontrado');
        }

        // Verificar que el pago pertenece al padre
        if ($payment->subscription->transportContract->student->parentProfile->parent_id !== $parent->parent_id) {
            abort(403, 'No tienes acceso a este pago');
        }

        if ($payment->payment_status !== 'pending') {
            return redirect()->route('payments.show', $payment)
                ->with('error', 'Solo se pueden editar pagos pendientes');
        }

        $request->validate([
            'payment_method' => 'required|string',
            'description' => 'nullable|string|max:255'
        ]);

        $payment->update([
            'payment_method' => $request->payment_method,
            'payment_status' => 'paid',
            'paid_at' => now()
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Pago actualizado exitosamente');
    }
}
