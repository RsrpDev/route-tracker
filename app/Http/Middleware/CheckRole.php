<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Verificar si el usuario tiene el rol especificado
        if ($user->account_type !== $role) {
            // Si no tiene el rol, redirigir al dashboard correspondiente
            return $this->redirectToAppropriateDashboard($user);
        }

        return $next($request);
    }

    /**
     * Redirigir al usuario al dashboard apropiado según su rol
     */
    private function redirectToAppropriateDashboard($user)
    {
        switch ($user->account_type) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'provider':
                return redirect()->route('provider.dashboard.by.type');
            case 'parent':
                return redirect()->route('parent.dashboard');
            case 'school':
                return redirect()->route('school.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }
}
