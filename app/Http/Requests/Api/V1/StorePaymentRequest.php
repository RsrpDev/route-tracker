<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
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
            'subscription_id' => ['required', 'integer', 'exists:subscriptions,subscription_id'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after:period_start'],
            'amount_total' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['required', 'string', Rule::in(['card', 'pse', 'nequi', 'daviplata'])],
            'payment_status' => ['nullable', 'string', Rule::in(['pending', 'paid', 'failed', 'refunded'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'subscription_id.exists' => 'La suscripción especificada no existe.',
            'period_end.after' => 'La fecha de fin del período debe ser posterior a la fecha de inicio.',
            'amount_total.min' => 'El monto total no puede ser negativo.',
            'payment_method.in' => 'El método de pago debe ser: card, pse, nequi o daviplata.',
        ];
    }
}
