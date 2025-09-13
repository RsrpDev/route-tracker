<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\RouteAssignmentResource;
use App\Models\RouteAssignment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controlador para gestión de asignaciones de ruta
 *
 * Rutas:
 * - GET /api/v1/route-assignments - Listar asignaciones (historial)
 * - GET /api/v1/route-assignments/{assignment} - Mostrar asignación específica
 * - POST /api/v1/route-assignments - Crear nueva asignación
 * - PUT /api/v1/route-assignments/{assignment} - Actualizar asignación
 * - DELETE /api/v1/route-assignments/{assignment} - Eliminar asignación
 *
 * Permisos: auth:sanctum
 */
class RouteAssignmentController extends Controller
{
    /**
     * Listar todas las asignaciones de ruta (historial) con paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = RouteAssignment::with(['route', 'driver', 'vehicle']);

        // Filtros
        if ($request->filled('route_id')) {
            $query->where('route_id', $request->route_id);
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        if ($request->filled('assignment_status')) {
            $query->where('assignment_status', $request->assignment_status);
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('start_date', '<=', $request->date_to);
        }

        // Ordenamiento
        $query->orderBy('start_date', 'desc');

        // Paginación
        $assignments = $query->paginate($request->get('per_page', 15));

        return RouteAssignmentResource::collection($assignments);
    }

    /**
     * Mostrar una asignación específica
     */
    public function show(RouteAssignment $assignment): RouteAssignmentResource
    {
        $assignment->load(['route', 'driver', 'vehicle']);
        return new RouteAssignmentResource($assignment);
    }

    /**
     * Crear una nueva asignación de ruta
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'route_id' => 'required|integer|exists:routes,route_id',
            'driver_id' => 'required|integer|exists:drivers,driver_id',
            'vehicle_id' => 'required|integer|exists:vehicles,vehicle_id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after:start_date',
            'assignment_status' => 'nullable|string|in:active,inactive',
        ]);

        // Verificar que el conductor y vehículo pertenezcan al mismo proveedor de la ruta
        $route = \App\Models\Route::find($validated['route_id']);
        $driver = \App\Models\Driver::find($validated['driver_id']);
        $vehicle = \App\Models\Vehicle::find($validated['vehicle_id']);

        if ($driver->provider_id !== $route->provider_id || $vehicle->provider_id !== $route->provider_id) {
            return response()->json([
                'message' => 'El conductor y vehículo deben pertenecer al mismo proveedor de la ruta',
            ], 422);
        }

        $assignment = RouteAssignment::create($validated);
        $assignment->load(['route', 'driver', 'vehicle']);

        return response()->json([
            'message' => 'Asignación de ruta creada exitosamente',
            'assignment' => new RouteAssignmentResource($assignment),
        ], 201);
    }

    /**
     * Actualizar una asignación existente
     */
    public function update(Request $request, RouteAssignment $assignment): JsonResponse
    {
        $validated = $request->validate([
            'end_date' => 'nullable|date|after:start_date',
            'assignment_status' => 'sometimes|string|in:active,inactive',
        ]);

        $assignment->update($validated);
        $assignment->load(['route', 'driver', 'vehicle']);

        return response()->json([
            'message' => 'Asignación de ruta actualizada exitosamente',
            'assignment' => new RouteAssignmentResource($assignment),
        ]);
    }

    /**
     * Eliminar una asignación
     */
    public function destroy(RouteAssignment $assignment): JsonResponse
    {
        $assignment->delete();

        return response()->json([
            'message' => 'Asignación de ruta eliminada exitosamente',
        ]);
    }
}
