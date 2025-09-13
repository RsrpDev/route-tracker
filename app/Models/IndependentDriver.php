<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo IndependentDriver - Conductores independientes del sistema
 *
 * Este modelo representa la tabla 'independent_drivers' que contiene todos los conductores
 * independientes que operan su propio negocio de transporte. Se diferencian de los conductores
 * empleados en que no tienen un empleador y manejan sus propias tarifas.
 *
 * Funcionalidades principales:
 * - Gestión de conductores independientes
 * - Validación de licencias y certificaciones
 * - Cálculo de tarifas por hora y mensuales
 * - Verificación de documentos y certificados
 * - Relaciones con cuentas y proveedores
 *
 * Estados del conductor:
 * - active: Activo
 * - inactive: Inactivo
 * - pending_verification: Pendiente de verificación
 * - suspended: Suspendido
 * - terminated: Terminado
 */
class IndependentDriver extends Model
{
    use HasFactory;

    protected $table = 'independent_drivers';
    protected $primaryKey = 'independent_driver_id';

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
        'registration_date',
        'hourly_rate',
        'monthly_rate',
        'driver_status',
        'verification_notes',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'license_expiration' => 'date',
        'license_issue_date' => 'date',
        'medical_certificate_expiration' => 'date',
        'psychological_certificate_expiration' => 'date',
        'registration_date' => 'date',
        'verified_at' => 'datetime',
        'has_medical_certificate' => 'boolean',
        'has_psychological_certificate' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
    ];

    /**
     * Relación con Account
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id', 'account_id');
    }

    /**
     * Relación con Provider
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id', 'provider_id');
    }

    /**
     * Relación con el usuario que verificó
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'verified_by', 'account_id');
    }

    /**
     * Accessor para el nombre completo
     */
    public function getFullNameAttribute(): string
    {
        return $this->given_name . ' ' . $this->family_name;
    }

    /**
     * Accessor para el estado de la licencia
     */
    public function getLicenseStatusAttribute(): string
    {
        if ($this->license_expiration < now()) {
            return 'expired';
        } elseif ($this->license_expiration < now()->addDays(30)) {
            return 'expiring_soon';
        }
        return 'valid';
    }

    /**
     * Accessor para el estado de certificación médica
     */
    public function getMedicalCertificateStatusAttribute(): string
    {
        if (!$this->has_medical_certificate || !$this->medical_certificate_expiration) {
            return 'not_required';
        }

        if ($this->medical_certificate_expiration < now()) {
            return 'expired';
        } elseif ($this->medical_certificate_expiration < now()->addDays(30)) {
            return 'expiring_soon';
        }
        return 'valid';
    }

    /**
     * Accessor para el estado de certificación psicológica
     */
    public function getPsychologicalCertificateStatusAttribute(): string
    {
        if (!$this->has_psychological_certificate || !$this->psychological_certificate_expiration) {
            return 'not_required';
        }

        if ($this->psychological_certificate_expiration < now()) {
            return 'expired';
        } elseif ($this->psychological_certificate_expiration < now()->addDays(30)) {
            return 'expiring_soon';
        }
        return 'valid';
    }

    /**
     * Scope para conductores activos
     */
    public function scopeActive($query)
    {
        return $query->where('driver_status', 'active');
    }

    /**
     * Scope para conductores pendientes de verificación
     */
    public function scopePendingVerification($query)
    {
        return $query->where('driver_status', 'pending_verification');
    }

    /**
     * Scope para conductores con licencias próximas a vencer
     */
    public function scopeWithExpiringLicenses($query)
    {
        return $query->where('license_expiration', '<=', now()->addDays(30))
                    ->where('license_expiration', '>', now());
    }
}
