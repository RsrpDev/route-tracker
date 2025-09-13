<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * Modelo StudentTransportContract - Contratos de transporte estudiantil
 *
 * Este modelo representa la tabla 'student_transport_contracts' que contiene todos los
 * contratos de transporte entre estudiantes y proveedores. Estos contratos definen
 * las condiciones del servicio de transporte, incluyendo rutas, horarios y tarifas.
 *
 * Funcionalidades principales:
 * - Gestión de contratos de transporte
 * - Relaciones con estudiantes, proveedores y rutas
 * - Gestión de fechas de inicio y fin de contrato
 * - Cálculo de tarifas mensuales
 * - Validación de estado y vigencia del contrato
 *
 * Estados del contrato:
 * - active: Activo
 * - inactive: Inactivo
 * - suspended: Suspendido
 * - expired: Expirado
 * - cancelled: Cancelado
 *
 * Relaciones principales:
 * - student: Estudiante que tiene el contrato
 * - provider: Proveedor que ofrece el servicio
 * - pickupRoute: Ruta de recogida (casa a escuela)
 * - dropoffRoute: Ruta de entrega (escuela a casa)
 * - subscription: Suscripción asociada al contrato
 */
class StudentTransportContract extends Model
{
    use HasFactory;

    protected $table = 'student_transport_contracts';
    protected $primaryKey = 'contract_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'student_id',
        'provider_id',
        'contract_start_date',
        'contract_end_date',
        'contract_status',
        'pickup_route_id',
        'dropoff_route_id',
        'monthly_fee',
        'special_instructions',
    ];

    protected $casts = [
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'monthly_fee' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el estudiante
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    /**
     * Relación con el proveedor
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'provider_id');
    }

    /**
     * Relación con la ruta de recogida (casa → colegio)
     */
    public function pickupRoute(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'pickup_route_id', 'route_id');
    }

    /**
     * Relación con la ruta de entrega (colegio → casa)
     */
    public function dropoffRoute(): BelongsTo
    {
        return $this->belongsTo(Route::class, 'dropoff_route_id', 'route_id');
    }

    /**
     * Relación con la suscripción (si existe)
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class, 'contract_id', 'contract_id');
    }

    /**
     * Accessor para obtener el conductor asignado
     */
    public function getDriverAttribute()
    {
        // Si el proveedor es un conductor independiente, obtenerlo directamente
        if ($this->provider && $this->provider->provider_type === 'driver') {
            return Driver::with('provider')->where('provider_id', $this->provider_id)->first();
        }

        // Si no, intentar obtener el conductor a través de la ruta asignada
        if ($this->pickup_route_id) {
            $assignment = RouteAssignment::with('driver.provider')->where('route_id', $this->pickup_route_id)
                ->where('assignment_status', 'active')
                ->first();

            if ($assignment) {
                return $assignment->driver;
            }
        }

        return null;
    }

    /**
     * Scope para contratos activos
     */
    public function scopeActive($query)
    {
        return $query->where('contract_status', 'active');
    }

    /**
     * Scope para contratos por proveedor
     */
    public function scopeByProvider($query, $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    /**
     * Scope para contratos por estudiante
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Verificar si el contrato está activo
     */
    public function isActive(): bool
    {
        return $this->contract_status === 'active';
    }

    /**
     * Verificar si el contrato está vigente (dentro del período de fechas)
     */
    public function isCurrent(): bool
    {
        $now = now()->toDateString();

        if ($this->contract_start_date > $now) {
            return false; // Contrato aún no inicia
        }

        if ($this->contract_end_date && $this->contract_end_date < $now) {
            return false; // Contrato ya expiró
        }

        return true;
    }

    /**
     * Obtener el nombre completo del estudiante
     */
    public function getStudentNameAttribute(): string
    {
        return $this->student ? $this->student->first_name . ' ' . $this->student->last_name : 'N/A';
    }

    /**
     * Obtener el nombre del proveedor
     */
    public function getProviderNameAttribute(): string
    {
        return $this->provider ? $this->provider->display_name : 'N/A';
    }

    /**
     * Obtener información de las rutas
     */
    public function getRoutesInfoAttribute(): array
    {
        return [
            'pickup' => $this->pickupRoute ? [
                'name' => $this->pickupRoute->route_name,
                'origin' => $this->pickupRoute->origin_address,
                'destination' => $this->pickupRoute->destination_address,
                'departure_time' => $this->pickupRoute->departure_time,
            ] : null,
            'dropoff' => $this->dropoffRoute ? [
                'name' => $this->dropoffRoute->route_name,
                'origin' => $this->dropoffRoute->origin_address,
                'destination' => $this->dropoffRoute->destination_address,
                'departure_time' => $this->dropoffRoute->departure_time,
            ] : null,
        ];
    }

    /**
     * Obtener días restantes del contrato
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->contract_end_date) {
            return null; // Contrato sin fecha de fin
        }

        return max(0, now()->diffInDays($this->contract_end_date, false));
    }
}
