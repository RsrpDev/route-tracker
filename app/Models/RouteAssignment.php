<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo RouteAssignment - Asignaciones de rutas
 *
 * Este modelo representa la tabla 'route_assignments' que contiene todas las
 * asignaciones de conductores y vehículos a rutas específicas. Estas asignaciones
 * definen quién maneja qué ruta y con qué vehículo durante un período determinado.
 *
 * Funcionalidades principales:
 * - Gestión de asignaciones de rutas
 * - Relaciones con rutas, conductores y vehículos
 * - Gestión de fechas de inicio y fin de asignación
 * - Control de estado de asignaciones
 * - Optimización de recursos de transporte
 *
 * Estados de la asignación:
 * - active: Activa
 * - inactive: Inactiva
 * - pending: Pendiente
 * - completed: Completada
 * - cancelled: Cancelada
 *
 * Relaciones principales:
 * - route: Ruta asignada
 * - driver: Conductor asignado
 * - vehicle: Vehículo asignado
 */
class RouteAssignment extends Model
{
    use HasFactory;

    protected $table = 'route_assignments';
    protected $primaryKey = 'assignment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'route_id',
        'driver_id',
        'vehicle_id',
        'start_date',
        'end_date',
        'assignment_status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con la ruta
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'route_id', 'route_id');
    }

    /**
     * Relación con el conductor
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    /**
     * Relación con el vehículo
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }
}
