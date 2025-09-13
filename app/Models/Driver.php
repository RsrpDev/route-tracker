<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Modelo Driver - Conductores empleados del sistema
 *
 * Este modelo representa la tabla 'drivers' que contiene todos los conductores empleados
 * por empresas de transporte o colegios. Se diferencia de los conductores independientes
 * en que estos tienen un empleador y reciben un salario fijo.
 *
 * Funcionalidades principales:
 * - Gestión de conductores empleados
 * - Relaciones con cuentas, proveedores y asignaciones de rutas
 * - Validación de licencias y certificaciones
 * - Cálculo de salarios y tarifas
 *
 * Estados del conductor:
 * - active: Activo
 * - inactive: Inactivo
 * - suspended: Suspendido
 * - terminated: Terminado
 */
class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';
    protected $primaryKey = 'driver_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'account_id',
        'provider_id',
        'given_name',
        'family_name',
        'id_number',
        'document_type',
        'birth_city',
        'birth_department',
        'birth_date',
        'blood_type',
        'phone_number',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'license_number',
        'license_category',
        'license_expiration',
        'license_issuing_authority',
        'license_issuing_city',
        'license_issue_date',
        'has_medical_certificate',
        'medical_certificate_expiration',
        'has_psychological_certificate',
        'psychological_certificate_expiration',
        'years_experience',
        'employment_status',
        'hire_date',
        'hourly_rate',
        'monthly_salary',
        'driver_status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'license_expiration' => 'date',
        'license_issue_date' => 'date',
        'medical_certificate_expiration' => 'date',
        'psychological_certificate_expiration' => 'date',
        'hire_date' => 'date',
        'years_experience' => 'integer',
        'hourly_rate' => 'decimal:2',
        'monthly_salary' => 'decimal:2',
        'has_medical_certificate' => 'boolean',
        'has_psychological_certificate' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con la cuenta
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }

    /**
     * Relación con el proveedor
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'provider_id');
    }

    /**
     * Relación con las asignaciones de ruta
     */
    public function routeAssignments(): HasMany
    {
        return $this->hasMany(RouteAssignment::class, 'driver_id', 'driver_id');
    }

    /**
     * Relación con los vehículos a través de las asignaciones de ruta
     */
    public function vehicles(): HasManyThrough
    {
        return $this->hasManyThrough(
            Vehicle::class,
            RouteAssignment::class,
            'driver_id', // Foreign key en route_assignments
            'vehicle_id', // Foreign key en vehicles
            'driver_id', // Local key en drivers
            'vehicle_id' // Local key en route_assignments
        );
    }
}
