<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'account_type' => ['required', 'string', Rule::in(['parent', 'provider', 'school', 'admin'])],
            'full_name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:191', 'unique:accounts,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'id_number' => ['nullable', 'string', 'max:50', 'unique:accounts,id_number'],

            // Campos específicos para padres
            'address' => ['required_if:account_type,parent', 'string', 'max:255'],

            // Campos específicos para proveedores
            'provider_type' => ['required_if:account_type,provider', Rule::in(['driver', 'company', 'school_provider'])],
            'display_name' => ['required_if:account_type,provider', 'string', 'max:150'],
            'contact_email' => ['nullable', 'email', 'max:191'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
            'linked_school_id' => ['nullable', 'integer', 'exists:schools,school_id'],
            'default_commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],

            // Campos específicos para escuelas
            'legal_name' => ['required_if:account_type,school', 'string', 'max:150'],
            'rector_name' => ['nullable', 'string', 'max:120'],
            'nit' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'account_type.in' => 'El tipo de cuenta debe ser: parent, provider, school o admin.',
            'email.unique' => 'El email ya está registrado en el sistema.',
            'id_number.unique' => 'El número de identificación ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'address.required_if' => 'La dirección es requerida para cuentas de padre.',
            'provider_type.required_if' => 'El tipo de proveedor es requerido para cuentas de proveedor.',
            'display_name.required_if' => 'El nombre de display es requerido para cuentas de proveedor.',
            'legal_name.required_if' => 'El nombre legal es requerido para cuentas de escuela.',
        ];
    }
}
