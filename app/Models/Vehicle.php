<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Modelo Vehicle - Vehículos del sistema
 *
 * Este modelo representa la tabla 'vehicles' que contiene todos los vehículos
 * registrados en el sistema. Los vehículos pertenecen a proveedores de transporte
 * y son asignados a rutas específicas con conductores.
 *
 * Funcionalidades principales:
 * - Gestión de vehículos y su información técnica
 * - Relaciones con proveedores y asignaciones de rutas
 * - Gestión de documentación y seguros
 * - Control de mantenimiento y revisiones
 * - Validación de vencimientos de documentos
 *
 * Tipos de vehículo:
 * - bus: Autobús escolar
 * - van: Van de transporte
 * - car: Automóvil particular
 *
 * Estados del vehículo:
 * - active: Activo
 * - inactive: Inactivo
 * - maintenance: En mantenimiento
 * - retired: Retirado
 *
 * Relaciones principales:
 * - provider: Proveedor propietario del vehículo
 * - routeAssignments: Asignaciones a rutas
 * - drivers: Conductores asignados al vehículo
 */
class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';
    protected $primaryKey = 'vehicle_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'provider_id',
        'plate',
        'brand',
        'model_year',
        'serial_number',
        'engine_number',
        'chassis_number',
        'color',
        'fuel_type',
        'cylinder_capacity',
        'vehicle_class',
        'service_type',
        'capacity',
        'soat_expiration',
        'soat_number',
        'insurance_expiration',
        'insurance_company',
        'insurance_policy_number',
        'technical_inspection_expiration',
        'revision_expiration',
        'odometer_reading',
        'last_maintenance_date',
        'next_maintenance_date',
        'vehicle_status',
    ];

    protected $casts = [
        'model_year' => 'integer',
        'cylinder_capacity' => 'integer',
        'capacity' => 'integer',
        'odometer_reading' => 'integer',
        'soat_expiration' => 'date',
        'insurance_expiration' => 'date',
        'technical_inspection_expiration' => 'date',
        'revision_expiration' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
        return $this->hasMany(RouteAssignment::class, 'vehicle_id', 'vehicle_id');
    }

    /**
     * Relación con los conductores a través de las asignaciones de ruta
     */
    public function drivers(): HasManyThrough
    {
        return $this->hasManyThrough(
            Driver::class,
            RouteAssignment::class,
            'vehicle_id', // Foreign key en route_assignments
            'driver_id', // Foreign key en drivers
            'vehicle_id', // Local key en vehicles
            'driver_id' // Local key en route_assignments
        );
    }
}
