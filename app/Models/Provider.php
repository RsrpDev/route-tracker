<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Provider - Proveedores de transporte
 *
 * Este modelo representa la tabla 'providers' que contiene todos los proveedores de transporte del sistema.
 * Maneja diferentes tipos de proveedores:
 * - driver: Conductor independiente
 * - company: Empresa de transporte
 * - school_provider: Colegio que ofrece servicio de transporte
 *
 * Funcionalidades principales:
 * - Gestión de proveedores de transporte
 * - Relaciones con cuentas, vehículos, rutas y conductores
 * - Cálculo de estadísticas y métricas
 * - Validación de licencias y certificaciones
 */
class Provider extends Model
{
    use HasFactory;

    protected $table = 'providers';
    protected $primaryKey = 'provider_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'account_id',
        'provider_type',
        'display_name',
        'contact_email',
        'contact_phone',
        'linked_school_id',
        'default_commission_rate',
        'provider_status',
        // Campos de conductor (solo para provider_type = 'driver')
        'driver_license_number',
        'driver_license_category',
        'driver_license_expiration',
        'driver_years_experience',
        'driver_status',
    ];

    protected $casts = [
        'default_commission_rate' => 'decimal:2',
        'driver_license_expiration' => 'date',
        'driver_years_experience' => 'integer',
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
     * Relación con la escuela vinculada (opcional)
     */
    public function linkedSchool(): BelongsTo
    {
        return $this->belongsTo(School::class, 'linked_school_id', 'school_id');
    }

    /**
     * Relación con los conductores
     */
    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'provider_id', 'provider_id');
    }

    /**
     * Relación con el conductor independiente
     */
    public function independentDriver(): HasOne
    {
        return $this->hasOne(IndependentDriver::class, 'provider_id', 'provider_id');
    }

    /**
     * Relación con los vehículos
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'provider_id', 'provider_id');
    }

    /**
     * Relación con las rutas
     */
    public function routes(): HasMany
    {
        return $this->hasMany(Route::class, 'provider_id', 'provider_id');
    }

    /**
     * Obtener rutas activas del proveedor
     */
    public function activeRoutes(): HasMany
    {
        return $this->hasMany(Route::class, 'provider_id', 'provider_id')->where('active_flag', true);
    }

    /**
     * Obtener escuelas a las que ofrece servicios
     */
    public function serviceSchools()
    {
        return School::whereHas('routes', function($query) {
            $query->where('provider_id', $this->provider_id);
        })->distinct();
    }

    /**
     * Obtener rutas por escuela específica
     */
    public function routesBySchool($schoolId)
    {
        return $this->routes()->where('school_id', $schoolId);
    }

    /**
     * Relación con contratos de transporte de estudiantes
     */
    public function studentContracts(): HasMany
    {
        return $this->hasMany(StudentTransportContract::class, 'provider_id', 'provider_id');
    }

    /**
     * Obtener estudiantes activos del proveedor
     */
    public function getActiveStudents()
    {
        return $this->studentContracts()
            ->where('contract_status', 'active')
            ->with('student')
            ->get()
            ->pluck('student');
    }

    /**
     * Obtener contratos activos del proveedor
     */
    public function getActiveContracts()
    {
        return $this->studentContracts()
            ->where('contract_status', 'active')
            ->with(['student', 'pickupRoute', 'dropoffRoute'])
            ->get();
    }

    /**
     * Contar estudiantes activos
     */
    public function getActiveStudentsCount(): int
    {
        return $this->studentContracts()
            ->where('contract_status', 'active')
            ->count();
    }

    /**
     * Verificar si es un conductor independiente
     */
    public function isIndependentDriver(): bool
    {
        return $this->provider_type === 'driver';
    }

    /**
     * Verificar si es una empresa
     */
    public function isCompany(): bool
    {
        return $this->provider_type === 'company';
    }

    /**
     * Verificar si es un colegio proveedor
     */
    public function isSchoolProvider(): bool
    {
        return $this->provider_type === 'school_provider';
    }

    /**
     * Obtener el nombre completo del conductor independiente
     */
    public function getDriverFullName(): string
    {
        if (!$this->isIndependentDriver()) {
            return $this->display_name;
        }

        return $this->display_name;
    }

    /**
     * Verificar si el conductor independiente tiene licencia válida
     */
    public function hasValidLicense(): bool
    {
        if (!$this->isIndependentDriver()) {
            return false;
        }

        return $this->driver_license_expiration &&
               $this->driver_license_expiration->isFuture() &&
               $this->driver_status === 'approved';
    }

    /**
     * Obtener el estado de la licencia como texto
     */
    public function getLicenseStatusText(): string
    {
        if (!$this->isIndependentDriver()) {
            return 'N/A';
        }

        if (!$this->driver_license_expiration) {
            return 'Sin licencia';
        }

        if ($this->driver_license_expiration->isPast()) {
            return 'Licencia vencida';
        }

        return match($this->driver_status) {
            'pending' => 'Pendiente de aprobación',
            'approved' => 'Aprobada',
            'rejected' => 'Rechazada',
            default => 'Desconocido'
        };
    }
}
