<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Route - Rutas de transporte del sistema
 *
 * Este modelo representa la tabla 'routes' que contiene todas las rutas de transporte
 * disponibles en el sistema. Las rutas conectan puntos de origen con escuelas y
 * son gestionadas por proveedores de transporte.
 *
 * Funcionalidades principales:
 * - Gestión de rutas de transporte
 * - Cálculo de capacidad y ocupación
 * - Relaciones con proveedores, escuelas y contratos
 * - Gestión de horarios y días de servicio
 * - Logs de actividad de rutas
 *
 * Tipos de ruta:
 * - pickup: Ruta de recogida (casa a escuela)
 * - dropoff: Ruta de entrega (escuela a casa)
 *
 * Relaciones principales:
 * - provider: Proveedor que gestiona la ruta
 * - school: Escuela de destino
 * - routeAssignments: Asignaciones de conductores y vehículos
 * - transportContracts: Contratos que usan esta ruta
 * - routeLogs: Logs de actividad de la ruta
 */
class Route extends Model
{
    use HasFactory;

    protected $table = 'routes';
    protected $primaryKey = 'route_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'provider_id',
        'school_id',
        'route_name',
        'origin_address',
        'destination_address',
        'capacity',
        'monthly_price',
        'pickup_time',
        'dropoff_time',
        'schedule_days',
        'route_description',
        'estimated_duration_minutes',
        'active_flag',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'monthly_price' => 'decimal:2',
        'pickup_time' => 'datetime:H:i',
        'dropoff_time' => 'datetime:H:i',
        'schedule_days' => 'array',
        'estimated_duration_minutes' => 'integer',
        'active_flag' => 'boolean',
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
     * Relación con la escuela
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'school_id');
    }

    /**
     * Relación con las asignaciones de ruta
     */
    public function routeAssignments(): HasMany
    {
        return $this->hasMany(RouteAssignment::class, 'route_id', 'route_id');
    }


    /**
     * Relación con los contratos de transporte (pickup)
     */
    public function transportContracts(): HasMany
    {
        return $this->hasMany(StudentTransportContract::class, 'pickup_route_id', 'route_id');
    }

    /**
     * Relación con los contratos de transporte (dropoff)
     */
    public function dropoffContracts(): HasMany
    {
        return $this->hasMany(StudentTransportContract::class, 'dropoff_route_id', 'route_id');
    }

    /**
     * Relación con logs de rutas
     */
    public function routeLogs(): HasMany
    {
        return $this->hasMany(RouteLog::class, 'route_id', 'route_id');
    }


    /**
     * Verificar si la ruta está activa
     */
    public function isActive(): bool
    {
        return $this->active_flag === true;
    }

    /**
     * Obtener el nombre de la escuela de destino
     */
    public function getSchoolName(): string
    {
        return $this->school ? $this->school->legal_name : 'Sin escuela asignada';
    }

    /**
     * Verificar si la ruta tiene capacidad disponible
     */
    public function hasAvailableCapacity(): bool
    {
        $contractCount = $this->transportContracts()->where('contract_status', 'active')->count() +
                        $this->dropoffContracts()->where('contract_status', 'active')->count();
        return $contractCount < $this->capacity;
    }

    /**
     * Obtener la capacidad disponible
     */
    public function getAvailableCapacity(): int
    {
        $contractCount = $this->transportContracts()->where('contract_status', 'active')->count() +
                        $this->dropoffContracts()->where('contract_status', 'active')->count();
        return max(0, $this->capacity - $contractCount);
    }

    /**
     * Obtener el porcentaje de ocupación
     */
    public function getOccupancyPercentage(): float
    {
        if ($this->capacity === 0) {
            return 0;
        }

        $contractCount = $this->transportContracts()->where('contract_status', 'active')->count() +
                        $this->dropoffContracts()->where('contract_status', 'active')->count();
        return round(($contractCount / $this->capacity) * 100, 2);
    }

    /**
     * Scope para rutas activas
     */
    public function scopeActive($query)
    {
        return $query->where('active_flag', true);
    }

    /**
     * Scope para rutas por escuela
     */
    public function scopeBySchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /**
     * Scope para rutas por proveedor
     */
    public function scopeByProvider($query, $providerId)
    {
        return $query->where('provider_id', $providerId);
    }
}
