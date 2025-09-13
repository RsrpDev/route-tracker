<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVehicleRequest extends FormRequest
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
            'plate' => ['required', 'string', 'max:20', 'unique:vehicles,plate'],
            'brand' => ['nullable', 'string', 'max:50'],
            'model_year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'capacity' => ['required', 'integer', 'min:1'],
            'soat_expiration' => ['required', 'date', 'after:today'],
            'insurance_expiration' => ['required', 'date', 'after:today'],
            'technical_inspection_expiration' => ['required', 'date', 'after:today'],
            'vehicle_status' => ['nullable', 'string', Rule::in(['active', 'inactive'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'provider_id.exists' => 'El proveedor especificado no existe.',
            'plate.unique' => 'La placa ya está registrada.',
            'model_year.min' => 'El año del modelo no puede ser anterior a 1900.',
            'model_year.max' => 'El año del modelo no puede ser posterior al año siguiente.',
            'capacity.min' => 'La capacidad debe ser al menos 1.',
            'soat_expiration.after' => 'El SOAT debe tener una fecha de expiración futura.',
            'insurance_expiration.after' => 'El seguro debe tener una fecha de expiración futura.',
            'technical_inspection_expiration.after' => 'La revisión técnica debe tener una fecha de expiración futura.',
        ];
    }
}
