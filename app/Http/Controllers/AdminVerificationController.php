<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Provider;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para la gestión de verificación de cuentas por parte del administrador
 */
class AdminVerificationController extends Controller
{
    /**
     * Mostrar lista de cuentas pendientes de verificación
     */
    public function index()
    {
        $pendingAccounts = Account::with(['provider', 'school', 'parentProfile'])
            ->where('verification_status', 'pending')
            ->where('account_type', '!=', 'admin') // Excluir cuentas de admin
            ->latest()
            ->paginate(15);

        $verifiedAccounts = Account::with(['provider', 'school', 'parentProfile'])
            ->where('verification_status', 'verified')
            ->where('account_type', '!=', 'admin')
            ->latest()
            ->take(10)
            ->get();

        $rejectedAccounts = Account::with(['provider', 'school', 'parentProfile'])
            ->where('verification_status', 'rejected')
            ->where('account_type', '!=', 'admin')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.verification.index', compact(
            'pendingAccounts',
            'verifiedAccounts',
            'rejectedAccounts'
        ));
    }

    /**
     * Mostrar detalles de una cuenta específica para verificación
     */
    public function show(Account $account)
    {
        // Cargar relaciones según el tipo de cuenta
        $account->load(['provider', 'school', 'parentProfile']);

        // Verificar que no sea una cuenta de admin
        if ($account->account_type === 'admin') {
            abort(403, 'No se puede verificar una cuenta de administrador.');
        }

        return view('admin.verification.show', compact('account'));
    }

    /**
     * Aprobar una cuenta
     */
    public function approve(Request $request, Account $account)
    {
        $request->validate([
            'verification_notes' => 'nullable|string|max:1000'
        ]);

        // Verificar que no sea una cuenta de admin
        if ($account->account_type === 'admin') {
            abort(403, 'No se puede verificar una cuenta de administrador.');
        }

        DB::transaction(function () use ($account, $request) {
            $account->update([
                'verification_status' => 'verified',
                'verification_notes' => $request->verification_notes,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            // Si es un proveedor, también activar el proveedor
            if ($account->provider) {
                $account->provider->update(['provider_status' => 'active']);
            }

            // Si es una escuela, también activar la escuela
            if ($account->school) {
                $account->school->update(['school_status' => 'active']);
            }
        });

        return redirect()
            ->route('admin.verification.index')
            ->with('success', "Cuenta de {$account->full_name} verificada exitosamente.");
    }

    /**
     * Rechazar una cuenta
     */
    public function reject(Request $request, Account $account)
    {
        $request->validate([
            'verification_notes' => 'required|string|max:1000'
        ]);

        // Verificar que no sea una cuenta de admin
        if ($account->account_type === 'admin') {
            abort(403, 'No se puede verificar una cuenta de administrador.');
        }

        DB::transaction(function () use ($account, $request) {
            $account->update([
                'verification_status' => 'rejected',
                'verification_notes' => $request->verification_notes,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
            ]);

            // Si es un proveedor, desactivar el proveedor
            if ($account->provider) {
                $account->provider->update(['provider_status' => 'inactive']);
            }

            // Si es una escuela, desactivar la escuela
            if ($account->school) {
                $account->school->update(['school_status' => 'inactive']);
            }
        });

        return redirect()
            ->route('admin.verification.index')
            ->with('success', "Cuenta de {$account->full_name} rechazada.");
    }

    /**
     * Revertir verificación (volver a pendiente)
     */
    public function revert(Account $account)
    {
        // Verificar que no sea una cuenta de admin
        if ($account->account_type === 'admin') {
            abort(403, 'No se puede verificar una cuenta de administrador.');
        }

        DB::transaction(function () use ($account) {
            $account->update([
                'verification_status' => 'pending',
                'verification_notes' => null,
                'verified_at' => null,
                'verified_by' => null,
            ]);

            // Si es un proveedor, volver a pendiente
            if ($account->provider) {
                $account->provider->update(['provider_status' => 'pending']);
            }

            // Si es una escuela, volver a pendiente
            if ($account->school) {
                $account->school->update(['school_status' => 'pending']);
            }
        });

        return redirect()
            ->route('admin.verification.index')
            ->with('success', "Verificación de {$account->full_name} revertida.");
    }

    /**
     * Estadísticas de verificación para el dashboard
     */
    public function getVerificationStats()
    {
        $stats = [
            'pending_count' => Account::where('verification_status', 'pending')
                ->where('account_type', '!=', 'admin')
                ->count(),
            'verified_count' => Account::where('verification_status', 'verified')
                ->where('account_type', '!=', 'admin')
                ->count(),
            'rejected_count' => Account::where('verification_status', 'rejected')
                ->where('account_type', '!=', 'admin')
                ->count(),
            'pending_providers' => Account::where('verification_status', 'pending')
                ->where('account_type', 'provider')
                ->count(),
            'pending_schools' => Account::where('verification_status', 'pending')
                ->where('account_type', 'school')
                ->count(),
        ];

        return response()->json($stats);
    }
}
