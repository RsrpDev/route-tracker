@extends('layouts.app')

@section('title', 'Agregar Vehículo - Conductor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Agregar Nuevo Vehículo</h2>
                <p class="text-gray-600">Registra un nuevo vehículo</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('driver.vehicles') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Vehículos
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('driver.vehicles.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Información Básica -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Información Básica</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="plate" class="block text-sm font-medium text-gray-700">Placa <span class="text-red-500">*</span></label>
                    <input type="text" name="plate" id="plate" value="{{ old('plate') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('plate') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700">Marca <span class="text-red-500">*</span></label>
                    <input type="text" name="brand" id="brand" value="{{ old('brand') }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('brand') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="model_year" class="block text-sm font-medium text-gray-700">Año del Modelo <span class="text-red-500">*</span></label>
                    <input type="number" name="model_year" id="model_year" value="{{ old('model_year') }}"
                           min="1900" max="{{ date('Y') + 1 }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('model_year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                    <input type="text" name="color" id="color" value="{{ old('color') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('color') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="fuel_type" class="block text-sm font-medium text-gray-700">Tipo de Combustible <span class="text-red-500">*</span></label>
                    <select name="fuel_type" id="fuel_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Seleccione un tipo</option>
                        <option value="Gasolina" {{ old('fuel_type') == 'Gasolina' ? 'selected' : '' }}>Gasolina</option>
                        <option value="Diésel" {{ old('fuel_type') == 'Diésel' ? 'selected' : '' }}>Diésel</option>
                        <option value="Gas Natural" {{ old('fuel_type') == 'Gas Natural' ? 'selected' : '' }}>Gas Natural</option>
                        <option value="Eléctrico" {{ old('fuel_type') == 'Eléctrico' ? 'selected' : '' }}>Eléctrico</option>
                        <option value="Híbrido" {{ old('fuel_type') == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                    </select>
                    @error('fuel_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="vehicle_class" class="block text-sm font-medium text-gray-700">Clase de Vehículo <span class="text-red-500">*</span></label>
                    <select name="vehicle_class" id="vehicle_class" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Seleccione una clase</option>
                        <option value="Automóvil" {{ old('vehicle_class') == 'Automóvil' ? 'selected' : '' }}>Automóvil</option>
                        <option value="Camioneta" {{ old('vehicle_class') == 'Camioneta' ? 'selected' : '' }}>Camioneta</option>
                        <option value="Bus" {{ old('vehicle_class') == 'Bus' ? 'selected' : '' }}>Bus</option>
                        <option value="Microbús" {{ old('vehicle_class') == 'Microbús' ? 'selected' : '' }}>Microbús</option>
                        <option value="Van" {{ old('vehicle_class') == 'Van' ? 'selected' : '' }}>Van</option>
                    </select>
                    @error('vehicle_class') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="capacity" class="block text-sm font-medium text-gray-700">Capacidad (pasajeros) <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity') }}"
                           min="1" max="50" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('capacity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="vehicle_status" class="block text-sm font-medium text-gray-700">Estado <span class="text-red-500">*</span></label>
                    <select name="vehicle_status" id="vehicle_status" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Seleccione un estado</option>
                        <option value="active" {{ old('vehicle_status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('vehicle_status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        <option value="maintenance" {{ old('vehicle_status') == 'maintenance' ? 'selected' : '' }}>En Mantenimiento</option>
                        <option value="retired" {{ old('vehicle_status') == 'retired' ? 'selected' : '' }}>Retirado</option>
                    </select>
                    @error('vehicle_status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Especificaciones Técnicas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Especificaciones Técnicas</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="serial_number" class="block text-sm font-medium text-gray-700">Número de Serie</label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('serial_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="engine_number" class="block text-sm font-medium text-gray-700">Número de Motor</label>
                    <input type="text" name="engine_number" id="engine_number" value="{{ old('engine_number') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('engine_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="chassis_number" class="block text-sm font-medium text-gray-700">Número de Chasis</label>
                    <input type="text" name="chassis_number" id="chassis_number" value="{{ old('chassis_number') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('chassis_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cylinder_capacity" class="block text-sm font-medium text-gray-700">Cilindrada (cc)</label>
                    <input type="number" name="cylinder_capacity" id="cylinder_capacity" value="{{ old('cylinder_capacity') }}"
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('cylinder_capacity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="service_type" class="block text-sm font-medium text-gray-700">Tipo de Servicio <span class="text-red-500">*</span></label>
                    <select name="service_type" id="service_type" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Seleccione un tipo</option>
                        <option value="Particular" {{ old('service_type') == 'Particular' ? 'selected' : '' }}>Particular</option>
                        <option value="Público" {{ old('service_type') == 'Público' ? 'selected' : '' }}>Público</option>
                        <option value="Escolar" {{ old('service_type') == 'Escolar' ? 'selected' : '' }}>Escolar</option>
                        <option value="Turismo" {{ old('service_type') == 'Turismo' ? 'selected' : '' }}>Turismo</option>
                    </select>
                    @error('service_type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="odometer_reading" class="block text-sm font-medium text-gray-700">Lectura del Odómetro (km)</label>
                    <input type="number" name="odometer_reading" id="odometer_reading" value="{{ old('odometer_reading') }}"
                           min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('odometer_reading') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Documentos y Seguros -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Documentos y Seguros</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="soat_number" class="block text-sm font-medium text-gray-700">Número SOAT</label>
                    <input type="text" name="soat_number" id="soat_number" value="{{ old('soat_number') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('soat_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="soat_expiration" class="block text-sm font-medium text-gray-700">Vencimiento SOAT</label>
                    <input type="date" name="soat_expiration" id="soat_expiration" value="{{ old('soat_expiration') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('soat_expiration') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="insurance_company" class="block text-sm font-medium text-gray-700">Compañía de Seguro</label>
                    <input type="text" name="insurance_company" id="insurance_company" value="{{ old('insurance_company') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('insurance_company') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="insurance_policy_number" class="block text-sm font-medium text-gray-700">Número de Póliza</label>
                    <input type="text" name="insurance_policy_number" id="insurance_policy_number" value="{{ old('insurance_policy_number') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('insurance_policy_number') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="insurance_expiration" class="block text-sm font-medium text-gray-700">Vencimiento Seguro</label>
                    <input type="date" name="insurance_expiration" id="insurance_expiration" value="{{ old('insurance_expiration') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('insurance_expiration') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="technical_inspection_expiration" class="block text-sm font-medium text-gray-700">Vencimiento Revisión Técnica</label>
                    <input type="date" name="technical_inspection_expiration" id="technical_inspection_expiration" value="{{ old('technical_inspection_expiration') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('technical_inspection_expiration') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="revision_expiration" class="block text-sm font-medium text-gray-700">Vencimiento Revisión</label>
                    <input type="date" name="revision_expiration" id="revision_expiration" value="{{ old('revision_expiration') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('revision_expiration') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Mantenimiento -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Mantenimiento</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="last_maintenance_date" class="block text-sm font-medium text-gray-700">Último Mantenimiento</label>
                    <input type="date" name="last_maintenance_date" id="last_maintenance_date" value="{{ old('last_maintenance_date') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('last_maintenance_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="next_maintenance_date" class="block text-sm font-medium text-gray-700">Próximo Mantenimiento</label>
                    <input type="date" name="next_maintenance_date" id="next_maintenance_date" value="{{ old('next_maintenance_date') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    @error('next_maintenance_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('driver.vehicles') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Crear Vehículo
            </button>
        </div>
    </form>
</div>
@endsection
