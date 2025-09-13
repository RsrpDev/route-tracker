<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DriverProfileController extends Controller
{
    /**
     * Mostrar el perfil del conductor independiente
     */
    public function show()
    {
        $provider = Auth::user()->provider;

        if (!$provider || !$provider->isIndependentDriver()) {
            abort(403, 'Acceso denegado. Solo conductores independientes pueden acceder a esta sección.');
        }

        return view('provider.driver.profile', compact('provider'));
    }

    /**
     * Mostrar el formulario de edición del perfil
     */
    public function edit()
    {
        $provider = Auth::user()->provider;

        if (!$provider || !$provider->isIndependentDriver()) {
            abort(403, 'Acceso denegado. Solo conductores independientes pueden acceder a esta sección.');
        }

        return view('provider.driver.edit-profile', compact('provider'));
    }

    /**
     * Actualizar el perfil del conductor independiente
     */
    public function update(Request $request)
    {
        $provider = Auth::user()->provider;

        if (!$provider || !$provider->isIndependentDriver()) {
            abort(403, 'Acceso denegado. Solo conductores independientes pueden acceder a esta sección.');
        }

        $validated = $request->validate([
            'display_name' => 'required|string|max:150',
            'contact_email' => 'required|email|max:191',
            'contact_phone' => 'required|string|max:30',
            'driver_license_number' => 'required|string|max:50',
            'driver_license_category' => 'required|string|max:10',
            'driver_license_expiration' => 'required|date|after:today',
            'driver_years_experience' => 'required|integer|min:0|max:50',
            'default_commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        $provider->update($validated);

        return redirect()->route('provider.driver.profile')
            ->with('success', 'Perfil actualizado exitosamente');
    }

    /**
     * Mostrar el estado de la licencia
     */
    public function licenseStatus()
    {
        $provider = Auth::user()->provider;

        if (!$provider || !$provider->isIndependentDriver()) {
            abort(403, 'Acceso denegado. Solo conductores independientes pueden acceder a esta sección.');
        }

        return view('provider.driver.license-status', compact('provider'));
    }

    /**
     * Mostrar estadísticas del conductor independiente
     */
    public function statistics()
    {
        $provider = Auth::user()->provider;

        if (!$provider || !$provider->isIndependentDriver()) {
            abort(403, 'Acceso denegado. Solo conductores independientes pueden acceder a esta sección.');
        }

        // Obtener estadísticas específicas del conductor independiente
        $stats = [
            'total_routes' => $provider->routes()->count(),
            'active_routes' => $provider->routes()->where('active_flag', true)->count(),
            'total_students' => $provider->studentContracts()->where('contract_status', 'active')->count(),
            'monthly_income' => \App\Models\Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
                $query->where('provider_id', $provider->provider_id);
            })
            ->where('paid_at', '>=', now()->startOfMonth())
            ->sum('amount_total'),
            'total_income' => \App\Models\Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
                $query->where('provider_id', $provider->provider_id);
            })->sum('amount_total'),
            'license_status' => $provider->getLicenseStatusText(),
            'license_expiration' => $provider->driver_license_expiration,
            'years_experience' => $provider->driver_years_experience,
            'average_monthly_income' => \App\Models\Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
                $query->where('provider_id', $provider->provider_id);
            })
            ->where('paid_at', '>=', now()->subMonths(6))
            ->avg('amount_total'),
        ];

        return view('provider.driver.statistics', compact('provider', 'stats'));
    }
}
