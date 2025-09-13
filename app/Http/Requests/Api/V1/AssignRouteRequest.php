<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class AssignRouteRequest extends FormRequest
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
            'driver_id' => ['required', 'integer', 'exists:drivers,driver_id'],
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,vehicle_id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'driver_id.exists' => 'El conductor especificado no existe.',
            'vehicle_id.exists' => 'El vehículo especificado no existe.',
            'start_date.after_or_equal' => 'La fecha de inicio debe ser hoy o una fecha futura.',
            'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
        ];
    }
}
