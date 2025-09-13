<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreVehicleRequest;
use App\Http\Requests\Api\V1\UpdateVehicleRequest;
use App\Http\Resources\Api\V1\VehicleResource;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controlador para gestión de vehículos
 *
 * Rutas:
 * - GET /api/v1/vehicles - Listar vehículos
 * - GET /api/v1/vehicles/{vehicle} - Mostrar vehículo específico
 * - POST /api/v1/vehicles - Crear nuevo vehículo
 * - PUT /api/v1/vehicles/{vehicle} - Actualizar vehículo
 * - DELETE /api/v1/vehicles/{vehicle} - Eliminar vehículo
 *
 * Permisos: auth:sanctum
 */
class VehicleController extends Controller
{
    /**
     * Listar todos los vehículos con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Vehicle::with(['provider']);

        // Filtros
        if ($request->filled('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        if ($request->filled('vehicle_status')) {
            $query->where('vehicle_status', $request->vehicle_status);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('plate', 'like', '%' . $request->q . '%')
                  ->orWhere('brand', 'like', '%' . $request->q . '%');
            });
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        // Paginación
        $vehicles = $query->paginate($request->get('per_page', 15));

        return VehicleResource::collection($vehicles);
    }

    /**
     * Mostrar un vehículo específico
     */
    public function show(Vehicle $vehicle): VehicleResource
    {
        $vehicle->load(['provider']);
        return new VehicleResource($vehicle);
    }

    /**
     * Crear un nuevo vehículo
     */
    public function store(StoreVehicleRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $vehicle = Vehicle::create($validated);
        $vehicle->load(['provider']);

        return response()->json([
            'message' => 'Vehículo creado exitosamente',
            'vehicle' => new VehicleResource($vehicle),
        ], 201);
    }

    /**
     * Actualizar un vehículo existente
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): JsonResponse
    {
        $validated = $request->validated();

        $vehicle->update($validated);
        $vehicle->load(['provider']);

        return response()->json([
            'message' => 'Vehículo actualizado exitosamente',
            'vehicle' => new VehicleResource($vehicle),
        ]);
    }

    /**
     * Eliminar un vehículo
     */
    public function destroy(Vehicle $vehicle): JsonResponse
    {
        // Verificar si tiene asignaciones de ruta antes de eliminar
        if ($vehicle->routeAssignments()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar el vehículo porque tiene asignaciones de ruta activas',
            ], 422);
        }

        $vehicle->delete();

        return response()->json([
            'message' => 'Vehículo eliminado exitosamente',
        ]);
    }
}
