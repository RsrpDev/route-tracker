@extends('layouts.app')

@section('title', 'Detalle de Ruta - Conductor')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $route->route_name }}</h1>
                    <p class="text-gray-600">{{ $route->pickup_location }} → {{ $route->dropoff_location }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('driver.routes') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Rutas
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Información de la Ruta -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Información de la Ruta</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">ID de la Ruta</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $route->route_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Nombre de la Ruta</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $route->route_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Punto de Recogida</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $route->pickup_location }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Punto de Entrega</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $route->dropoff_location }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Distancia</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $route->distance ?? 'N/A' }} km</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Duración Estimada</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $route->estimated_duration ?? 'N/A' }} minutos</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Estado</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $route->active_flag ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $route->active_flag ? 'Activa' : 'Inactiva' }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Fecha de Creación</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $route->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Horarios -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Horarios de la Ruta</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center p-4 bg-green-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-green-800">Hora de Recogida</h4>
                                <p class="text-lg font-semibold text-green-900">{{ $route->pickup_time ?? 'No especificada' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-red-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-red-800">Hora de Entrega</h4>
                                <p class="text-lg font-semibold text-red-900">{{ $route->dropoff_time ?? 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estudiantes Asignados -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Estudiantes Asignados</h3>

                    @if($route->transportContracts->where('contract_status', 'active')->count() > 0)
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($route->transportContracts->where('contract_status', 'active') as $contract)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $contract->student->first_name }} {{ $contract->student->last_name }}</div>
                                                    <div class="text-sm text-gray-500">ID: {{ $contract->student->student_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $contract->student->grade ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $contract->student->phone_number ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay estudiantes asignados</h3>
                            <p class="mt-1 text-sm text-gray-500">Esta ruta no tiene estudiantes activos asignados.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Información de la Escuela -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de la Escuela</h3>

                    @if($route->school)
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $route->school->school_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $route->school->address }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-900">{{ $route->school->phone_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-900">{{ $route->school->email ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No hay información de escuela disponible</p>
                        </div>
                    @endif
                </div>

                <!-- Estadísticas de la Ruta -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Estadísticas</h3>

                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Total Estudiantes:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $route->transportContracts->where('contract_status', 'active')->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Contratos Activos:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $route->transportContracts->where('contract_status', 'active')->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Contratos Inactivos:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $route->transportContracts->where('contract_status', 'inactive')->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Capacidad Máxima:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $route->max_capacity ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>

                    <div class="space-y-3">
                        <a href="{{ route('driver.routes') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Ver Todas las Rutas
                        </a>
                        <a href="{{ route('driver.dashboard') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Volver al Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




