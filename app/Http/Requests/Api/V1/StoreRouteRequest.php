<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreRouteRequest extends FormRequest
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
            'provider_id' => ['required', 'integer', 'exists:providers,provider_id'],
            'route_name' => ['required', 'string', 'max:120'],
            'origin_address' => ['required', 'string', 'max:255'],
            'destination_address' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'active_flag' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'provider_id.exists' => 'El proveedor especificado no existe.',
            'capacity.min' => 'La capacidad debe ser al menos 1.',
            'monthly_price.min' => 'El precio mensual no puede ser negativo.',
        ];
    }
}
