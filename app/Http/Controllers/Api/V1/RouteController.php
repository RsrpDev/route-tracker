<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AssignRouteRequest;
use App\Http\Requests\Api\V1\StoreRouteRequest;
use App\Http\Requests\Api\V1\UpdateRouteRequest;
use App\Http\Resources\Api\V1\RouteResource;
use App\Models\Route;
use App\Models\RouteAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

/**
 * Controlador para gestión de rutas
 *
 * Rutas:
 * - GET /api/v1/routes - Listar rutas (con filtros)
 * - GET /api/v1/routes/{route} - Mostrar ruta específica
 * - POST /api/v1/routes - Crear nueva ruta
 * - PUT /api/v1/routes/{route} - Actualizar ruta
 * - DELETE /api/v1/routes/{route} - Eliminar ruta
 * - POST /api/v1/routes/{route}/assign - Asignar conductor y vehículo a ruta
 *
 * Permisos: auth:sanctum
 */
class RouteController extends Controller
{
    /**
     * Listar todas las rutas con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Route::with(['provider']);

        // Filtros
        if ($request->filled('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        if ($request->filled('active_flag')) {
            $query->where('active_flag', $request->active_flag);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('route_name', 'like', '%' . $request->q . '%')
                  ->orWhere('origin_address', 'like', '%' . $request->q . '%')
                  ->orWhere('destination_address', 'like', '%' . $request->q . '%');
            });
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        // Paginación
        $routes = $query->paginate($request->get('per_page', 15));

        return RouteResource::collection($routes);
    }

    /**
     * Mostrar una ruta específica con contadores
     */
    public function show(Route $route): RouteResource
    {
        // Cargar relaciones y agregar contadores
        $route->load(['provider']);
        $route->assignments_count = $route->assignments()->count();
        $route->enrollments_count = $route->enrollments()->count();

        return new RouteResource($route);
    }

    /**
     * Crear una nueva ruta
     */
    public function store(StoreRouteRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $route = Route::create($validated);
        $route->load(['provider']);

        return response()->json([
            'message' => 'Ruta creada exitosamente',
            'route' => new RouteResource($route),
        ], 201);
    }

    /**
     * Actualizar una ruta existente
     */
    public function update(UpdateRouteRequest $request, Route $route): JsonResponse
    {
        $validated = $request->validated();

        $route->update($validated);
        $route->load(['provider']);

        return response()->json([
            'message' => 'Ruta actualizada exitosamente',
            'route' => new RouteResource($route),
        ]);
    }

    /**
     * Eliminar una ruta
     */
    public function destroy(Route $route): JsonResponse
    {
        // Verificar si tiene asignaciones o inscripciones antes de eliminar
        if ($route->assignments()->exists() || $route->enrollments()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar la ruta porque tiene asignaciones o inscripciones activas',
            ], 422);
        }

        $route->delete();

        return response()->json([
            'message' => 'Ruta eliminada exitosamente',
        ]);
    }

    /**
     * Asignar conductor y vehículo a una ruta
     */
    public function assign(AssignRouteRequest $request, Route $route): JsonResponse
    {
        $validated = $request->validated();

        // Verificar que el conductor y vehículo pertenezcan al mismo proveedor
        $driver = \App\Models\Driver::find($validated['driver_id']);
        $vehicle = \App\Models\Vehicle::find($validated['vehicle_id']);

        if ($driver->provider_id !== $route->provider_id || $vehicle->provider_id !== $route->provider_id) {
            return response()->json([
                'message' => 'El conductor y vehículo deben pertenecer al mismo proveedor de la ruta',
            ], 422);
        }

        // Verificar que el conductor esté aprobado
        if ($driver->driver_status !== 'approved') {
            return response()->json([
                'message' => 'El conductor debe estar aprobado para ser asignado a una ruta',
            ], 422);
        }

        // Verificar que el vehículo esté activo
        if ($vehicle->vehicle_status !== 'active') {
            return response()->json([
                'message' => 'El vehículo debe estar activo para ser asignado a una ruta',
            ], 422);
        }

        // Crear la asignación
        $assignment = RouteAssignment::create([
            'route_id' => $route->route_id,
            'driver_id' => $validated['driver_id'],
            'vehicle_id' => $validated['vehicle_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'assignment_status' => 'active',
        ]);

        $assignment->load(['route', 'driver', 'vehicle']);

        return response()->json([
            'message' => 'Ruta asignada exitosamente',
            'assignment' => new \App\Http\Resources\Api\V1\RouteAssignmentResource($assignment),
        ], 201);
    }
}
