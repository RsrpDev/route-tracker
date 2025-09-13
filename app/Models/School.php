<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Modelo School - Colegios del sistema
 *
 * Este modelo representa la tabla 'schools' que contiene todos los colegios
 * registrados en el sistema. Los colegios pueden ofrecer o no servicio de transporte
 * y pueden tener proveedores vinculados para gestionar sus rutas.
 *
 * Funcionalidades principales:
 * - Gestión de colegios y su información
 * - Relaciones con estudiantes, rutas y proveedores
 * - Gestión de contratos de transporte
 * - Estadísticas y métricas del colegio
 *
 * Tipos de colegio:
 * - Con servicio de transporte: Tiene proveedores vinculados
 * - Sin servicio de transporte: Solo gestiona estudiantes
 *
 * Relaciones principales:
 * - account: Cuenta de usuario asociada
 * - students: Estudiantes matriculados
 * - routes: Rutas de transporte del colegio
 * - linkedProvider: Proveedor vinculado (si tiene servicio)
 */
class School extends Model
{
    use HasFactory;

    protected $table = 'schools';
    protected $primaryKey = 'school_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'account_id',
        'legal_name',
        'rector_name',
        'nit',
        'phone_number',
        'address',
        'has_transport_service',
    ];

    protected $casts = [
        'has_transport_service' => 'boolean',
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
     * Relación con los estudiantes
     */
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'school_id', 'school_id');
    }

    /**
     * Relación con el proveedor vinculado (opcional)
     */
    public function linkedProvider(): HasOne
    {
        return $this->hasOne(Provider::class, 'linked_school_id', 'school_id');
    }

    /**
     * Relación con las rutas que tienen esta escuela como destino
     */
    public function routes(): HasMany
    {
        return $this->hasMany(Route::class, 'school_id', 'school_id');
    }

    /**
     * Relación con las inscripciones de los estudiantes de la escuela (DEPRECATED)
     * @deprecated Usar transportContracts() en su lugar
     */
    public function enrollments()
    {
        return \App\Models\Enrollment::whereHas('student', function($query) {
            $query->where('school_id', $this->school_id);
        });
    }

    /**
     * Relación con los contratos de transporte de los estudiantes de la escuela
     */
    public function transportContracts()
    {
        return \App\Models\StudentTransportContract::whereHas('student', function($query) {
            $query->where('school_id', $this->school_id);
        });
    }

    /**
     * Obtener rutas activas de la escuela
     */
    public function activeRoutes(): HasMany
    {
        return $this->hasMany(Route::class, 'school_id', 'school_id')->where('active_flag', true);
    }

    /**
     * Obtener proveedores que ofrecen servicios a esta escuela
     */
    public function serviceProviders()
    {
        return Provider::whereHas('routes', function($query) {
            $query->where('school_id', $this->school_id);
        })->distinct();
    }

    /**
     * Obtener estadísticas de la escuela
     */
    public function getStatistics(): array
    {
        return [
            'total_students' => $this->students()->count(),
            'total_routes' => $this->routes()->count(),
            'active_routes' => $this->activeRoutes()->count(),
            'total_providers' => $this->serviceProviders()->count(),
            'total_transport_contracts' => $this->transportContracts()->count(),
            'active_transport_contracts' => $this->transportContracts()->where('contract_status', 'active')->count(),
        ];
    }
}
