<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Payment - Pagos del sistema
 *
 * Este modelo representa la tabla 'payments' que contiene todos los pagos
 * realizados en el sistema. Los pagos están asociados a suscripciones y
 * manejan la distribución de ingresos entre la plataforma y los proveedores.
 *
 * Funcionalidades principales:
 * - Gestión de pagos y transacciones
 * - Cálculo de comisiones de plataforma
 * - Distribución de ingresos a proveedores
 * - Gestión de períodos de facturación
 * - Seguimiento de estado de pagos
 *
 * Estados del pago:
 * - pending: Pendiente
 * - completed: Completado
 * - failed: Fallido
 * - refunded: Reembolsado
 * - cancelled: Cancelado
 *
 * Métodos de pago:
 * - stripe: Tarjeta de crédito/débito
 * - pse: PSE (Pagos Seguros en Línea)
 * - bank_transfer: Transferencia bancaria
 * - cash: Efectivo
 *
 * Relaciones principales:
 * - subscription: Suscripción asociada al pago
 */
class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'subscription_id',
        'period_start',
        'period_end',
        'amount_total',
        'platform_fee',
        'provider_amount',
        'payment_method',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'amount_total' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'provider_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con la suscripción
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'subscription_id');
    }
}
