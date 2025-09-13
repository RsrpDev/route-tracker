@extends('layouts.app')

@section('title', 'Registrar Conductor - Colegio')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Registrar Nuevo Conductor</h1>
                    <p class="text-gray-600">{{ $provider->display_name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('provider.school.drivers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Conductores
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Información del Conductor</h3>
            </div>

            <form method="POST" action="{{ route('provider.school.drivers.store') }}" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información Personal -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900">Información Personal</h4>

                        <div>
                            <label for="given_name" class="block text-sm font-medium text-gray-700">Nombres *</label>
                            <input type="text" name="given_name" id="given_name" value="{{ old('given_name') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('given_name') border-red-300 @enderror">
                            @error('given_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="family_name" class="block text-sm font-medium text-gray-700">Apellidos *</label>
                            <input type="text" name="family_name" id="family_name" value="{{ old('family_name') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('family_name') border-red-300 @enderror">
                            @error('family_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="id_number" class="block text-sm font-medium text-gray-700">Número de Cédula *</label>
                            <input type="text" name="id_number" id="id_number" value="{{ old('id_number') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('id_number') border-red-300 @enderror">
                            @error('id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700">Teléfono *</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('phone_number') border-red-300 @enderror">
                            @error('phone_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Información de Licencia -->
                    <div class="space-y-4">
                        <h4 class="text-md font-medium text-gray-900">Información de Licencia</h4>

                        <div>
                            <label for="license_number" class="block text-sm font-medium text-gray-700">Número de Licencia *</label>
                            <input type="text" name="license_number" id="license_number" value="{{ old('license_number') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('license_number') border-red-300 @enderror">
                            @error('license_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="license_category" class="block text-sm font-medium text-gray-700">Categoría de Licencia *</label>
                            <select name="license_category" id="license_category" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('license_category') border-red-300 @enderror">
                                <option value="">Seleccionar categoría</option>
                                <option value="A1" {{ old('license_category') == 'A1' ? 'selected' : '' }}>A1 - Motocicletas</option>
                                <option value="A2" {{ old('license_category') == 'A2' ? 'selected' : '' }}>A2 - Motocicletas</option>
                                <option value="B1" {{ old('license_category') == 'B1' ? 'selected' : '' }}>B1 - Automóviles</option>
                                <option value="B2" {{ old('license_category') == 'B2' ? 'selected' : '' }}>B2 - Automóviles</option>
                                <option value="C1" {{ old('license_category') == 'C1' ? 'selected' : '' }}>C1 - Camiones</option>
                                <option value="C2" {{ old('license_category') == 'C2' ? 'selected' : '' }}>C2 - Camiones</option>
                                <option value="C3" {{ old('license_category') == 'C3' ? 'selected' : '' }}>C3 - Camiones</option>
                            </select>
                            @error('license_category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="license_expiration" class="block text-sm font-medium text-gray-700">Fecha de Vencimiento *</label>
                            <input type="date" name="license_expiration" id="license_expiration" value="{{ old('license_expiration') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('license_expiration') border-red-300 @enderror">
                            @error('license_expiration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="years_experience" class="block text-sm font-medium text-gray-700">Años de Experiencia *</label>
                            <input type="number" name="years_experience" id="years_experience" value="{{ old('years_experience') }}" min="0" max="50" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('years_experience') border-red-300 @enderror">
                            @error('years_experience')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de Cuenta -->
                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Información de Cuenta</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Correo Electrónico *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña *</label>
                            <input type="password" name="password" id="password" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Asignación de Vehículo -->
                @if($availableVehicles->count() > 0)
                <div class="mt-8">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Asignación de Vehículo (Opcional)</h4>

                    <div>
                        <label for="vehicle_id" class="block text-sm font-medium text-gray-700">Vehículo Disponible</label>
                        <select name="vehicle_id" id="vehicle_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sin asignar vehículo</option>
                            @foreach($availableVehicles as $vehicle)
                                <option value="{{ $vehicle->vehicle_id }}" {{ old('vehicle_id') == $vehicle->vehicle_id ? 'selected' : '' }}>
                                    {{ $vehicle->make }} {{ $vehicle->model }} - {{ $vehicle->license_plate }}
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endif

                <!-- Botones -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('provider.school.drivers.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Registrar Conductor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection




