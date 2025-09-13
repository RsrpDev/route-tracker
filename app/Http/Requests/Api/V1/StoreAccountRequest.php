<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountRequest extends FormRequest
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
            'password_hash' => ['required', 'string', 'min:8'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'id_number' => ['nullable', 'string', 'max:50', 'unique:accounts,id_number'],
            'account_status' => ['nullable', 'string', Rule::in(['active', 'inactive', 'pending', 'blocked'])],
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
            'password_hash.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];
    }
}
