<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDriverRequest extends FormRequest
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
        $driverId = $this->route('driver');

        return [
            'given_name' => ['sometimes', 'string', 'max:80'],
            'family_name' => ['sometimes', 'string', 'max:80'],
            'id_number' => ['sometimes', 'string', 'max:50', Rule::unique('drivers', 'id_number')->ignore($driverId, 'driver_id')],
            'phone_number' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
            'license_number' => ['sometimes', 'string', 'max:50'],
            'license_category' => ['nullable', 'string', 'max:10'],
            'license_expiration' => ['sometimes', 'date', 'after:today'],
            'years_experience' => ['nullable', 'integer', 'min:0'],
            'driver_status' => ['sometimes', 'string', Rule::in(['pending', 'approved', 'rejected'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'id_number.unique' => 'El número de identificación ya está registrado.',
            'license_expiration.after' => 'La licencia debe tener una fecha de expiración futura.',
            'years_experience.min' => 'Los años de experiencia no pueden ser negativos.',
        ];
    }
}
