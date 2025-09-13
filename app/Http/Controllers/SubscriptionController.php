<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subscriptions = Subscription::paginate(15);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subscriptions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:student_transport_contracts,contract_id',
            'billing_cycle' => 'required|in:monthly,quarterly,semiannual,annual',
            'price_snapshot' => 'required|numeric|min:0',
            'platform_fee_rate' => 'required|numeric|min:0|max:100',
            'next_billing_date' => 'required|date|after:today',
            'subscription_status' => 'required|in:active,paused,cancelled,expired'
        ]);

        Subscription::create($validated);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Suscripción creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'contract_id' => 'required|exists:student_transport_contracts,contract_id',
            'billing_cycle' => 'required|in:monthly,quarterly,semiannual,annual',
            'price_snapshot' => 'required|numeric|min:0',
            'platform_fee_rate' => 'required|numeric|min:0|max:100',
            'next_billing_date' => 'required|date',
            'subscription_status' => 'required|in:active,paused,cancelled,expired'
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Suscripción actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', 'Suscripción eliminada exitosamente');
    }

    /**
     * Activate subscription
     */
    public function activate(Subscription $subscription)
    {
        try {
            $subscription->update([
                'subscription_status' => 'active',
                'updated_at' => now()
            ]);

            \Log::info('Suscripción activada', [
                'subscription_id' => $subscription->subscription_id,
                'user_id' => auth()->id(),
                'previous_status' => 'paused'
            ]);

            return redirect()->route('subscriptions.show', $subscription)->with('success', 'Suscripción activada exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al activar suscripción: ' . $e->getMessage(), [
                'subscription_id' => $subscription->subscription_id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Error al activar la suscripción');
        }
    }

    /**
     * Suspend subscription
     */
    public function suspend(Subscription $subscription)
    {
        try {
            $subscription->update([
                'subscription_status' => 'paused',
                'updated_at' => now()
            ]);

            \Log::info('Suscripción suspendida', [
                'subscription_id' => $subscription->subscription_id,
                'user_id' => auth()->id(),
                'previous_status' => 'active'
            ]);

            return redirect()->route('subscriptions.show', $subscription)->with('success', 'Suscripción suspendida exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al suspender suscripción: ' . $e->getMessage(), [
                'subscription_id' => $subscription->subscription_id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Error al suspender la suscripción');
        }
    }

    /**
     * Renew subscription
     */
    public function renew(Subscription $subscription)
    {
        try {
            // Calcular nueva fecha de facturación basada en el ciclo
            $nextBillingDate = $this->calculateNextBillingDate($subscription);

            $subscription->update([
                'subscription_status' => 'active',
                'next_billing_date' => $nextBillingDate,
                'updated_at' => now()
            ]);

            \Log::info('Suscripción renovada', [
                'subscription_id' => $subscription->subscription_id,
                'user_id' => auth()->id(),
                'next_billing_date' => $nextBillingDate->format('Y-m-d')
            ]);

            return redirect()->route('subscriptions.show', $subscription)->with('success', 'Suscripción renovada exitosamente. Nueva fecha de facturación: ' . $nextBillingDate->format('d/m/Y'));
        } catch (\Exception $e) {
            \Log::error('Error al renovar suscripción: ' . $e->getMessage(), [
                'subscription_id' => $subscription->subscription_id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Error al renovar la suscripción');
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel(Subscription $subscription)
    {
        try {
            $subscription->update([
                'subscription_status' => 'cancelled',
                'auto_renewal' => false,
                'updated_at' => now()
            ]);

            \Log::info('Suscripción cancelada', [
                'subscription_id' => $subscription->subscription_id,
                'user_id' => auth()->id(),
                'previous_status' => $subscription->getOriginal('subscription_status')
            ]);

            return redirect()->route('subscriptions.show', $subscription)->with('success', 'Suscripción cancelada exitosamente');
        } catch (\Exception $e) {
            \Log::error('Error al cancelar suscripción: ' . $e->getMessage(), [
                'subscription_id' => $subscription->subscription_id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('error', 'Error al cancelar la suscripción');
        }
    }

    /**
     * Calculate next billing date based on subscription cycle
     */
    private function calculateNextBillingDate(Subscription $subscription)
    {
        $currentDate = now();

        switch ($subscription->billing_cycle) {
            case 'monthly':
                return $currentDate->addMonth();
            case 'quarterly':
                return $currentDate->addMonths(3);
            case 'semiannual':
                return $currentDate->addMonths(6);
            case 'annual':
                return $currentDate->addYear();
            default:
                return $currentDate->addMonth();
        }
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request, Subscription $subscription)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('subscription-documents', 'public');

        // Aquí podrías guardar la información del documento en la base de datos

        return redirect()->route('admin.subscriptions.show', $subscription)->with('success', 'Documento subido exitosamente');
    }

    /**
     * Bulk update subscriptions
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'subscription_ids' => 'required|array',
            'subscription_ids.*' => 'exists:subscriptions,subscription_id',
            'subscription_status' => 'required|in:active,paused,cancelled,expired'
        ]);

        Subscription::whereIn('subscription_id', $request->subscription_ids)
            ->update(['subscription_status' => $request->subscription_status]);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Suscripciones actualizadas exitosamente');
    }

    /**
     * Export subscriptions
     */
    public function export()
    {
        $subscriptions = Subscription::with(['enrollment.student', 'enrollment.route'])->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="subscriptions.csv"',
        ];

        $callback = function() use ($subscriptions) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID', 'Estudiante', 'Ruta', 'Ciclo Facturación', 'Precio', 'Tarifa Plataforma',
                'Próxima Facturación', 'Estado', 'Fecha Creación'
            ]);

            // Data
            foreach ($subscriptions as $subscription) {
                fputcsv($file, [
                    $subscription->subscription_id,
                    $subscription->enrollment->student->first_name . ' ' . $subscription->enrollment->student->last_name,
                    $subscription->enrollment->route->route_name,
                    $subscription->billing_cycle,
                    '$' . number_format($subscription->price_snapshot, 2),
                    $subscription->platform_fee_rate . '%',
                    $subscription->next_billing_date->format('Y-m-d'),
                    $subscription->subscription_status,
                    $subscription->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

