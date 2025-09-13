<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProviderRequest extends FormRequest
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
            'provider_type' => ['sometimes', 'string', Rule::in(['driver', 'company', 'school_provider'])],
            'display_name' => ['sometimes', 'string', 'max:150'],
            'contact_email' => ['nullable', 'email', 'max:191'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'linked_school_id' => ['nullable', 'integer', 'exists:schools,school_id'],
            'default_commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'provider_status' => ['sometimes', 'string', Rule::in(['active', 'inactive', 'pending', 'blocked'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'linked_school_id.exists' => 'La escuela especificada no existe.',
            'provider_type.in' => 'El tipo de proveedor debe ser: driver, company o school_provider.',
            'default_commission_rate.min' => 'La comisión no puede ser menor a 0%.',
            'default_commission_rate.max' => 'La comisión no puede ser mayor a 100%.',
        ];
    }
}
