<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRouteRequest extends FormRequest
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
            'route_name' => ['sometimes', 'string', 'max:120'],
            'origin_address' => ['sometimes', 'string', 'max:255'],
            'destination_address' => ['sometimes', 'string', 'max:255'],
            'capacity' => ['sometimes', 'integer', 'min:1'],
            'monthly_price' => ['sometimes', 'numeric', 'min:0'],
            'active_flag' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'capacity.min' => 'La capacidad debe ser al menos 1.',
            'monthly_price.min' => 'El precio mensual no puede ser negativo.',
        ];
    }
}
