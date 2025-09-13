<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
            'parent_id' => ['required', 'integer', 'exists:parents,parent_id'],
            'given_name' => ['required', 'string', 'max:80'],
            'family_name' => ['required', 'string', 'max:80'],
            'identity_number' => ['required', 'string', 'max:50', 'unique:students,identity_number'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'school_id' => ['nullable', 'integer', 'exists:schools,school_id'],
            'grade' => ['nullable', 'string', 'max:50'],
            'shift' => ['nullable', 'string', Rule::in(['morning', 'afternoon', 'mixed'])],
            'address' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:30'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'parent_id.exists' => 'El padre especificado no existe.',
            'identity_number.unique' => 'El número de identidad ya está registrado.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'school_id.exists' => 'La escuela especificada no existe.',
            'shift.in' => 'El turno debe ser: morning, afternoon o mixed.',
        ];
    }
}
