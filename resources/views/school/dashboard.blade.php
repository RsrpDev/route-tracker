@extends('layouts.app')

@section('title', 'Dashboard Escuela - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Escuela</h1>
        <p class="text-gray-600">Panel de control de tu institución educativa</p>
    </div>

    <!-- Información de la escuela -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $school->legal_name ?? auth()->user()->full_name }}</h2>
                <p class="text-gray-600">{{ $school->phone_number ?? auth()->user()->email }}</p>
                <p class="text-sm text-gray-500">{{ $school->address ?? 'Dirección no especificada' }}</p>
                <p class="text-sm text-gray-500">Rector: {{ $school->rector_name ?? 'No especificado' }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">NIT</p>
                <p class="text-lg font-semibold text-gray-900">{{ $school->nit ?? 'No especificado' }}</p>
            </div>
        </div>
    </div>

    <!-- Estadísticas principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Estudiantes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalStudents) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Contratos Activos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($activeEnrollments) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Proveedores</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalProviders) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Rutas Activas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($activeRoutes) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ingresos del servicio de transporte (si la escuela es prestadora) -->
    @if($linkedProvider)
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">💰 Servicio de Transporte Escolar</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">${{ number_format($monthlyRevenue, 2) }}</div>
                <p class="text-sm text-gray-600">Ingresos del Mes</p>
                <p class="text-xs text-gray-500">Servicio de transporte</p>
            </div>
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ number_format($linkedProvider->routes->count()) }}</div>
                <p class="text-sm text-gray-600">Rutas Propias</p>
                <p class="text-xs text-gray-500">Servicio interno</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($linkedProvider->activeRoutes()->count()) }}</div>
                <p class="text-sm text-gray-600">Rutas Activas</p>
                <p class="text-xs text-gray-500">En operación</p>
            </div>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ route('provider.school.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Gestionar Servicio de Transporte
            </a>
        </div>
    </div>
    @else
    <!-- Sección para escuela que NO es proveedor de transporte -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                <i class="fas fa-bus text-2xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Servicio de Transporte Escolar</h3>
            <p class="text-gray-600 mb-6">
                Tu escuela aún no está registrada como proveedor de servicios de transporte estudiantil.
            </p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-blue-800">
                            ¿Qué puedes hacer como proveedor de transporte?
                        </h4>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Crear y gestionar rutas de transporte estudiantil</li>
                                <li>Administrar vehículos y conductores propios</li>
                                <li>Gestionar contratos de transporte con estudiantes</li>
                                <li>Monitorear ingresos y pagos del servicio</li>
                                <li>Acceder a herramientas especializadas de gestión</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('school.register-as-provider') }}"
               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i>
                Registrar como Proveedor de Transporte
            </a>
        </div>
    </div>
    @endif

    <!-- Distribución de estudiantes por grado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estudiantes por Grado</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($studentsByGrade as $grade => $count)
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ $count }}</div>
                <div class="text-sm text-gray-700">Grado {{ $grade }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Acciones Rápidas</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('school.students') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">👥</div>
                    <span class="text-sm font-medium text-gray-700">Estudiantes</span>
                </div>
            </a>

            <a href="{{ route('school.routes') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🚌</div>
                    <span class="text-sm font-medium text-gray-700">Rutas</span>
                </div>
            </a>

            <a href="{{ route('school.providers') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🏢</div>
                    <span class="text-sm font-medium text-gray-700">Proveedores</span>
                </div>
            </a>

            <a href="{{ route('school.profile') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-yellow-300 hover:bg-yellow-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">⚙️</div>
                    <span class="text-sm font-medium text-gray-700">Perfil</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Información detallada -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Estudiantes recientes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Estudiantes Recientes</h3>
            @if($students->count() > 0)
                <div class="space-y-3">
                    @foreach($students->take(5) as $student)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $student->given_name }} {{ $student->family_name }}</p>
                            <p class="text-xs text-gray-500">Grado {{ $student->grade }} • {{ $student->shift ?? 'Turno no especificado' }}</p>
                            <p class="text-xs text-gray-500">
                                @if($student->transportContract)
                                    Contrato activo con {{ $student->transportContract->provider->display_name }}
                                @else
                                    Sin contrato de transporte
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('school.students.show', $student) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($students->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('school.students') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todos los estudiantes ({{ $students->count() }})
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 text-sm">No hay estudiantes registrados</p>
            @endif
        </div>

        <!-- Proveedores de Transporte -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Proveedores de Transporte</h3>

            <!-- Estadísticas de proveedores -->
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="text-center p-3 bg-blue-50 rounded-lg">
                    <div class="text-lg font-bold text-blue-600">{{ number_format($providersByType['driver'] ?? 0) }}</div>
                    <p class="text-xs text-gray-600">Conductores</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded-lg">
                    <div class="text-lg font-bold text-green-600">{{ number_format($providersByType['company'] ?? 0) }}</div>
                    <p class="text-xs text-gray-600">Empresas</p>
                </div>
                <div class="text-center p-3 bg-purple-50 rounded-lg">
                    <div class="text-lg font-bold text-purple-600">{{ number_format($providersByType['school_provider'] ?? 0) }}</div>
                    <p class="text-xs text-gray-600">Colegios</p>
                </div>
            </div>

            @if($serviceProviders->count() > 0)
                <div class="space-y-3">
                    @foreach($serviceProviders->take(3) as $provider)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                @if($provider->provider_type === 'driver')
                                    <span class="text-sm">🚗</span>
                                @elseif($provider->provider_type === 'company')
                                    <span class="text-sm">🏢</span>
                                @else
                                    <span class="text-sm">🏫</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $provider->display_name }}</p>
                                <p class="text-xs text-gray-500">
                                    @switch($provider->provider_type)
                                        @case('driver')
                                            Conductor Independiente
                                            @break
                                        @case('company')
                                            Empresa de Transporte
                                            @break
                                        @case('school_provider')
                                            Colegio Prestador
                                            @break
                                        @default
                                            {{ $provider->provider_type }}
                                    @endswitch
                                </p>
                                <p class="text-xs text-gray-500">{{ $provider->routes->count() }} rutas •
                                    @php
                                        $totalContracts = $provider->routes->sum(function($route) {
                                            return $route->transportContracts->where('contract_status', 'active')->count() +
                                                   $route->dropoffContracts->where('contract_status', 'active')->count();
                                        });
                                    @endphp
                                    {{ $totalContracts }} estudiantes
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $provider->provider_status === 'active' ? 'bg-green-100 text-green-800' :
                                   ($provider->provider_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($provider->provider_status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($serviceProviders->count() > 3)
                    <div class="mt-4 text-center">
                        <a href="{{ route('school.providers') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todos los proveedores ({{ $serviceProviders->count() }})
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 text-sm">No hay proveedores de transporte registrados</p>
            @endif
        </div>
    </div>

    <!-- Rutas activas -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rutas Activas</h3>
        @if($routes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiantes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Origen</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destino</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($routes->take(10) as $route)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $route->route_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $route->provider->display_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $route->transportContracts->where('contract_status', 'active')->count() + $route->dropoffContracts->where('contract_status', 'active')->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ Str::limit($route->origin_address, 30) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ Str::limit($route->destination_address, 30) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($routes->count() > 10)
                <div class="mt-4 text-center">
                    <a href="{{ route('school.routes') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        Ver todas las rutas ({{ $routes->count() }})
                    </a>
                </div>
            @endif
        @else
            <p class="text-gray-500 text-sm">No hay rutas activas</p>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard de escuela cargado');
});
</script>
@endpush
@endsection
