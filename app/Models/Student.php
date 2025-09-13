<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo Student - Estudiantes del sistema
 *
 * Este modelo representa la tabla 'students' que contiene todos los estudiantes
 * registrados en el sistema. Los estudiantes están asociados a un padre y pueden
 * estar matriculados en una escuela y tener contratos de transporte.
 *
 * Funcionalidades principales:
 * - Gestión de estudiantes y su información
 * - Relaciones con padres, escuelas y contratos de transporte
 * - Verificación de estado de transporte
 * - Gestión de matrículas y grados
 *
 * Estados del estudiante:
 * - active: Activo
 * - inactive: Inactivo
 * - graduated: Graduado
 * - transferred: Transferido
 *
 * Relaciones principales:
 * - parentProfile: Padre del estudiante
 * - school: Escuela donde está matriculado
 * - transportContracts: Contratos de transporte
 * - transportContract: Contrato activo de transporte
 */
class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'parent_id',
        'given_name',
        'family_name',
        'identity_number',
        'birth_date',
        'school_id',
        'grade',
        'shift',
        'address',
        'phone_number',
        'status',
        'has_transport',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Accessor para first_name (compatibilidad con vistas)
     */
    public function getFirstNameAttribute(): string
    {
        return $this->given_name;
    }

    /**
     * Accessor para last_name (compatibilidad con vistas)
     */
    public function getLastNameAttribute(): string
    {
        return $this->family_name;
    }

    /**
     * Relación con el perfil de padre
     */
    public function parentProfile(): BelongsTo
    {
        return $this->belongsTo(ParentProfile::class, 'parent_id', 'parent_id');
    }

    /**
     * Alias para la relación con el padre (compatibilidad)
     */
    public function parent(): BelongsTo
    {
        return $this->parentProfile();
    }

    /**
     * Relación con la escuela (opcional)
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'school_id');
    }

    /**
     * Relación con los contratos de transporte (plural)
     */
    public function transportContracts(): HasMany
    {
        return $this->hasMany(StudentTransportContract::class, 'student_id', 'student_id');
    }

    /**
     * Relación con el contrato de transporte activo (singular)
     */
    public function transportContract(): HasOne
    {
        return $this->hasOne(StudentTransportContract::class, 'student_id', 'student_id')
            ->where('contract_status', 'active');
    }

    /**
     * Obtener el proveedor actual del estudiante
     */
    public function getCurrentProvider(): ?Provider
    {
        $contract = $this->transportContract()
            ->where('contract_status', 'active')
            ->first();

        return $contract ? $contract->provider : null;
    }

    /**
     * Accessor para has_transport que se sincroniza automáticamente con los contratos
     */
    public function getHasTransportAttribute(): bool
    {
        return $this->hasTransport();
    }

    /**
     * Verificar si el estudiante tiene transporte
     */
    public function hasTransport(): bool
    {
        return $this->transportContract()
            ->where('contract_status', 'active')
            ->exists();
    }
}
