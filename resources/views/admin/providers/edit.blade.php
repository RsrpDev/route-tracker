{{--
    Archivo: resources/views/admin/providers/edit.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/providers/{provider}/edit', [ProviderController::class, 'edit'])
--}}

@extends('layouts.app')

@section('title', 'Editar Proveedor - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Editar Proveedor</h1>
                <p class="text-gray-600">Modificar información de {{ $provider->display_name }}</p>
            </div>
            <a href="{{ route('admin.providers.show', $provider) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                ← Volver a Detalles
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Información del Proveedor</h3>
        </div>
        <form action="{{ route('admin.providers.update', $provider) }}" method="POST" class="px-6 py-4">
            @csrf
            @method('PUT')

            <!-- Información básica -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="display_name" class="block text-sm font-medium text-gray-700">Nombre de la Empresa/Proveedor *</label>
                    <input type="text" name="display_name" id="display_name" value="{{ old('display_name', $provider->display_name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('display_name') border-red-300 @enderror">
                    @error('display_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="provider_type" class="block text-sm font-medium text-gray-700">Tipo de Proveedor *</label>
                    <select name="provider_type" id="provider_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('provider_type') border-red-300 @enderror">
                        <option value="">Seleccionar tipo</option>
                        <option value="company" {{ old('provider_type', $provider->provider_type) == 'company' ? 'selected' : '' }}>Empresa</option>
                        <option value="individual" {{ old('provider_type', $provider->provider_type) == 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="school" {{ old('provider_type', $provider->provider_type) == 'school' ? 'selected' : '' }}>Escuela/Colegio</option>
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
                    <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $provider->contact_email) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('contact_email') border-red-300 @enderror">
                    @error('contact_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700">Teléfono de Contacto</label>
                    <input type="tel" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $provider->contact_phone) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('contact_phone') border-red-300 @enderror">
                    @error('contact_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Información legal -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="license_number" class="block text-sm font-medium text-gray-700">Número de Licencia</label>
                    <input type="text" name="license_number" id="license_number" value="{{ old('license_number', $provider->license_number) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('license_number') border-red-300 @enderror">
                    @error('license_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tax_id" class="block text-sm font-medium text-gray-700">NIT/RUT</label>
                    <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id', $provider->tax_id) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('tax_id') border-red-300 @enderror">
                    @error('tax_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dirección -->
            <div class="mb-6">
                <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                <textarea name="address" id="address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('address') border-red-300 @enderror">{{ old('address', $provider->address) }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Información adicional -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="website" class="block text-sm font-medium text-gray-700">Sitio Web</label>
                    <input type="url" name="website" id="website" value="{{ old('website', $provider->website) }}" placeholder="https://ejemplo.com" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('website') border-red-300 @enderror">
                    @error('website')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="years_in_business" class="block text-sm font-medium text-gray-700">Años en el Negocio</label>
                    <input type="number" name="years_in_business" id="years_in_business" value="{{ old('years_in_business', $provider->years_in_business) }}" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('years_in_business') border-red-300 @enderror">
                    @error('years_in_business')
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
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $provider->account->full_name) }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('full_name') border-red-300 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700">Documento de Identidad</label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $provider->account->id_number) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('id_number') border-red-300 @enderror">
                        @error('id_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('password') border-red-300 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Dejar en blanco para mantener la contraseña actual</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.providers.show', $provider) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Actualizar Proveedor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
