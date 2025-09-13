<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreDriverRequest;
use App\Http\Requests\Api\V1\UpdateDriverRequest;
use App\Http\Resources\Api\V1\DriverResource;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controlador para gestión de conductores
 *
 * Rutas:
 * - GET /api/v1/drivers - Listar conductores (con filtros)
 * - GET /api/v1/drivers/{driver} - Mostrar conductor específico
 * - POST /api/v1/drivers - Crear nuevo conductor
 * - PUT /api/v1/drivers/{driver} - Actualizar conductor
 * - DELETE /api/v1/drivers/{driver} - Eliminar conductor
 * - PATCH /api/v1/drivers/{driver}/approve - Aprobar conductor
 *
 * Permisos: auth:sanctum
 */
class DriverController extends Controller
{
    /**
     * Listar todos los conductores con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Driver::with(['provider']);

        // Filtros
        if ($request->filled('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        if ($request->filled('driver_status')) {
            $query->where('driver_status', $request->driver_status);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('given_name', 'like', '%' . $request->q . '%')
                  ->orWhere('family_name', 'like', '%' . $request->q . '%')
                  ->orWhere('id_number', 'like', '%' . $request->q . '%')
                  ->orWhere('license_number', 'like', '%' . $request->q . '%');
            });
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        // Paginación
        $drivers = $query->paginate($request->get('per_page', 15));

        return DriverResource::collection($drivers);
    }

    /**
     * Mostrar un conductor específico
     */
    public function show(Driver $driver): DriverResource
    {
        $driver->load(['provider']);
        return new DriverResource($driver);
    }

    /**
     * Crear un nuevo conductor
     */
    public function store(StoreDriverRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $driver = Driver::create($validated);
        $driver->load(['provider']);

        return response()->json([
            'message' => 'Conductor creado exitosamente',
            'driver' => new DriverResource($driver),
        ], 201);
    }

    /**
     * Actualizar un conductor existente
     */
    public function update(UpdateDriverRequest $request, Driver $driver): JsonResponse
    {
        $validated = $request->validated();

        $driver->update($validated);
        $driver->load(['provider']);

        return response()->json([
            'message' => 'Conductor actualizado exitosamente',
            'driver' => new DriverResource($driver),
        ]);
    }

    /**
     * Eliminar un conductor
     */
    public function destroy(Driver $driver): JsonResponse
    {
        // Verificar si tiene asignaciones de ruta antes de eliminar
        if ($driver->routeAssignments()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar el conductor porque tiene asignaciones de ruta activas',
            ], 422);
        }

        $driver->delete();

        return response()->json([
            'message' => 'Conductor eliminado exitosamente',
        ]);
    }

    /**
     * Aprobar un conductor (cambiar estado a approved)
     */
    public function approve(Driver $driver): JsonResponse
    {
        if ($driver->driver_status === 'approved') {
            return response()->json([
                'message' => 'El conductor ya está aprobado',
            ], 422);
        }

        $driver->update(['driver_status' => 'approved']);
        $driver->load(['provider']);

        return response()->json([
            'message' => 'Conductor aprobado exitosamente',
            'driver' => new DriverResource($driver),
        ]);
    }
}
