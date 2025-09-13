<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreProviderRequest;
use App\Http\Requests\Api\V1\UpdateProviderRequest;
use App\Http\Resources\Api\V1\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controlador para gestión de proveedores
 *
 * Rutas:
 * - GET /api/v1/providers - Listar proveedores (con filtros)
 * - GET /api/v1/providers/{provider} - Mostrar proveedor específico
 * - POST /api/v1/providers - Crear nuevo proveedor
 * - PUT /api/v1/providers/{provider} - Actualizar proveedor
 * - DELETE /api/v1/providers/{provider} - Eliminar proveedor
 *
 * Permisos: auth:sanctum
 */
class ProviderController extends Controller
{
    /**
     * Listar todos los proveedores con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Provider::with(['account', 'linkedSchool']);

        // Filtros
        if ($request->filled('provider_type')) {
            $query->where('provider_type', $request->provider_type);
        }

        if ($request->filled('provider_status')) {
            $query->where('provider_status', $request->provider_status);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('display_name', 'like', '%' . $request->q . '%')
                  ->orWhere('contact_email', 'like', '%' . $request->q . '%')
                  ->orWhereHas('account', function ($accountQuery) use ($request) {
                      $accountQuery->where('full_name', 'like', '%' . $request->q . '%');
                  });
            });
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        // Paginación
        $providers = $query->paginate($request->get('per_page', 15));

        return ProviderResource::collection($providers);
    }

    /**
     * Mostrar un proveedor específico con relaciones
     */
    public function show(Provider $provider): ProviderResource
    {
        $provider->load(['account', 'linkedSchool', 'drivers', 'vehicles', 'routes']);
        return new ProviderResource($provider);
    }

    /**
     * Crear un nuevo proveedor
     */
    public function store(StoreProviderRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $provider = Provider::create($validated);
        $provider->load(['account', 'linkedSchool']);

        return response()->json([
            'message' => 'Proveedor creado exitosamente',
            'provider' => new ProviderResource($provider),
        ], 201);
    }

    /**
     * Actualizar un proveedor existente
     */
    public function update(UpdateProviderRequest $request, Provider $provider): JsonResponse
    {
        $validated = $request->validated();

        $provider->update($validated);
        $provider->load(['account', 'linkedSchool']);

        return response()->json([
            'message' => 'Proveedor actualizado exitosamente',
            'provider' => new ProviderResource($provider),
        ]);
    }

    /**
     * Eliminar un proveedor
     */
    public function destroy(Provider $provider): JsonResponse
    {
        // Verificar si tiene conductores, vehículos o rutas antes de eliminar
        if ($provider->drivers()->exists() || $provider->vehicles()->exists() || $provider->routes()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar el proveedor porque tiene conductores, vehículos o rutas asociados',
            ], 422);
        }

        $provider->delete();

        return response()->json([
            'message' => 'Proveedor eliminado exitosamente',
        ]);
    }
}
