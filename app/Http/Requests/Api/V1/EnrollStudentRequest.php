<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class EnrollStudentRequest extends FormRequest
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
            'route_id' => ['required', 'integer', 'exists:routes,route_id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'months_agreed' => ['required', 'integer', 'min:1', 'max:12'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'route_id.exists' => 'La ruta especificada no existe.',
            'start_date.after_or_equal' => 'La fecha de inicio debe ser hoy o una fecha futura.',
            'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            'months_agreed.min' => 'Los meses acordados deben ser al menos 1.',
            'months_agreed.max' => 'Los meses acordados no pueden ser más de 12.',
        ];
    }
}
