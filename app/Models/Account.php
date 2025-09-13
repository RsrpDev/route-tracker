<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Account - Base de usuarios del sistema
 *
 * Este modelo representa la tabla 'accounts' que contiene todos los usuarios del sistema.
 * Implementa la autenticación de Laravel y maneja diferentes tipos de cuentas:
 * - admin: Administrador del sistema
 * - provider: Proveedor de transporte (conductor independiente, empresa, colegio prestador)
 * - driver: Conductor (independiente o empleado)
 * - parent: Padre de familia
 * - school: Colegio (con o sin servicio de transporte)
 *
 * Funcionalidades principales:
 * - Autenticación y autorización
 * - Verificación de cuentas
 * - Relaciones con perfiles específicos
 * - Gestión de tokens API
 */
class Account extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'accounts';
    protected $primaryKey = 'account_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'account_type',
        'full_name',
        'email',
        'password_hash',
        'phone_number',
        'id_number',
        'account_status',
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the name of the password field.
     */
    public function getPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Relación con el perfil de padre
     */
    public function parentProfile(): HasOne
    {
        return $this->hasOne(ParentProfile::class, 'account_id', 'account_id');
    }

    /**
     * Relación con el proveedor
     */
    public function provider(): HasOne
    {
        return $this->hasOne(Provider::class, 'account_id', 'account_id');
    }

    /**
     * Relación con el conductor independiente
     */
    public function independentDriver(): HasOne
    {
        return $this->hasOne(IndependentDriver::class, 'account_id', 'account_id');
    }

    /**
     * Relación con el conductor empleado
     */
    public function employedDriver(): HasOne
    {
        return $this->hasOne(Driver::class, 'account_id', 'account_id');
    }

    /**
     * Relación con la escuela
     */
    public function school(): HasOne
    {
        return $this->hasOne(School::class, 'account_id', 'account_id');
    }

    /**
     * Obtener las habilidades del token basadas en el tipo de cuenta
     */
    public function getTokenAbilities(): array
    {
        return match($this->account_type) {
            'admin' => ['*'],
            'provider' => [
                'routes:manage', 'drivers:manage', 'vehicles:manage',
                'route-assignments:manage', 'payments:read'
            ],
            'parent' => [
                'students:manage', 'enrollments:manage', 'payments:create',
                'payments:read', 'routes:read'
            ],
            'school' => [
                'students:read', 'providers:read', 'routes:read'
            ],
            default => []
        };
    }

    /**
     * Relación con el administrador que verificó la cuenta
     */
    public function verifier(): HasOne
    {
        return $this->hasOne(Account::class, 'account_id', 'verified_by');
    }

    /**
     * Scope para cuentas pendientes de verificación
     */
    public function scopePendingVerification($query)
    {
        return $query->where('verification_status', 'pending');
    }

    /**
     * Scope para cuentas verificadas
     */
    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    /**
     * Scope para cuentas rechazadas
     */
    public function scopeRejected($query)
    {
        return $query->where('verification_status', 'rejected');
    }

    /**
     * Verificar si la cuenta está verificada
     */
    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    /**
     * Verificar si la cuenta está pendiente
     */
    public function isPendingVerification(): bool
    {
        return $this->verification_status === 'pending';
    }

    /**
     * Verificar si la cuenta fue rechazada
     */
    public function isRejected(): bool
    {
        return $this->verification_status === 'rejected';
    }
}
