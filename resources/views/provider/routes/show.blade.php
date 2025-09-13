@extends('layouts.app')

@section('title', 'Detalles de la Ruta - Proveedor')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $route->route_name }}</h1>
                    <p class="text-gray-600">{{ $provider->display_name ?? 'Proveedor de Transporte' }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('provider.routes.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Rutas
                    </a>
                    <a href="{{ route('routes.edit', $route) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar Ruta
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información de la Ruta -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Información de la Ruta</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Nombre de la Ruta</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->route_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Estado</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $route->active_flag ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $route->active_flag ? 'Activa' : 'Inactiva' }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Origen</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->origin_address }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Destino</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->destination_address }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Distancia</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->distance_km }} km</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Duración Estimada</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->estimated_duration_minutes }} minutos</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Capacidad Máxima</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->max_capacity ?? 'No especificada' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Tarifa Base</label>
                                <p class="mt-1 text-sm text-gray-900">${{ number_format($route->base_fare ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horarios -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Horarios</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Hora de Recogida</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->pickup_time ? \Carbon\Carbon::parse($route->pickup_time)->format('H:i') : 'No especificada' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Hora de Entrega</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->dropoff_time ? \Carbon\Carbon::parse($route->dropoff_time)->format('H:i') : 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estudiantes Asignados -->
                @if($route->transportContracts->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Estudiantes Asignados ({{ $route->transportContracts->count() }})</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($route->transportContracts as $contract)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $contract->student->given_name }} {{ $contract->student->family_name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $contract->student->id_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $contract->student->phone_number }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $contract->contract_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($contract->contract_status) }}
                                        </span>
                                        <p class="text-sm text-gray-500 mt-1">${{ number_format($contract->monthly_fare, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Estudiantes Asignados</h3>
                    </div>
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin estudiantes asignados</h3>
                        <p class="mt-1 text-sm text-gray-500">Esta ruta no tiene estudiantes asignados.</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Información de la Escuela -->
                @if($route->school)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Escuela Asignada</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Nombre</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->school->legal_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Dirección</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->school->address }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Ciudad</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->school->city }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Teléfono</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $route->school->phone_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Escuela Asignada</h3>
                    </div>
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin escuela asignada</h3>
                        <p class="mt-1 text-sm text-gray-500">Esta ruta no tiene una escuela asignada.</p>
                    </div>
                </div>
                @endif

                <!-- Conductores Asignados -->
                @if($route->routeAssignments->count() > 0)
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Conductores Asignados</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($route->routeAssignments as $assignment)
                                @if($assignment->driver)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">{{ $assignment->driver->given_name }} {{ $assignment->driver->family_name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $assignment->driver->phone_number }}</p>
                                            <p class="text-sm text-gray-500">{{ $assignment->driver->license_number }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $assignment->assignment_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($assignment->assignment_status) }}
                                        </span>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Conductores Asignados</h3>
                    </div>
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin conductores asignados</h3>
                        <p class="mt-1 text-sm text-gray-500">Esta ruta no tiene conductores asignados.</p>
                    </div>
                </div>
                @endif

                <!-- Estadísticas de la Ruta -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Estadísticas</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Total Estudiantes</span>
                                <span class="text-sm font-medium text-gray-900">{{ $route->transportContracts->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Conductores Asignados</span>
                                <span class="text-sm font-medium text-gray-900">{{ $route->routeAssignments->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Capacidad Utilizada</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $route->max_capacity ? round(($route->transportContracts->count() / $route->max_capacity) * 100, 1) : 'N/A' }}%
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Ingresos Mensuales</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($route->transportContracts->sum('monthly_fare'), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Acciones Rápidas</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('routes.edit', $route) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Ruta
                        </a>

                        <a href="{{ route('provider.school.drivers.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            Gestionar Conductores
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
