@extends('layouts.app')

@section('title', 'Detalles del Conductor - Colegio')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $driver->given_name }} {{ $driver->family_name }}</h1>
                    <p class="text-gray-600">{{ $provider->display_name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('provider.school.drivers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Conductores
                    </a>
                    <a href="{{ route('provider.school.drivers.edit', $driver) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Conductor
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información Personal -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Información Personal</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Nombres Completos</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->given_name }} {{ $driver->family_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Número de Cédula</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->id_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Teléfono</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->phone_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Correo Electrónico</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->account->email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Años de Experiencia</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->years_experience }} años</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Estado</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $driver->driver_status === 'active' ? 'bg-green-100 text-green-800' :
                                       ($driver->driver_status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($driver->driver_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Licencia -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Información de Licencia</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Número de Licencia</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->license_number }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Categoría</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->license_category }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Fecha de Vencimiento</label>
                                <p class="mt-1 text-sm {{ $driver->license_expiration < now() ? 'text-red-600 font-semibold' : ($driver->license_expiration <= now()->addDays(30) ? 'text-yellow-600 font-semibold' : 'text-gray-900') }}">
                                    {{ $driver->license_expiration->format('d/m/Y') }}
                                    @if($driver->license_expiration < now())
                                        <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">VENCIDA</span>
                                    @elseif($driver->license_expiration <= now()->addDays(30))
                                        <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">PRÓXIMA A VENCER</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Autoridad Emisora</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->license_issuing_authority }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Ciudad de Emisión</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->license_issuing_city }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Fecha de Emisión</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->license_issue_date ? $driver->license_issue_date->format('d/m/Y') : 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Laboral -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Información Laboral</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Fecha de Contratación</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $driver->hire_date ? $driver->hire_date->format('d/m/Y') : 'No especificada' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Salario Mensual</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($driver->monthly_salary, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Estado de Empleo</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $driver->employment_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($driver->employment_status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rutas Asignadas -->
                @if($driver->routeAssignments->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Rutas Asignadas</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($driver->routeAssignments as $assignment)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $assignment->route->route_name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $assignment->route->origin_address }} → {{ $assignment->route->destination_address }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $assignment->assignment_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($assignment->assignment_status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Vehículo Asignado -->
                @if($driver->vehicles->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">🚗 Vehículo Asignado</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Asignado
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        @foreach($driver->vehicles as $vehicle)
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $vehicle->make }} {{ $vehicle->model }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $vehicle->vehicle_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($vehicle->vehicle_status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Placa</label>
                                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $vehicle->license_plate }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Año</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $vehicle->model_year }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Capacidad</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ $vehicle->passenger_capacity }} pasajeros</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Tipo de Combustible</label>
                                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($vehicle->fuel_type ?? 'N/A') }}</p>
                                    </div>
                                </div>

                                @if($vehicle->vehicle_class)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <label class="block text-sm font-medium text-gray-500">Clase de Vehículo</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($vehicle->vehicle_class) }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 bg-yellow-50">
                        <h3 class="text-lg font-semibold text-gray-900">🚗 Vehículo Asignado</h3>
                    </div>
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin vehículo asignado</h3>
                        <p class="mt-1 text-sm text-gray-500">Este conductor no tiene un vehículo asignado.</p>
                        <p class="mt-2 text-xs text-gray-400">Puedes asignar un vehículo al editar el conductor.</p>
                    </div>
                </div>
                @endif

                <!-- Acciones Rápidas -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Acciones Rápidas</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('provider.school.drivers.edit', $driver) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Conductor
                        </a>

                        @if($driver->driver_status === 'active')
                            <form method="POST" action="{{ route('provider.school.drivers.update-status', $driver) }}" class="w-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="driver_status" value="inactive">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700" onclick="return confirm('¿Desactivar conductor?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                    </svg>
                                    Desactivar Conductor
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('provider.school.drivers.update-status', $driver) }}" class="w-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="driver_status" value="active">
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700" onclick="return confirm('¿Activar conductor?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Activar Conductor
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
