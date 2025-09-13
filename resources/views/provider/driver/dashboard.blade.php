@extends('layouts.app')

@section('title', 'Dashboard - Conductor Independiente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado del conductor -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $provider->display_name ?? auth()->user()->full_name }}</h2>
                <p class="text-gray-600">{{ $provider->contact_email ?? auth()->user()->email }}</p>
                <p class="text-sm text-gray-500">Conductor Independiente</p>
                <p class="text-sm text-gray-500">Estado:
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $provider->provider_status === 'active' ? 'bg-green-100 text-green-800' :
                           ($provider->provider_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($provider->provider_status ?? 'active') }}
                    </span>
                </p>
                @if($provider->isIndependentDriver())
                <p class="text-sm text-gray-500 mt-1">
                    <strong>Licencia:</strong> {{ $provider->driver_license_number }} ({{ $provider->driver_license_category }})
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ml-2
                        {{ $provider->hasValidLicense() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $provider->getLicenseStatusText() }}
                    </span>
                </p>
                <p class="text-sm text-gray-500">
                    <strong>Experiencia:</strong> {{ $provider->driver_years_experience }} años
                    <span class="mx-2">•</span>
                    <strong>Vence:</strong> {{ $provider->driver_license_expiration ? $provider->driver_license_expiration->format('d/m/Y') : 'No definida' }}
                </p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Comisión por defecto</p>
                <p class="text-lg font-semibold text-gray-900">{{ $provider->default_commission_rate ?? 0 }}%</p>
                @if($provider->isIndependentDriver())
                <div class="mt-4">
                    <a href="{{ route('provider.driver.profile') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Mi Perfil
                    </a>
                </div>
                @endif
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Mis Rutas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($activeRoutes) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Mis Estudiantes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalStudents) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Mi Vehículo</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $myVehicle ? 'Activo' : 'No asignado' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ingresos del Mes</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($monthlyRevenue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas adicionales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Ingresos Totales</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pagos Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingPayments) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-teal-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Promedio Mensual</p>
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($averageMonthlyRevenue, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de ingresos -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Ingresos de los Últimos 6 Meses</h3>
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

    <!-- Acciones rápidas -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Acciones Rápidas</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <a href="{{ route('provider.routes') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🚌</div>
                    <span class="text-sm font-medium text-gray-700">Mis Rutas</span>
                </div>
            </a>

            <a href="{{ route('provider.driver.vehicles') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🚗</div>
                    <span class="text-sm font-medium text-gray-700">Mi Vehículo</span>
                </div>
            </a>

            <a href="{{ route('provider.transport-contracts.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">👨‍🎓</div>
                    <span class="text-sm font-medium text-gray-700">Mis Estudiantes</span>
                </div>
            </a>

            <a href="{{ route('provider.payments') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-yellow-300 hover:bg-yellow-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">💰</div>
                    <span class="text-sm font-medium text-gray-700">Mis Pagos</span>
                </div>
            </a>

            <a href="{{ route('provider.driver.profile') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">👤</div>
                    <span class="text-sm font-medium text-gray-700">Mi Perfil</span>
                </div>
            </a>

            <a href="{{ route('provider.payments') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-gray-300 hover:bg-gray-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">📊</div>
                    <span class="text-sm font-medium text-gray-700">Estadísticas</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Información detallada -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Mis rutas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Rutas</h3>
            @if($myRoutes->count() > 0)
                <div class="space-y-3">
                    @foreach($myRoutes->take(5) as $route)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $route->route_name }}</p>
                            <p class="text-xs text-gray-500">{{ $route->origin_address }} → {{ $route->destination_address }}</p>
                            <p class="text-xs text-gray-500">{{ $route->transportContracts->where('contract_status', 'active')->count() }} estudiantes</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">${{ number_format($route->monthly_price, 2) }}</p>
                            <a href="{{ route('provider.routes.show', $route) }}" class="text-blue-600 hover:text-blue-800 text-xs">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($myRoutes->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('provider.routes') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todas mis rutas ({{ $myRoutes->count() }})
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 text-sm">No tienes rutas asignadas</p>
            @endif
        </div>

        <!-- Próximos pagos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Próximos Pagos</h3>
            @if($upcomingPayments->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingPayments->take(5) as $subscription)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $subscription->transportContract->student->given_name }} {{ $subscription->transportContract->student->family_name }}</p>
                            <p class="text-xs text-gray-500">{{ $subscription->transportContract->pickupRoute->route_name }}</p>
                            <p class="text-xs text-gray-500">Vence: {{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d/m/Y') : 'No definida' }}</p>
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
                        <a href="{{ route('provider.payments') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todos los pagos ({{ $upcomingPayments->count() }})
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 text-sm">No hay pagos próximos</p>
            @endif
        </div>
    </div>

    <!-- Información adicional -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
        <!-- Estado del vehículo -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado de Mi Vehículo</h3>
            @if($myVehicle)
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Marca y Modelo</span>
                        <span class="text-sm font-medium text-gray-900">{{ $myVehicle->vehicle_make }} {{ $myVehicle->vehicle_model }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Año</span>
                        <span class="text-sm font-medium text-gray-900">{{ $myVehicle->vehicle_year }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Placa</span>
                        <span class="text-sm font-medium text-gray-900">{{ $myVehicle->license_plate }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Capacidad</span>
                        <span class="text-sm font-medium text-gray-900">{{ $myVehicle->capacity }} estudiantes</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Estado</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ ucfirst($myVehicle->vehicle_status) }}
                        </span>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 text-4xl mb-4">🚗</div>
                    <p class="text-gray-500 text-sm mb-4">No tienes un vehículo asignado</p>
                    <a href="{{ route('provider.driver.vehicles') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Registrar Vehículo
                    </a>
                </div>
            @endif
        </div>

        <!-- Resumen de actividades -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen de Actividades</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Rutas Activas</p>
                            <p class="text-xs text-gray-500">{{ $activeRoutes }} rutas en servicio</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-blue-600">{{ $activeRoutes }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Estudiantes</p>
                            <p class="text-xs text-gray-500">Estudiantes activos</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-green-600">{{ $totalStudents }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Ingresos del Mes</p>
                            <p class="text-xs text-gray-500">{{ now()->format('F Y') }}</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-yellow-600">${{ number_format($monthlyRevenue, 0) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard de conductor independiente cargado');
});
</script>
@endpush
@endsection
