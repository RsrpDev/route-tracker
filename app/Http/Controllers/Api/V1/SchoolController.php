<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreSchoolRequest;
use App\Http\Requests\Api\V1\UpdateSchoolRequest;
use App\Http\Resources\Api\V1\SchoolResource;
use App\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controlador para gestión de escuelas
 *
 * Rutas:
 * - GET /api/v1/schools - Listar escuelas
 * - GET /api/v1/schools/{school} - Mostrar escuela específica
 * - POST /api/v1/schools - Crear nueva escuela
 * - PUT /api/v1/schools/{school} - Actualizar escuela
 * - DELETE /api/v1/schools/{school} - Eliminar escuela
 *
 * Permisos: auth:sanctum
 */
class SchoolController extends Controller
{
    /**
     * Listar todas las escuelas con paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = School::with(['account']);

        // Filtros
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('legal_name', 'like', '%' . $request->q . '%')
                  ->orWhere('nit', 'like', '%' . $request->q . '%')
                  ->orWhereHas('account', function ($accountQuery) use ($request) {
                      $accountQuery->where('full_name', 'like', '%' . $request->q . '%');
                  });
            });
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        // Paginación
        $schools = $query->paginate($request->get('per_page', 15));

        return SchoolResource::collection($schools);
    }

    /**
     * Mostrar una escuela específica
     */
    public function show(School $school): SchoolResource
    {
        $school->load(['account']);
        return new SchoolResource($school);
    }

    /**
     * Crear una nueva escuela
     */
    public function store(StoreSchoolRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $school = School::create($validated);
        $school->load(['account']);

        return response()->json([
            'message' => 'Escuela creada exitosamente',
            'school' => new SchoolResource($school),
        ], 201);
    }

    /**
     * Actualizar una escuela existente
     */
    public function update(UpdateSchoolRequest $request, School $school): JsonResponse
    {
        $validated = $request->validated();

        $school->update($validated);
        $school->load(['account']);

        return response()->json([
            'message' => 'Escuela actualizada exitosamente',
            'school' => new SchoolResource($school),
        ]);
    }

    /**
     * Eliminar una escuela
     */
    public function destroy(School $school): JsonResponse
    {
        // Verificar si tiene estudiantes o proveedores antes de eliminar
        if ($school->students()->exists() || $school->providers()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar la escuela porque tiene estudiantes o proveedores asociados',
            ], 422);
        }

        $school->delete();

        return response()->json([
            'message' => 'Escuela eliminada exitosamente',
        ]);
    }
}
