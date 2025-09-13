<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Modelo RouteLog - Logs de actividad de rutas
 *
 * Este modelo representa la tabla 'route_logs' que contiene todos los logs
 * de actividad de las rutas de transporte. Registra eventos como inicio de ruta,
 * recogidas, entregas, incidentes y otros eventos importantes durante el servicio.
 *
 * Funcionalidades principales:
 * - Registro de actividad de rutas
 * - Seguimiento de ubicación GPS
 * - Gestión de horarios y retrasos
 * - Registro de incidentes y observaciones
 * - Control de estudiantes recogidos y entregados
 * - Monitoreo de condiciones del vehículo
 *
 * Tipos de actividad:
 * - start: Inicio de ruta
 * - pickup: Recogida de estudiantes
 * - dropoff: Entrega de estudiantes
 * - end: Fin de ruta
 * - break: Descanso del conductor
 * - incident: Incidente o problema
 *
 * Estados de tiempo:
 * - on_time: A tiempo
 * - early: Temprano
 * - late: Tarde
 * - delayed: Retrasado
 * - cancelled: Cancelado
 *
 * Relaciones principales:
 * - route: Ruta asociada al log
 * - driver: Conductor que registró el log
 * - vehicle: Vehículo utilizado
 */
class RouteLog extends Model
{
    use HasFactory;

    protected $table = 'route_logs';
    protected $primaryKey = 'log_id';

    protected $fillable = [
        'route_id',
        'driver_id',
        'vehicle_id',
        'activity_type',
        'activity_description',
        'latitude',
        'longitude',
        'address',
        'city',
        'department',
        'scheduled_time',
        'actual_time',
        'status',
        'delay_minutes',
        'observations',
        'incident_details',
        'students_picked_up',
        'students_dropped_off',
        'fuel_level',
        'odometer_reading',
        'weather_conditions',
        'traffic_conditions',
        'gps_enabled',
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'actual_time' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'fuel_level' => 'decimal:2',
        'gps_enabled' => 'boolean',
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

    /**
     * Accessor para el estado formateado
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'on_time' => 'A tiempo',
            'early' => 'Temprano',
            'late' => 'Tarde',
            'delayed' => 'Retrasado',
            'cancelled' => 'Cancelado',
            default => 'Desconocido'
        };
    }

    /**
     * Accessor para el tipo de actividad formateado
     */
    public function getActivityTypeTextAttribute(): string
    {
        return match($this->activity_type) {
            'start' => 'Inicio de Ruta',
            'pickup' => 'Recogida',
            'dropoff' => 'Entrega',
            'end' => 'Fin de Ruta',
            'break' => 'Descanso',
            'incident' => 'Incidente',
            default => 'Desconocido'
        };
    }

    /**
     * Accessor para el tiempo de retraso formateado
     */
    public function getDelayTextAttribute(): string
    {
        if ($this->delay_minutes <= 0) {
            return 'Sin retraso';
        }

        if ($this->delay_minutes < 60) {
            return "{$this->delay_minutes} minutos";
        }

        $hours = floor($this->delay_minutes / 60);
        $minutes = $this->delay_minutes % 60;

        if ($minutes > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$hours} horas";
    }

    /**
     * Scope para logs de hoy
     */
    public function scopeToday($query)
    {
        return $query->whereDate('actual_time', today());
    }

    /**
     * Scope para logs de una ruta específica
     */
    public function scopeForRoute($query, $routeId)
    {
        return $query->where('route_id', $routeId);
    }

    /**
     * Scope para logs de un conductor específico
     */
    public function scopeForDriver($query, $driverId)
    {
        return $query->where('driver_id', $driverId);
    }

    /**
     * Scope para logs de un tipo de actividad específico
     */
    public function scopeActivityType($query, $activityType)
    {
        return $query->where('activity_type', $activityType);
    }

    /**
     * Método para calcular el retraso automáticamente
     */
    public function calculateDelay(): void
    {
        if ($this->scheduled_time && $this->actual_time) {
            $scheduled = Carbon::parse($this->scheduled_time);
            $actual = Carbon::parse($this->actual_time);

            $this->delay_minutes = $actual->diffInMinutes($scheduled, false);

            if ($this->delay_minutes > 0) {
                $this->status = $this->delay_minutes > 15 ? 'late' : 'on_time';
            } elseif ($this->delay_minutes < -5) {
                $this->status = 'early';
            } else {
                $this->status = 'on_time';
            }
        }
    }

    /**
     * Método para obtener la ubicación formateada
     */
    public function getFormattedLocationAttribute(): string
    {
        if ($this->address) {
            return $this->address;
        }

        if ($this->latitude && $this->longitude) {
            return "Lat: {$this->latitude}, Lng: {$this->longitude}";
        }

        return 'Ubicación no disponible';
    }
}
