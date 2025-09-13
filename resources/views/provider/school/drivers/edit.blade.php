@extends('layouts.app')

@section('title', 'Editar Conductor - Escuela')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">✏️ Editar Conductor</h1>
                <p class="mt-2 text-gray-600">Modifica la información del conductor: {{ $driver->given_name }} {{ $driver->family_name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('provider.school.drivers.show', $driver) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Ver Detalles
                </a>
                <a href="{{ route('provider.school.drivers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Lista
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario de Edición -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('provider.school.drivers.update', $driver) }}" method="POST" class="space-y-6 p-6">
            @csrf
            @method('PUT')

            <!-- Información Personal -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">👤 Información Personal</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="given_name" class="block text-sm font-medium text-gray-700">Nombres *</label>
                        <input type="text" name="given_name" id="given_name" value="{{ old('given_name', $driver->given_name) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('given_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="family_name" class="block text-sm font-medium text-gray-700">Apellidos *</label>
                        <input type="text" name="family_name" id="family_name" value="{{ old('family_name', $driver->family_name) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('family_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700">Número de Identificación *</label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $driver->id_number) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="document_type" class="block text-sm font-medium text-gray-700">Tipo de Documento *</label>
                        <select name="document_type" id="document_type" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="CC" {{ old('document_type', $driver->document_type) == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                            <option value="CE" {{ old('document_type', $driver->document_type) == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                            <option value="PA" {{ old('document_type', $driver->document_type) == 'PA' ? 'selected' : '' }}>Pasaporte</option>
                        </select>
                        @error('document_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento *</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $driver->birth_date?->format('Y-m-d')) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700">Tipo de Sangre</label>
                        <select name="blood_type" id="blood_type"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar...</option>
                            <option value="A+" {{ old('blood_type', $driver->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_type', $driver->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_type', $driver->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_type', $driver->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_type', $driver->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_type', $driver->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_type', $driver->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_type', $driver->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                        @error('blood_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">📞 Información de Contacto</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono *</label>
                        <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $driver->phone_number) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('phone_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Dirección *</label>
                        <input type="text" name="address" id="address" value="{{ old('address', $driver->address) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Contacto de Emergencia *</label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $driver->emergency_contact_name) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Teléfono de Emergencia *</label>
                        <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $driver->emergency_contact_phone) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700">Relación *</label>
                        <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $driver->emergency_contact_relationship) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('emergency_contact_relationship')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Información de Licencia -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">🚗 Información de Licencia</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="license_number" class="block text-sm font-medium text-gray-700">Número de Licencia *</label>
                        <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $driver->license_number) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('license_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="license_category" class="block text-sm font-medium text-gray-700">Categoría de Licencia *</label>
                        <select name="license_category" id="license_category" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="A1" {{ old('license_category', $driver->license_category) == 'A1' ? 'selected' : '' }}>A1 - Motocicletas</option>
                            <option value="A2" {{ old('license_category', $driver->license_category) == 'A2' ? 'selected' : '' }}>A2 - Motocicletas</option>
                            <option value="B1" {{ old('license_category', $driver->license_category) == 'B1' ? 'selected' : '' }}>B1 - Automóviles</option>
                            <option value="B2" {{ old('license_category', $driver->license_category) == 'B2' ? 'selected' : '' }}>B2 - Automóviles</option>
                            <option value="B3" {{ old('license_category', $driver->license_category) == 'B3' ? 'selected' : '' }}>B3 - Servicio Público</option>
                            <option value="C1" {{ old('license_category', $driver->license_category) == 'C1' ? 'selected' : '' }}>C1 - Camiones</option>
                            <option value="C2" {{ old('license_category', $driver->license_category) == 'C2' ? 'selected' : '' }}>C2 - Camiones</option>
                        </select>
                        @error('license_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="license_expiration" class="block text-sm font-medium text-gray-700">Fecha de Vencimiento *</label>
                        <input type="date" name="license_expiration" id="license_expiration" value="{{ old('license_expiration', $driver->license_expiration?->format('Y-m-d')) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('license_expiration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="license_issuing_authority" class="block text-sm font-medium text-gray-700">Autoridad Emisora *</label>
                        <input type="text" name="license_issuing_authority" id="license_issuing_authority" value="{{ old('license_issuing_authority', $driver->license_issuing_authority) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('license_issuing_authority')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="license_issuing_city" class="block text-sm font-medium text-gray-700">Ciudad de Emisión *</label>
                        <input type="text" name="license_issuing_city" id="license_issuing_city" value="{{ old('license_issuing_city', $driver->license_issuing_city) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('license_issuing_city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="license_issue_date" class="block text-sm font-medium text-gray-700">Fecha de Emisión *</label>
                        <input type="date" name="license_issue_date" id="license_issue_date" value="{{ old('license_issue_date', $driver->license_issue_date?->format('Y-m-d')) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('license_issue_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Certificados Médicos -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">🏥 Certificados Médicos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certificado Médico</label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="has_medical_certificate" value="1" {{ old('has_medical_certificate', $driver->has_medical_certificate) ? 'checked' : '' }}
                                       class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-sm text-gray-700">Sí</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="has_medical_certificate" value="0" {{ !old('has_medical_certificate', $driver->has_medical_certificate) ? 'checked' : '' }}
                                       class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-sm text-gray-700">No</span>
                            </label>
                        </div>
                        @error('has_medical_certificate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="medical_certificate_expiration" class="block text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
                        <input type="date" name="medical_certificate_expiration" id="medical_certificate_expiration" value="{{ old('medical_certificate_expiration', $driver->medical_certificate_expiration?->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('medical_certificate_expiration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Certificado Psicológico</label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="has_psychological_certificate" value="1" {{ old('has_psychological_certificate', $driver->has_psychological_certificate) ? 'checked' : '' }}
                                       class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-sm text-gray-700">Sí</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="has_psychological_certificate" value="0" {{ !old('has_psychological_certificate', $driver->has_psychological_certificate) ? 'checked' : '' }}
                                       class="form-radio h-4 w-4 text-blue-600">
                                <span class="ml-2 text-sm text-gray-700">No</span>
                            </label>
                        </div>
                        @error('has_psychological_certificate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="psychological_certificate_expiration" class="block text-sm font-medium text-gray-700">Fecha de Vencimiento</label>
                        <input type="date" name="psychological_certificate_expiration" id="psychological_certificate_expiration" value="{{ old('psychological_certificate_expiration', $driver->psychological_certificate_expiration?->format('Y-m-d')) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('psychological_certificate_expiration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Información Laboral -->
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">💼 Información Laboral</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="years_experience" class="block text-sm font-medium text-gray-700">Años de Experiencia *</label>
                        <input type="number" name="years_experience" id="years_experience" value="{{ old('years_experience', $driver->years_experience) }}" min="0" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('years_experience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="employment_status" class="block text-sm font-medium text-gray-700">Estado Laboral *</label>
                        <select name="employment_status" id="employment_status" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('employment_status', $driver->employment_status) == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('employment_status', $driver->employment_status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            <option value="suspended" {{ old('employment_status', $driver->employment_status) == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                        </select>
                        @error('employment_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hire_date" class="block text-sm font-medium text-gray-700">Fecha de Contratación *</label>
                        <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date', $driver->hire_date?->format('Y-m-d')) }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('hire_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="driver_status" class="block text-sm font-medium text-gray-700">Estado del Conductor *</label>
                        <select name="driver_status" id="driver_status" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="active" {{ old('driver_status', $driver->driver_status) == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('driver_status', $driver->driver_status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                            <option value="suspended" {{ old('driver_status', $driver->driver_status) == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                        </select>
                        @error('driver_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Tarifa por Hora</label>
                        <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $driver->hourly_rate) }}" step="0.01" min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('hourly_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="monthly_salary" class="block text-sm font-medium text-gray-700">Salario Mensual</label>
                        <input type="number" name="monthly_salary" id="monthly_salary" value="{{ old('monthly_salary', $driver->monthly_salary) }}" step="0.01" min="0"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('monthly_salary')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Asignación de Vehículo -->
            <div class="pb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">🚗 Asignación de Vehículo</h3>
                <div>
                    <label for="vehicle_id" class="block text-sm font-medium text-gray-700">Vehículo Asignado</label>
                    <select name="vehicle_id" id="vehicle_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sin vehículo asignado</option>
                        @foreach($availableVehicles as $vehicle)
                            <option value="{{ $vehicle->vehicle_id }}"
                                    {{ old('vehicle_id', $driver->vehicles->first()?->vehicle_id) == $vehicle->vehicle_id ? 'selected' : '' }}>
                                {{ $vehicle->brand }} {{ $vehicle->model }} - {{ $vehicle->plate }} ({{ $vehicle->capacity }} pasajeros)
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">Selecciona un vehículo disponible para asignar a este conductor.</p>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('provider.school.drivers.show', $driver) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizar Conductor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection




