@extends('layouts.app')

@section('title', 'Centro de Monitoreo - Transporte Escolar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header del Centro de Monitoreo -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🏫 Centro de Monitoreo</h1>
                <p class="text-gray-600">Supervisión del servicio de transporte escolar</p>
                <div class="mt-2 flex items-center space-x-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $provider->display_name ?? auth()->user()->full_name }}
                    </span>
                    @if($provider->linkedSchool)
                        <span class="text-sm text-gray-500">Vinculado a: {{ $provider->linkedSchool->legal_name }}</span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $provider->provider_status === 'active' ? 'bg-green-100 text-green-800' :
                           ($provider->provider_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($provider->provider_status ?? 'active') }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <p class="text-sm text-gray-500">Comisión por defecto</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $provider->default_commission_rate ?? 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel de Monitoreo Principal -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Estado del Servicio -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Estado del Servicio</p>
                    <p class="text-2xl font-bold text-gray-900">Activo</p>
                    <p class="text-xs text-gray-500">{{ number_format($activeRoutes) }} rutas operando</p>
                </div>
            </div>
        </div>

        <!-- Cobertura de Estudiantes -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Cobertura</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($enrolledInProviderRoutes) }}</p>
                    <p class="text-xs text-gray-500">de {{ number_format($schoolStudents) }} estudiantes</p>
                </div>
            </div>
        </div>

        <!-- Flota Activa -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Flota</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($activeVehicles) }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($activeDrivers) }} conductores</p>
                </div>
            </div>
        </div>

        <!-- Rendimiento Financiero -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ingresos Mensuales</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($monthlyRevenue, 2) }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($enrollmentsInProviderRoutes ?? 0) }} contratos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de ingresos -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ingresos del Colegio - Últimos 6 Meses</h3>
        <div class="h-64 flex items-end justify-between space-x-2">
            @foreach($monthlyRevenueData as $month => $amount)
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-gray-200 rounded-t" style="height: {{ $amount > 0 ? ($amount / max($monthlyRevenueData)) * 200 : 0 }}px">
                    <div class="w-full bg-blue-500 rounded-t" style="height: 100%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">{{ \Carbon\Carbon::parse($month)->format('M Y') }}</p>
                <p class="text-xs font-medium text-gray-900">${{ number_format($amount, 0) }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Centro de Control -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🎛️ Centro de Control</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('provider.school.routes') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🛣️</div>
                    <span class="text-sm font-medium text-gray-700">Monitorear Rutas</span>
                    <p class="text-xs text-gray-500 mt-1">Supervisar rutas activas</p>
                </div>
            </a>

            <a href="{{ route('provider.school.drivers.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">👨‍💼</div>
                    <span class="text-sm font-medium text-gray-700">Gestionar Conductores</span>
                    <p class="text-xs text-gray-500 mt-1">Conductores y vehículos</p>
                </div>
            </a>

            <a href="{{ route('provider.school.payments') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">💰</div>
                    <span class="text-sm font-medium text-gray-700">Control Financiero</span>
                    <p class="text-xs text-gray-500 mt-1">Pagos e ingresos</p>
                </div>
            </a>

            <a href="{{ route('provider.school.transport-contracts.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-yellow-300 hover:bg-yellow-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">📋</div>
                    <span class="text-sm font-medium text-gray-700">Contratos</span>
                    <p class="text-xs text-gray-500 mt-1">Gestión de contratos</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Panel de Monitoreo en Tiempo Real -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Estado de Rutas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">🛣️ Estado de Rutas</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    {{ $activeRoutes }} activas
                </span>
            </div>
            @if($routes->count() > 0)
                <div class="space-y-3">
                    @foreach($routes->take(5) as $route)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $route->route_name }}</p>
                                <p class="text-xs text-gray-500">{{ Str::limit($route->origin_address, 25) }} → {{ Str::limit($route->destination_address, 25) }}</p>
                                <p class="text-xs text-gray-500">{{ $route->transportContracts->where('contract_status', 'active')->count() + $route->dropoffContracts->where('contract_status', 'active')->count() }} estudiantes</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">${{ number_format($route->monthly_price, 2) }}</p>
                            <a href="{{ route('provider.school.routes.show', $route) }}" class="text-blue-600 hover:text-blue-800 text-xs">
                                Monitorear
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($routes->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('provider.school.routes') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todas las rutas ({{ $routes->count() }})
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                    </svg>
                    <p class="text-gray-500 text-sm mt-2">No hay rutas activas</p>
                </div>
            @endif
        </div>

        <!-- Alertas y Notificaciones -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">🚨 Alertas del Sistema</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    {{ $upcomingPayments->count() }} pendientes
                </span>
            </div>

            <!-- Alertas de Pagos -->
            @if($upcomingPayments->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingPayments->take(5) as $subscription)
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $subscription->transportContract->student->given_name }} {{ $subscription->transportContract->student->family_name }}</p>
                                <p class="text-xs text-gray-500">{{ $subscription->transportContract->pickupRoute->route_name ?? $subscription->transportContract->dropoffRoute->route_name ?? 'Ruta no especificada' }}</p>
                                <p class="text-xs text-gray-500">Vence: {{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d/m/Y') : 'No definida' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">${{ number_format($subscription->price_snapshot, 2) }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Pendiente
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($upcomingPayments->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('provider.school.payments') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todas las alertas ({{ $upcomingPayments->count() }})
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-600 text-sm mt-2">¡Todo al día! No hay alertas pendientes</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Resumen Ejecutivo -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Resumen Ejecutivo del Servicio</h3>

        <!-- Métricas clave -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ number_format($schoolStudents) }}</div>
                <p class="text-sm text-gray-600">Total Estudiantes</p>
                <p class="text-xs text-gray-500">en el colegio</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ number_format($enrolledInProviderRoutes) }}</div>
                <p class="text-sm text-gray-600">Nuestro Servicio</p>
                <p class="text-xs text-gray-500">estudiantes cubiertos</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($activeVehicles) }}</div>
                <p class="text-sm text-gray-600">Vehículos</p>
                <p class="text-xs text-gray-500">en operación</p>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ number_format($activeDrivers) }}</div>
                <p class="text-sm text-gray-600">Conductores</p>
                <p class="text-xs text-gray-500">certificados</p>
            </div>
        </div>

        <!-- Indicador de cobertura -->
        @if($schoolStudents > 0)
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium text-gray-900">Cobertura del Servicio</h4>
                    <span class="text-sm font-medium text-gray-600">{{ number_format(($enrolledInProviderRoutes / $schoolStudents) * 100, 1) }}%</span>
                </div>
                <div class="bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-300"
                         style="width: {{ ($enrolledInProviderRoutes / $schoolStudents) * 100 }}%"></div>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    {{ number_format($enrolledInProviderRoutes) }} de {{ number_format($schoolStudents) }} estudiantes utilizan nuestro servicio de transporte
                </p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Centro de Monitoreo de Transporte Escolar cargado');

    // Simular actualización en tiempo real (para futuras implementaciones)
    setInterval(function() {
        // Aquí se podría implementar actualización automática de datos
        // Por ejemplo, verificar estado de rutas, pagos pendientes, etc.
    }, 30000); // Cada 30 segundos
});
</script>
@endpush
@endsection
