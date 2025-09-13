<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Modelo ParentProfile - Perfiles de padres de familia
 *
 * Este modelo representa la tabla 'parents' que contiene los perfiles específicos
 * de los padres de familia en el sistema. Extiende la información básica de la cuenta
 * con datos específicos relacionados con la gestión de sus hijos.
 *
 * Funcionalidades principales:
 * - Gestión de perfiles de padres
 * - Relaciones con estudiantes (hijos)
 * - Gestión de suscripciones y contratos de transporte
 * - Información de contacto y dirección
 *
 * Relaciones principales:
 * - account: Cuenta de usuario asociada
 * - students: Hijos registrados en el sistema
 * - subscriptions: Suscripciones de transporte de los hijos
 */
class ParentProfile extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $primaryKey = 'parent_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'account_id',
        'address',
    ];

    protected $casts = [
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
        return $this->hasMany(Student::class, 'parent_id', 'parent_id');
    }

    /**
     * Relación con las suscripciones a través de los estudiantes y sus contratos
     */
    public function subscriptions()
    {
        $studentIds = $this->students()->pluck('student_id');
        $contractIds = StudentTransportContract::whereIn('student_id', $studentIds)->pluck('contract_id');
        return Subscription::whereIn('contract_id', $contractIds);
    }
}
