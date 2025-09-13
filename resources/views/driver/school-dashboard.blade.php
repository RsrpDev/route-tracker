@extends('layouts.app')

@section('title', 'Dashboard - Conductor de Colegio')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard - Conductor de Colegio</h1>
                    <p class="text-gray-600">{{ $employerName }} - {{ $account->full_name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('driver.logs') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Ver Logs
                    </a>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Fecha actual</p>
                        <p class="text-lg font-semibold text-gray-900">{{ now()->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Estadísticas Principales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Rutas Escolares</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $assignedRoutes }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Estudiantes</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalStudents }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Rutas Hoy</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $myRoutes->where('active_flag', true)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Escuela</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $myRoutes->first()->school->school_name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Rutas de Hoy -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Rutas Escolares de Hoy</h3>
                </div>
                <div class="p-6">
                    @if($myRoutes->where('active_flag', true)->count() > 0)
                        <div class="space-y-4">
                            @foreach($myRoutes->where('active_flag', true) as $route)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-900">{{ $route->route_name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $route->school->school_name ?? 'Sin escuela' }}</p>
                                        <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Recogida: {{ $route->pickup_time ?? 'N/A' }}
                                            </div>
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Entrega: {{ $route->dropoff_time ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="mt-2 text-xs text-gray-500">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $route->transportContracts->where('contract_status', 'active')->count() }} estudiantes
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="startRoute({{ $route->route_id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h1m4 0h1m-6-8h8a2 2 0 012 2v8a2 2 0 01-2 2H8a2 2 0 01-2-2V6a2 2 0 012-2z"></path>
                                            </svg>
                                            Iniciar
                                        </button>
                                        <a href="{{ route('driver.routes.show', $route) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes rutas asignadas para hoy</h3>
                            <p class="mt-1 text-sm text-gray-500">Contacta con la administración del colegio.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lista de Estudiantes -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Estudiantes Asignados</h3>
                </div>
                <div class="p-6">
                    @php
                        $allStudents = collect();
                        foreach($myRoutes as $route) {
                            $allStudents = $allStudents->merge($route->transportContracts->where('contract_status', 'active')->pluck('student'));
                        }
                        $uniqueStudents = $allStudents->unique('student_id');
                    @endphp

                    @if($uniqueStudents->count() > 0)
                        <div class="space-y-3">
                            @foreach($uniqueStudents->take(10) as $student)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                                    <p class="text-xs text-gray-500">Grado: {{ $student->grade ?? 'N/A' }} • ID: {{ $student->student_id }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Activo
                                    </span>
                                </div>
                            </div>
                            @endforeach

                            @if($uniqueStudents->count() > 10)
                                <div class="text-center pt-3">
                                    <p class="text-sm text-gray-500">Y {{ $uniqueStudents->count() - 10 }} estudiantes más...</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay estudiantes asignados</h3>
                            <p class="mt-1 text-sm text-gray-500">Los estudiantes aparecerán aquí cuando se asignen a las rutas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información del Colegio -->
        <div class="mt-8 bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Información del Colegio</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $provider->display_name }}</h4>
                        <p class="text-sm text-gray-500">{{ $provider->contact_email }}</p>
                        <p class="text-sm text-gray-500">{{ $provider->contact_phone }}</p>
                        @if($myRoutes->first() && $myRoutes->first()->school)
                            <div class="mt-3">
                                <h5 class="text-sm font-medium text-gray-900">Escuela Asociada</h5>
                                <p class="text-sm text-gray-500">{{ $myRoutes->first()->school->school_name }}</p>
                                <p class="text-sm text-gray-500">{{ $myRoutes->first()->school->address }}</p>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Información del Conductor</h4>
                        <p class="text-sm text-gray-500">Estado: <span class="text-green-600 font-medium">{{ ucfirst($employedDriver->driver_status) }}</span></p>
                        <p class="text-sm text-gray-500">Experiencia: {{ $employedDriver->years_experience }} años</p>
                        <p class="text-sm text-gray-500">Licencia: {{ $employedDriver->license_category }} - {{ $employedDriver->license_number }}</p>
                        <p class="text-sm text-gray-500">Fecha de contratación: {{ $employedDriver->hire_date ? $employedDriver->hire_date->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para iniciar ruta -->
<div id="startRouteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Iniciar Ruta Escolar</h3>
            <form id="startRouteForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                    <textarea name="observations" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Condiciones del vehículo, clima, tráfico, etc."></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Nivel de Combustible (%)</label>
                    <input type="number" name="fuel_level" min="0" max="100" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ej: 75">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Condiciones del Clima</label>
                    <select name="weather_conditions" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccionar...</option>
                        <option value="sunny">Soleado</option>
                        <option value="cloudy">Nublado</option>
                        <option value="rainy">Lluvioso</option>
                        <option value="stormy">Tormentoso</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStartRouteModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                        Iniciar Ruta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentRouteId = null;

function startRoute(routeId) {
    currentRouteId = routeId;
    document.getElementById('startRouteModal').classList.remove('hidden');
}

function closeStartRouteModal() {
    document.getElementById('startRouteModal').classList.add('hidden');
    currentRouteId = null;
}

document.getElementById('startRouteForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const observations = formData.get('observations');
    const fuelLevel = formData.get('fuel_level');
    const weatherConditions = formData.get('weather_conditions');

    // Aquí se enviaría la petición al servidor para iniciar la ruta
    console.log('Iniciando ruta escolar:', currentRouteId, { observations, fuelLevel, weatherConditions });

    // Simular inicio de ruta
    alert('Ruta escolar iniciada exitosamente');
    closeStartRouteModal();
});
</script>
@endsection
