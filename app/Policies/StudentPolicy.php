<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\Student;

/**
 * Policy para el modelo Student
 */
class StudentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Account $user): bool
    {
        // Solo admins pueden ver todos los estudiantes
        if ($user->account_type === 'admin') {
            return true;
        }

        // Padres pueden ver sus propios estudiantes
        if ($user->account_type === 'parent') {
            return true;
        }

        // Proveedores y escuelas pueden ver estudiantes
        return in_array($user->account_type, ['provider', 'school']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Account $user, Student $student): bool
    {
        // Admin puede ver cualquier estudiante
        if ($user->account_type === 'admin') {
            return true;
        }

        // Padre puede ver sus propios estudiantes
        if ($user->account_type === 'parent') {
            return $user->parentProfile && $user->parentProfile->students->contains($student->student_id);
        }

        // Proveedor puede ver estudiantes si tiene rutas activas
        if ($user->account_type === 'provider') {
            // Verificar si el estudiante está inscrito en alguna ruta del proveedor
            return $student->enrollments()
                ->whereHas('route', function ($query) use ($user) {
                    $query->where('provider_id', $user->provider->provider_id);
                })
                ->exists();
        }

        // Escuela puede ver estudiantes si pertenecen a ella
        if ($user->account_type === 'school') {
            return $student->school_id === $user->school->school_id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Account $user): bool
    {
        // Solo admins y padres pueden crear estudiantes
        return in_array($user->account_type, ['admin', 'parent']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Account $user, Student $student): bool
    {
        // Admin puede actualizar cualquier estudiante
        if ($user->account_type === 'admin') {
            return true;
        }

        // Padre puede actualizar sus propios estudiantes
        if ($user->account_type === 'parent') {
            return $user->parentProfile && $user->parentProfile->students->contains($student->student_id);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Account $user, Student $student): bool
    {
        // Solo admins pueden eliminar estudiantes
        return $user->account_type === 'admin';
    }
}
