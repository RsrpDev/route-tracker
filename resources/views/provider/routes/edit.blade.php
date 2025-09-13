{{--
    Archivo: resources/views/provider/routes/edit.blade.php
    Roles: provider
    Rutas necesarias: Route::resource('provider.routes', ProviderRouteController::class)
--}}

@extends('layouts.app')

@section('title', 'Editar Ruta')

@section('breadcrumbs')
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Inicio
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('provider.dashboard.by.type') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Dashboard del Proveedor
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('provider.routes.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Mis Rutas
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Editar {{ $route->name }}</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Editar Ruta</h1>
            <p class="mt-2 text-sm text-gray-600">Modifica la información de la ruta "{{ $route->name }}"</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white shadow rounded-lg">
            <form method="POST" action="{{ route('provider.routes.update', $route) }}" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Información básica de la ruta -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Ruta</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-form-input
                            name="name"
                            label="Nombre de la Ruta"
                            value="{{ old('name', $route->name) }}"
                            required
                            placeholder="Ruta Norte - Centro"
                        />

                        <x-form-input
                            name="description"
                            label="Descripción"
                            value="{{ old('description', $route->description) }}"
                            placeholder="Ruta que cubre el sector norte hasta el centro de la ciudad"
                        />

                        <x-form-input
                            name="capacity"
                            label="Capacidad Máxima"
                            type="number"
                            value="{{ old('capacity', $route->capacity) }}"
                            required
                            placeholder="25"
                        />

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado
                            </label>
                            <select
                                name="status"
                                id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-300 @enderror"
                            >
                                <option value="active" {{ old('status', $route->status) == 'active' ? 'selected' : '' }}>Activa</option>
                                <option value="inactive" {{ old('status', $route->status) == 'inactive' ? 'selected' : '' }}>Inactiva</option>
                                <option value="maintenance" {{ old('status', $route->status) == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Horarios -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Horarios de Operación</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-form-input
                            name="pickup_time"
                            label="Hora de Recolección"
                            type="time"
                            value="{{ old('pickup_time', $route->pickup_time) }}"
                            required
                        />

                        <x-form-input
                            name="return_time"
                            label="Hora de Retorno"
                            type="time"
                            value="{{ old('return_time', $route->return_time) }}"
                            required
                        />

                        <x-form-input
                            name="estimated_duration"
                            label="Duración Estimada (minutos)"
                            type="number"
                            value="{{ old('estimated_duration', $route->estimated_duration) }}"
                            placeholder="45"
                        />

                        <div>
                            <label for="operating_days" class="block text-sm font-medium text-gray-700 mb-2">
                                Días de Operación
                            </label>
                            <div class="space-y-2">
                                @php
                                    $days = [
                                        'monday' => 'Lunes',
                                        'tuesday' => 'Martes',
                                        'wednesday' => 'Miércoles',
                                        'thursday' => 'Jueves',
                                        'friday' => 'Viernes',
                                        'saturday' => 'Sábado',
                                        'sunday' => 'Domingo'
                                    ];
                                    $currentDays = is_array($route->operating_days) ? $route->operating_days : [];
                                @endphp
                                @foreach($days as $key => $day)
                                    <label class="flex items-center">
                                        <input
                                            type="checkbox"
                                            name="operating_days[]"
                                            value="{{ $key }}"
                                            {{ in_array($key, old('operating_days', $currentDays)) ? 'checked' : '' }}
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                        >
                                        <span class="ml-2 text-sm text-gray-700">{{ $day }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('operating_days')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Zonas y paradas -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Zonas y Paradas</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="pickup_zones" class="block text-sm font-medium text-gray-700 mb-2">
                                Zonas de Recolección
                            </label>
                            <textarea
                                name="pickup_zones"
                                id="pickup_zones"
                                rows="3"
                                placeholder="Sector Norte, Barrio Los Pinos, Urbanización El Bosque"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('pickup_zones') border-red-300 @enderror"
                            >{{ old('pickup_zones', $route->pickup_zones) }}</textarea>
                            @error('pickup_zones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="dropoff_zones" class="block text-sm font-medium text-gray-700 mb-2">
                                Zonas de Destino
                            </label>
                            <textarea
                                name="dropoff_zones"
                                id="dropoff_zones"
                                rows="3"
                                placeholder="Centro de la ciudad, Zona Universitaria, Sector Comercial"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('dropoff_zones') border-red-300 @enderror"
                            >{{ old('dropoff_zones', $route->dropoff_zones) }}</textarea>
                            @error('dropoff_zones')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Precios -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Precios</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <x-form-input
                            name="monthly_fee"
                            label="Tarifa Mensual"
                            type="number"
                            step="0.01"
                            value="{{ old('monthly_fee', $route->monthly_fee) }}"
                            required
                            placeholder="150000"
                        />

                        <x-form-input
                            name="single_trip_fee"
                            label="Tarifa por Viaje"
                            type="number"
                            step="0.01"
                            value="{{ old('single_trip_fee', $route->single_trip_fee) }}"
                            placeholder="5000"
                        />

                        <div>
                            <label for="payment_frequency" class="block text-sm font-medium text-gray-700 mb-2">
                                Frecuencia de Pago
                            </label>
                            <select
                                name="payment_frequency"
                                id="payment_frequency"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('payment_frequency') border-red-300 @enderror"
                            >
                                <option value="monthly" {{ old('payment_frequency', $route->payment_frequency) == 'monthly' ? 'selected' : '' }}>Mensual</option>
                                <option value="quarterly" {{ old('payment_frequency', $route->payment_frequency) == 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                                <option value="semester" {{ old('payment_frequency', $route->payment_frequency) == 'semester' ? 'selected' : '' }}>Semestral</option>
                                <option value="annual" {{ old('payment_frequency', $route->payment_frequency) == 'annual' ? 'selected' : '' }}>Anual</option>
                            </select>
                            @error('payment_frequency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                                Moneda
                            </label>
                            <select
                                name="currency"
                                id="currency"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('currency') border-red-300 @enderror"
                            >
                                <option value="COP" {{ old('currency', $route->currency) == 'COP' ? 'selected' : '' }}>Peso Colombiano (COP)</option>
                                <option value="USD" {{ old('currency', $route->currency) == 'USD' ? 'selected' : '' }}>Dólar Estadounidense (USD)</option>
                                <option value="EUR" {{ old('currency', $route->currency) == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                            </select>
                            @error('currency')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notas adicionales -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información Adicional</h3>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notas y Observaciones
                        </label>
                        <textarea
                            name="notes"
                            id="notes"
                            rows="4"
                            placeholder="Información adicional sobre la ruta, requisitos especiales, etc."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('notes') border-red-300 @enderror"
                        >{{ old('notes', $route->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('provider.routes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>

                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Ruta
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
