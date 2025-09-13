<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para verificar que el usuario tenga uno de los roles especificados
 *
 * Uso: ->middleware('role:admin,provider') o ->middleware('role:parent')
 */
class EnsureHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verificar que el usuario esté autenticado
        if (!$request->user()) {
            return response()->json([
                'message' => 'No autenticado',
            ], 401);
        }

        // Si no se especificaron roles, permitir acceso
        if (empty($roles)) {
            return $next($request);
        }

        // Verificar que el usuario tenga uno de los roles especificados
        $userRole = $request->user()->account_type;

        if (!in_array($userRole, $roles)) {
            return response()->json([
                'message' => 'Acceso denegado. No tienes permisos para esta acción.',
                'required_roles' => $roles,
                'user_role' => $userRole,
            ], 403);
        }

        return $next($request);
    }
}
