{{--
    Archivo: resources/views/admin/schools/create.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/schools/create', [SchoolController::class, 'create'])
--}}

@extends('layouts.app')

@section('title', 'Nueva Escuela - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Nueva Escuela</h1>
                <p class="text-gray-600">Registrar una nueva institución educativa</p>
            </div>
            <a href="{{ route('schools.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                ← Volver a Escuelas
            </a>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Información de la Escuela</h3>
        </div>
        <form action="{{ route('schools.store') }}" method="POST" class="px-6 py-4">
            @csrf

            <!-- Información básica -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="school_name" class="block text-sm font-medium text-gray-700">Nombre de la Institución *</label>
                    <input type="text" name="school_name" id="school_name" value="{{ old('school_name') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('school_name') border-red-300 @enderror">
                    @error('school_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="dane_code" class="block text-sm font-medium text-gray-700">Código DANE</label>
                    <input type="text" name="dane_code" id="dane_code" value="{{ old('dane_code') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('dane_code') border-red-300 @enderror">
                    @error('dane_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Información de contacto -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email de Contacto *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('phone_number') border-red-300 @enderror">
                    @error('phone_number')
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

            <!-- Información académica -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                <div>
                    <label for="grade_levels" class="block text-sm font-medium text-gray-700">Niveles Educativos</label>
                    <input type="text" name="grade_levels" id="grade_levels" value="{{ old('grade_levels') }}" placeholder="Ej: Preescolar, Primaria, Secundaria" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('grade_levels') border-red-300 @enderror">
                    @error('grade_levels')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_students" class="block text-sm font-medium text-gray-700">Total de Estudiantes</label>
                    <input type="number" name="total_students" id="total_students" value="{{ old('total_students') }}" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('total_students') border-red-300 @enderror">
                    @error('total_students')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Servicio de transporte -->
            <div class="mb-6">
                <fieldset>
                    <legend class="text-sm font-medium text-gray-700">Servicio de Transporte</legend>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input id="has_transport_service_yes" name="has_transport_service" type="radio" value="1" {{ old('has_transport_service') == '1' ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                            <label for="has_transport_service_yes" class="ml-2 text-sm text-gray-700">Sí, ofrece servicio de transporte</label>
                        </div>
                        <div class="flex items-center">
                            <input id="has_transport_service_no" name="has_transport_service" type="radio" value="0" {{ old('has_transport_service') == '0' ? 'checked' : '' }} class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                            <label for="has_transport_service_no" class="ml-2 text-sm text-gray-700">No, no ofrece servicio de transporte</label>
                        </div>
                    </div>
                    @error('has_transport_service')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </fieldset>
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
                <a href="{{ route('schools.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Crear Escuela
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
