<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * Modelo Subscription - Suscripciones de pago
 *
 * Este modelo representa la tabla 'subscriptions' que contiene todas las suscripciones
 * de pago asociadas a contratos de transporte. Las suscripciones manejan la facturación
 * recurrente y los diferentes planes de pago disponibles.
 *
 * Funcionalidades principales:
 * - Gestión de suscripciones de pago
 * - Relaciones con contratos de transporte y pagos
 * - Gestión de planes de pago y descuentos
 * - Integración con Stripe para pagos
 * - Cálculo de precios con descuentos
 * - Gestión de renovación automática
 *
 * Tipos de plan de pago:
 * - monthly: Pago mensual recurrente
 * - quarterly: Pago trimestral con descuento
 * - annual: Pago anual con mayor descuento
 * - postpaid: Pago posterior al uso
 *
 * Estados de la suscripción:
 * - active: Activa
 * - inactive: Inactiva
 * - pending: Pendiente
 * - expired: Expirada
 * - cancelled: Cancelada
 *
 * Relaciones principales:
 * - transportContract: Contrato de transporte asociado
 * - payments: Pagos realizados
 * - pickupRoute: Ruta de recogida
 * - dropoffRoute: Ruta de entrega
 * - studentThroughContract: Estudiante a través del contrato
 */
class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
    protected $primaryKey = 'subscription_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'contract_id',
        'billing_cycle',
        'price_snapshot',
        'platform_fee_rate',
        'next_billing_date',
        'subscription_status',
        // Nuevos campos para planes de pago
        'payment_plan_type',
        'payment_plan_name',
        'payment_plan_description',
        'discount_rate',
        'auto_renewal',
        'plan_start_date',
        'plan_end_date',
        // Campos para Stripe
        'stripe_subscription_id',
        'stripe_customer_id',
        'stripe_price_id',
        'payment_method',
        'payment_metadata',
        'is_active',
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
        'platform_fee_rate' => 'decimal:2',
        'discount_rate' => 'decimal:2',
        'next_billing_date' => 'date',
        'plan_start_date' => 'date',
        'plan_end_date' => 'date',
        'auto_renewal' => 'boolean',
        'is_active' => 'boolean',
        'payment_metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con la matrícula (DEPRECATED - usar transportContract)
     * @deprecated Usar transportContract() en su lugar
     */
    public function enrollment(): BelongsTo
    {
        return $this->transportContract();
    }

    /**
     * Relación con el contrato de transporte
     */
    public function transportContract(): BelongsTo
    {
        return $this->belongsTo(StudentTransportContract::class, 'contract_id', 'contract_id');
    }

    /**
     * Relación con la ruta a través de la matrícula (DEPRECATED)
     * @deprecated Usar pickupRoute() o dropoffRoute() a través de transportContract
     */
    public function route()
    {
        return $this->pickupRoute();
    }

    /**
     * Relación con el estudiante a través de la matrícula (DEPRECATED)
     * @deprecated Usar studentThroughContract() a través de transportContract
     */
    public function student()
    {
        return $this->studentThroughContract();
    }

    /**
     * Relación con la ruta de recogida a través del contrato de transporte
     */
    public function pickupRoute()
    {
        return $this->hasOneThrough(Route::class, StudentTransportContract::class, 'contract_id', 'route_id', 'contract_id', 'pickup_route_id');
    }

    /**
     * Relación con la ruta de entrega a través del contrato de transporte
     */
    public function dropoffRoute()
    {
        return $this->hasOneThrough(Route::class, StudentTransportContract::class, 'contract_id', 'route_id', 'contract_id', 'dropoff_route_id');
    }

    /**
     * Relación con el estudiante a través del contrato de transporte
     */
    public function studentThroughContract()
    {
        return $this->hasOneThrough(Student::class, StudentTransportContract::class, 'contract_id', 'student_id', 'contract_id', 'student_id');
    }

    /**
     * Relación con los pagos
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'subscription_id', 'subscription_id');
    }

    /**
     * Scope para suscripciones activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('subscription_status', 'active');
    }

    /**
     * Scope para suscripciones por tipo de plan
     */
    public function scopeByPlanType($query, $planType)
    {
        return $query->where('payment_plan_type', $planType);
    }

    /**
     * Scope para suscripciones con renovación automática
     */
    public function scopeAutoRenewal($query)
    {
        return $query->where('auto_renewal', true);
    }

    /**
     * Obtener el precio con descuento aplicado
     */
    public function getDiscountedPriceAttribute(): float
    {
        $discount = $this->price_snapshot * ($this->discount_rate / 100);
        return $this->price_snapshot - $discount;
    }

    /**
     * Obtener el ahorro por descuento
     */
    public function getSavingsAttribute(): float
    {
        return $this->price_snapshot * ($this->discount_rate / 100);
    }

    /**
     * Verificar si la suscripción está activa
     */
    public function isActive(): bool
    {
        return $this->is_active && $this->subscription_status === 'active';
    }

    /**
     * Verificar si tiene renovación automática
     */
    public function hasAutoRenewal(): bool
    {
        return $this->auto_renewal;
    }

    /**
     * Obtener el nombre del plan de pago
     */
    public function getPlanNameAttribute(): string
    {
        return $this->payment_plan_name ?: ucfirst($this->payment_plan_type);
    }

    /**
     * Obtener la descripción del plan
     */
    public function getPlanDescriptionAttribute(): string
    {
        if ($this->payment_plan_description) {
            return $this->payment_plan_description;
        }

        $descriptions = [
            'monthly' => 'Pago mensual recurrente',
            'quarterly' => 'Pago trimestral con descuento',
            'annual' => 'Pago anual con mayor descuento',
            'postpaid' => 'Pago posterior al uso (como plan de datos)'
        ];

        return $descriptions[$this->payment_plan_type] ?? 'Plan de pago personalizado';
    }

    /**
     * Calcular el próximo ciclo de facturación
     */
    public function getNextBillingCycleAttribute(): ?\Carbon\Carbon
    {
        if (!$this->next_billing_date) {
            return null;
        }

        $cycles = [
            'monthly' => 1,
            'quarterly' => 3,
            'annual' => 12,
            'postpaid' => 1
        ];

        $months = $cycles[$this->payment_plan_type] ?? 1;
        return $this->next_billing_date->addMonths($months);
    }

    /**
     * Obtener el método de pago legible
     */
    public function getPaymentMethodTextAttribute(): string
    {
        $methods = [
            'stripe' => 'Tarjeta de Crédito/Débito',
            'pse' => 'PSE (Pagos Seguros en Línea)',
            'bank_transfer' => 'Transferencia Bancaria',
            'cash' => 'Efectivo'
        ];

        return $methods[$this->payment_method] ?? ucfirst($this->payment_method);
    }
}
