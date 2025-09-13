<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'billing_cycle' => ['sometimes', 'string', Rule::in(['monthly', 'quarterly', 'semiannual', 'annual'])],
            'price_snapshot' => ['sometimes', 'numeric', 'min:0'],
            'platform_fee_rate' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'next_billing_date' => ['sometimes', 'date', 'after:today'],
            'subscription_status' => ['sometimes', 'string', Rule::in(['active', 'paused', 'cancelled', 'expired'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'billing_cycle.in' => 'El ciclo de facturación debe ser: monthly, quarterly, semiannual o annual.',
            'price_snapshot.min' => 'El precio no puede ser negativo.',
            'platform_fee_rate.min' => 'La tasa de comisión no puede ser menor a 0%.',
            'platform_fee_rate.max' => 'La tasa de comisión no puede ser mayor a 100%.',
            'next_billing_date.after' => 'La próxima fecha de facturación debe ser futura.',
        ];
    }
}
