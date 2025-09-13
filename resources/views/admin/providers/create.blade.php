{{--
    Archivo: resources/views/admin/providers/create.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/providers/create', [ProviderController::class, 'create'])
--}}

@extends('layouts.app')

@section('title', 'Nuevo Proveedor - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Nuevo Proveedor</h1>
                <p class="text-gray-600">Registrar un nuevo proveedor de transporte escolar</p>
            </div>
            <a href="{{ route('admin.providers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                ← Volver a Proveedores
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Información del Proveedor</h3>
        </div>
        <form action="{{ route('admin.providers.store') }}" method="POST" class="px-6 py-4">
            @csrf

            <!-- Información básica -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="display_name" class="block text-sm font-medium text-gray-700">Nombre de la Empresa/Proveedor *</label>
                    <input type="text" name="display_name" id="display_name" value="{{ old('display_name') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('display_name') border-red-300 @enderror">
                    @error('display_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="provider_type" class="block text-sm font-medium text-gray-700">Tipo de Proveedor *</label>
                    <select name="provider_type" id="provider_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('provider_type') border-red-300 @enderror">
                        <option value="">Seleccionar tipo</option>
                        <option value="company" {{ old('provider_type') == 'company' ? 'selected' : '' }}>Empresa</option>
                        <option value="driver" {{ old('provider_type') == 'driver' ? 'selected' : '' }}>Conductor Independiente</option>
                        <option value="school_provider" {{ old('provider_type') == 'school_provider' ? 'selected' : '' }}>Escuela/Colegio</option>
                    </select>
                    @error('provider_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Información de contacto -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700">Email de Contacto *</label>
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('contact_email') border-red-300 @enderror">
                    @error('contact_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700">Teléfono de Contacto</label>
                    <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('contact_phone') border-red-300 @enderror">
                    @error('contact_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Información legal -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="license_number" class="block text-sm font-medium text-gray-700">Número de Licencia</label>
                    <input type="text" name="license_number" id="license_number" value="{{ old('license_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('license_number') border-red-300 @enderror">
                    @error('license_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tax_id" class="block text-sm font-medium text-gray-700">NIT/RUT</label>
                    <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tax_id') border-red-300 @enderror">
                    @error('tax_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dirección -->
            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                <textarea name="address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('address') border-red-300 @enderror">{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Información adicional -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700">Sitio Web</label>
                    <input type="url" name="website" id="website" value="{{ old('website') }}" placeholder="https://ejemplo.com" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('website') border-red-300 @enderror">
                    @error('website')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="years_in_business" class="block text-sm font-medium text-gray-700">Años en el Negocio</label>
                    <input type="number" name="years_in_business" id="years_in_business" value="{{ old('years_in_business') }}" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('years_in_business') border-red-300 @enderror">
                    @error('years_in_business')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Campos específicos para conductores independientes -->
            <div id="driver-fields" class="border-t border-gray-200 pt-6 mb-6" style="display: none;">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Información del Conductor</h4>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                    <div>
                        <label for="driver_license_number" class="block text-sm font-medium text-gray-700">Número de Licencia</label>
                        <input type="text" name="driver_license_number" id="driver_license_number" value="{{ old('driver_license_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('driver_license_number') border-red-300 @enderror">
                        @error('driver_license_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="driver_license_category" class="block text-sm font-medium text-gray-700">Categoría de Licencia</label>
                        <select name="driver_license_category" id="driver_license_category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('driver_license_category') border-red-300 @enderror">
                            <option value="">Seleccionar categoría</option>
                            <option value="A1" {{ old('driver_license_category') == 'A1' ? 'selected' : '' }}>A1 - Motocicletas</option>
                            <option value="A2" {{ old('driver_license_category') == 'A2' ? 'selected' : '' }}>A2 - Motocicletas</option>
                            <option value="B1" {{ old('driver_license_category') == 'B1' ? 'selected' : '' }}>B1 - Automóviles</option>
                            <option value="B2" {{ old('driver_license_category') == 'B2' ? 'selected' : '' }}>B2 - Automóviles</option>
                            <option value="C1" {{ old('driver_license_category') == 'C1' ? 'selected' : '' }}>C1 - Camiones</option>
                            <option value="C2" {{ old('driver_license_category') == 'C2' ? 'selected' : '' }}>C2 - Camiones</option>
                        </select>
                        @error('driver_license_category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                    <div>
                        <label for="driver_license_expiration" class="block text-sm font-medium text-gray-700">Vencimiento de Licencia</label>
                        <input type="date" name="driver_license_expiration" id="driver_license_expiration" value="{{ old('driver_license_expiration') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('driver_license_expiration') border-red-300 @enderror">
                        @error('driver_license_expiration')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="driver_years_experience" class="block text-sm font-medium text-gray-700">Años de Experiencia</label>
                        <input type="number" name="driver_years_experience" id="driver_years_experience" value="{{ old('driver_years_experience') }}" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('driver_years_experience') border-red-300 @enderror">
                        @error('driver_years_experience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label for="driver_status" class="block text-sm font-medium text-gray-700">Estado del Conductor</label>
                    <select name="driver_status" id="driver_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('driver_status') border-red-300 @enderror">
                        <option value="pending" {{ old('driver_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="approved" {{ old('driver_status') == 'approved' ? 'selected' : '' }}>Aprobado</option>
                        <option value="rejected" {{ old('driver_status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                    @error('driver_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Información de la cuenta -->
            <div class="border-t border-gray-200 pt-6 mb-6">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Información de la Cuenta</h4>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Nombre Completo del Representante *</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('full_name') border-red-300 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700">Documento de Identidad</label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('id_number') border-red-300 @enderror">
                        @error('id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Contraseña *</label>
                        <input type="password" name="password" id="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-300 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.providers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Crear Proveedor
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const providerTypeSelect = document.getElementById('provider_type');
    const driverFields = document.getElementById('driver-fields');

    function toggleDriverFields() {
        if (providerTypeSelect.value === 'driver') {
            driverFields.style.display = 'block';
        } else {
            driverFields.style.display = 'none';
        }
    }

    providerTypeSelect.addEventListener('change', toggleDriverFields);

    // Ejecutar al cargar la página
    toggleDriverFields();
});
</script>
@endsection
