<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAccountRequest extends FormRequest
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
        $accountId = $this->route('account');

        return [
            'account_type' => ['sometimes', 'string', Rule::in(['parent', 'provider', 'school', 'admin'])],
            'full_name' => ['sometimes', 'string', 'max:150'],
            'email' => ['sometimes', 'email', 'max:191', Rule::unique('accounts', 'email')->ignore($accountId, 'account_id')],
            'password_hash' => ['sometimes', 'string', 'min:8'],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'id_number' => ['nullable', 'string', 'max:50', Rule::unique('accounts', 'id_number')->ignore($accountId, 'account_id')],
            'account_status' => ['sometimes', 'string', Rule::in(['active', 'inactive', 'pending', 'blocked'])],
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
