<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Provider;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolHasTransportService
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Solo aplicar a usuarios autenticados
        if (!$user) {
            return redirect()->route('login');
        }

        // Verificar si el usuario tiene un proveedor de transporte asociado
        $hasTransportService = false;

        // Buscar proveedor directo
        $provider = $user->provider;
        if ($provider && $provider->provider_type === 'school_provider') {
            $hasTransportService = true;
        }

        // Si no encuentra proveedor directo, buscar por linked_school_id si es escuela
        if (!$hasTransportService && $user->account_type === 'school') {
            $school = $user->school;
            if ($school) {
                $provider = Provider::where('linked_school_id', $school->school_id)
                    ->where('provider_type', 'school_provider')
                    ->where('provider_status', 'active')
                    ->first();
                if ($provider) {
                    $hasTransportService = true;
                }
            }
        }

        // Si no tiene servicio de transporte, denegar acceso
        if (!$hasTransportService) {
            abort(403, 'Acceso denegado. Tu escuela debe estar registrada como proveedor de transporte para acceder a esta funcionalidad.');
        }

        return $next($request);
    }
}
