<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreParentRequest;
use App\Http\Requests\Api\V1\UpdateParentRequest;
use App\Http\Resources\Api\V1\ParentResource;
use App\Models\ParentProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controlador para gestión de perfiles de padres
 *
 * Rutas:
 * - GET /api/v1/parents - Listar perfiles de padres
 * - GET /api/v1/parents/{parent} - Mostrar perfil específico
 * - POST /api/v1/parents - Crear nuevo perfil
 * - PUT /api/v1/parents/{parent} - Actualizar perfil
 * - DELETE /api/v1/parents/{parent} - Eliminar perfil
 *
 * Permisos: auth:sanctum
 */
class ParentProfileController extends Controller
{
    /**
     * Listar todos los perfiles de padres con paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ParentProfile::with(['account', 'students']);

        // Filtros
        if ($request->filled('q')) {
            $query->whereHas('account', function ($q) use ($request) {
                $q->where('full_name', 'like', '%' . $request->q . '%')
                  ->orWhere('email', 'like', '%' . $request->q . '%');
            });
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        // Paginación
        $parents = $query->paginate($request->get('per_page', 15));

        return ParentResource::collection($parents);
    }

    /**
     * Mostrar un perfil de padre específico con estudiantes
     */
    public function show(ParentProfile $parent): ParentResource
    {
        $parent->load(['account', 'students']);
        return new ParentResource($parent);
    }

    /**
     * Crear un nuevo perfil de padre
     */
    public function store(StoreParentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $parent = ParentProfile::create($validated);
        $parent->load(['account', 'students']);

        return response()->json([
            'message' => 'Perfil de padre creado exitosamente',
            'parent' => new ParentResource($parent),
        ], 201);
    }

    /**
     * Actualizar un perfil de padre existente
     */
    public function update(UpdateParentRequest $request, ParentProfile $parent): JsonResponse
    {
        $validated = $request->validated();

        $parent->update($validated);
        $parent->load(['account', 'students']);

        return response()->json([
            'message' => 'Perfil de padre actualizado exitosamente',
            'parent' => new ParentResource($parent),
        ]);
    }

    /**
     * Eliminar un perfil de padre
     */
    public function destroy(ParentProfile $parent): JsonResponse
    {
        // Verificar si tiene estudiantes antes de eliminar
        if ($parent->students()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar el perfil porque tiene estudiantes asociados',
            ], 422);
        }

        $parent->delete();

        return response()->json([
            'message' => 'Perfil de padre eliminado exitosamente',
        ]);
    }
}
