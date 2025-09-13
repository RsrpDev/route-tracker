<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreAccountRequest;
use App\Http\Requests\Api\V1\UpdateAccountRequest;
use App\Http\Resources\Api\V1\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

/**
 * Controlador para gestión de cuentas de usuario
 *
 * Rutas:
 * - GET /api/v1/accounts - Listar cuentas (con filtros)
 * - GET /api/v1/accounts/{account} - Mostrar cuenta específica
 * - PUT /api/v1/accounts/{account} - Actualizar cuenta
 * - DELETE /api/v1/accounts/{account} - Eliminar cuenta
 *
 * Permisos: auth:sanctum
 */
class AccountController extends Controller
{
    /**
     * Listar todas las cuentas con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Account::query();

        // Filtros
        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%')
                  ->orWhere('id_number', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('account_status')) {
            $query->where('account_status', $request->account_status);
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        // Paginación
        $accounts = $query->paginate($request->get('per_page', 15));

        return AccountResource::collection($accounts);
    }

    /**
     * Mostrar una cuenta específica
     */
    public function show(Account $account): AccountResource
    {
        return new AccountResource($account);
    }

    /**
     * Actualizar una cuenta existente
     */
    public function update(UpdateAccountRequest $request, Account $account): JsonResponse
    {
        $validated = $request->validated();

        // Hash de la contraseña si se proporciona
        if (isset($validated['password_hash'])) {
            $validated['password_hash'] = Hash::make($validated['password_hash']);
        }

        $account->update($validated);

        return response()->json([
            'message' => 'Cuenta actualizada exitosamente',
            'account' => new AccountResource($account),
        ]);
    }

    /**
     * Eliminar una cuenta
     */
    public function destroy(Account $account): JsonResponse
    {
        // Verificar si tiene relaciones activas antes de eliminar
        $hasRelations = false;

        switch ($account->account_type) {
            case 'parent':
                $hasRelations = $account->parentProfile && $account->parentProfile->students()->exists();
                break;
            case 'provider':
                $hasRelations = $account->provider && (
                    $account->provider->drivers()->exists() ||
                    $account->provider->vehicles()->exists() ||
                    $account->provider->routes()->exists()
                );
                break;
            case 'school':
                $hasRelations = $account->school && (
                    $account->school->students()->exists() ||
                    $account->school->providers()->exists()
                );
                break;
        }

        if ($hasRelations) {
            return response()->json([
                'message' => 'No se puede eliminar la cuenta porque tiene relaciones activas',
            ], 422);
        }

        $account->delete();

        return response()->json([
            'message' => 'Cuenta eliminada exitosamente',
        ]);
    }
}
