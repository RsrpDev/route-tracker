{{--
    Archivo: resources/views/provider/dashboard.blade.php
    Roles: provider
    Rutas necesarias: Route::get('provider/dashboard', [ProviderDashboardController::class, 'index'])->name('provider.dashboard')
--}}

@extends('layouts.app')

@section('title', 'Dashboard Proveedor - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Proveedor</h1>
        <p class="text-gray-600">Panel de control de tu servicio de transporte</p>
    </div>

    <!-- Información del proveedor -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $provider->display_name ?? auth()->user()->full_name }}</h2>
                <p class="text-gray-600">{{ $provider->contact_email ?? auth()->user()->email }}</p>
                <p class="text-sm text-gray-500">Estado:
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $provider->provider_status === 'active' ? 'bg-green-100 text-green-800' :
                           ($provider->provider_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($provider->provider_status ?? 'active') }}
                    </span>
                </p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Comisión por defecto</p>
                <p class="text-lg font-semibold text-gray-900">{{ $provider->default_commission_rate ?? 0 }}%</p>
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
                    <p class="text-sm font-medium text-gray-500">Rutas Activas</p>
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
                    <p class="text-sm font-medium text-gray-500">Estudiantes</p>
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
                    <p class="text-sm font-medium text-gray-500">Vehículos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($activeVehicles) }}</p>
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

    <!-- Gráfico de ingresos -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ingresos de los Últimos 6 Meses</h3>
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
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('provider.routes') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🚌</div>
                    <span class="text-sm font-medium text-gray-700">Mis Rutas</span>
                </div>
            </a>

            <a href="{{ route('provider.drivers') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">👨‍💼</div>
                    <span class="text-sm font-medium text-gray-700">Conductores</span>
                </div>
            </a>

            <a href="{{ route('provider.driver.vehicles') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🚗</div>
                    <span class="text-sm font-medium text-gray-700">Vehículos</span>
                </div>
            </a>

            <a href="{{ route('provider.payments') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-yellow-300 hover:bg-yellow-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">💰</div>
                    <span class="text-sm font-medium text-gray-700">Pagos</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Información detallada -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Rutas activas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Rutas Activas</h3>
            @if($routes->count() > 0)
                <div class="space-y-3">
                    @foreach($routes->take(5) as $route)
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
                @if($routes->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('provider.routes') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todas las rutas ({{ $routes->count() }})
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 text-sm">No tienes rutas activas</p>
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
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard de proveedor cargado');
});
</script>
@endpush
@endsection
