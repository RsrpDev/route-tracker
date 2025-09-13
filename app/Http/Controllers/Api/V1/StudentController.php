<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\EnrollStudentRequest;
use App\Http\Requests\Api\V1\StoreStudentRequest;
use App\Http\Requests\Api\V1\UpdateStudentRequest;
use App\Http\Resources\Api\V1\StudentResource;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Controlador para gestión de estudiantes
 *
 * Rutas:
 * - GET /api/v1/students - Listar estudiantes (por padre)
 * - GET /api/v1/students/{student} - Mostrar estudiante específico
 * - POST /api/v1/students - Crear nuevo estudiante
 * - PUT /api/v1/students/{student} - Actualizar estudiante
 * - DELETE /api/v1/students/{student} - Eliminar estudiante
 * - POST /api/v1/students/{student}/enroll - Inscribir estudiante en ruta
 *
 * Permisos: auth:sanctum
 */
class StudentController extends Controller
{
    /**
     * Listar todos los estudiantes con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Student::with(['parent', 'school']);

        // Filtros
        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('given_name', 'like', '%' . $request->q . '%')
                  ->orWhere('family_name', 'like', '%' . $request->q . '%')
                  ->orWhere('identity_number', 'like', '%' . $request->q . '%');
            });
        }

        // Ordenamiento
        $query->orderBy('created_at', 'desc');

        // Paginación
        $students = $query->paginate($request->get('per_page', 15));

        return StudentResource::collection($students);
    }

    /**
     * Mostrar un estudiante específico
     */
    public function show(Student $student): StudentResource
    {
        $student->load(['parent', 'school', 'enrollments']);
        return new StudentResource($student);
    }

    /**
     * Crear un nuevo estudiante
     */
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $student = Student::create($validated);
        $student->load(['parent', 'school']);

        return response()->json([
            'message' => 'Estudiante creado exitosamente',
            'student' => new StudentResource($student),
        ], 201);
    }

    /**
     * Actualizar un estudiante existente
     */
    public function update(UpdateStudentRequest $request, Student $student): JsonResponse
    {
        $validated = $request->validated();

        $student->update($validated);
        $student->load(['parent', 'school']);

        return response()->json([
            'message' => 'Estudiante actualizado exitosamente',
            'student' => new StudentResource($student),
        ]);
    }

    /**
     * Eliminar un estudiante
     */
    public function destroy(Student $student): JsonResponse
    {
        // Verificar si tiene inscripciones antes de eliminar
        if ($student->enrollments()->exists()) {
            return response()->json([
                'message' => 'No se puede eliminar el estudiante porque tiene inscripciones activas',
            ], 422);
        }

        $student->delete();

        return response()->json([
            'message' => 'Estudiante eliminado exitosamente',
        ]);
    }

    /**
     * Inscribir un estudiante en una ruta
     */
    public function enroll(EnrollStudentRequest $request, Student $student): JsonResponse
    {
        $validated = $request->validated();

        // Verificar que el estudiante no esté ya inscrito en la misma ruta
        $existingEnrollment = $student->enrollments()
            ->where('route_id', $validated['route_id'])
            ->where('enrollment_status', '!=', 'cancelled')
            ->first();

        if ($existingEnrollment) {
            return response()->json([
                'message' => 'El estudiante ya está inscrito en esta ruta',
            ], 422);
        }

        // Crear la inscripción
        $enrollment = Enrollment::create([
            'student_id' => $student->student_id,
            'route_id' => $validated['route_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'] ?? null,
            'months_agreed' => $validated['months_agreed'],
            'enrollment_status' => 'pending',
        ]);

        $enrollment->load(['student', 'route']);

        return response()->json([
            'message' => 'Estudiante inscrito exitosamente',
            'enrollment' => new \App\Http\Resources\Api\V1\EnrollmentResource($enrollment),
        ], 201);
    }
}
