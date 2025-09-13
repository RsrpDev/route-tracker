@extends('layouts.app')

@section('title', 'Mis Estadísticas - Conductor Independiente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Mis Estadísticas</h2>
                <p class="text-gray-600">Resumen de tu rendimiento y actividad como conductor independiente</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('driver.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Perfil
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas Principales -->
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
                    <p class="text-sm font-medium text-gray-500">Rutas Totales</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_routes']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Rutas Activas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_routes']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Estudiantes Activos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_students']) }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">${{ number_format($stats['monthly_income'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Financieras -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Resumen Financiero</h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Ingresos Totales</p>
                            <p class="text-xs text-gray-500">Desde el inicio</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-green-600">${{ number_format($stats['total_income'], 2) }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Promedio Mensual</p>
                            <p class="text-xs text-gray-500">Últimos 6 meses</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-blue-600">${{ number_format($stats['average_monthly_income'] ?? 0, 2) }}</span>
                </div>

                <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Ingresos del Mes</p>
                            <p class="text-xs text-gray-500">{{ now()->format('F Y') }}</p>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-yellow-600">${{ number_format($stats['monthly_income'], 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Información Profesional -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Información Profesional</h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Años de Experiencia:</span>
                    <span class="text-sm text-gray-900 font-semibold">{{ $stats['years_experience'] ?? 0 }} años</span>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Estado de Licencia:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $provider->hasValidLicense() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $stats['license_status'] }}
                    </span>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Categoría de Licencia:</span>
                    <span class="text-sm text-gray-900">{{ $provider->driver_license_category ?? 'No especificada' }}</span>
                </div>

                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Comisión por Defecto:</span>
                    <span class="text-sm text-gray-900">{{ $provider->default_commission_rate ?? 0 }}%</span>
                </div>

                @if($stats['license_expiration'])
                <div class="flex items-center justify-between py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Licencia Vence:</span>
                    <span class="text-sm text-gray-900">{{ $stats['license_expiration']->format('d/m/Y') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Métricas de Rendimiento -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Métricas de Rendimiento</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $stats['total_routes'] > 0 ? round(($stats['active_routes'] / $stats['total_routes']) * 100, 1) : 0 }}%
                </div>
                <p class="text-sm text-gray-600">Rutas Activas</p>
                <p class="text-xs text-gray-500 mt-1">{{ $stats['active_routes'] }} de {{ $stats['total_routes'] }} rutas</p>
            </div>

            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $stats['total_students'] > 0 ? round($stats['monthly_income'] / $stats['total_students'], 2) : 0 }}
                </div>
                <p class="text-sm text-gray-600">Ingreso por Estudiante</p>
                <p class="text-xs text-gray-500 mt-1">Promedio mensual</p>
            </div>

            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-2xl font-bold text-gray-900 mb-2">
                    {{ $stats['years_experience'] ?? 0 }}
                </div>
                <p class="text-sm text-gray-600">Años de Experiencia</p>
                <p class="text-xs text-gray-500 mt-1">Como conductor profesional</p>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('provider.driver.dashboard') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors text-center">
                <div class="text-2xl mb-2">📊</div>
                <span class="text-sm font-medium text-gray-700">Dashboard</span>
            </a>

            <a href="{{ route('provider.routes') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors text-center">
                <div class="text-2xl mb-2">🚌</div>
                <span class="text-sm font-medium text-gray-700">Mis Rutas</span>
            </a>

            <a href="{{ route('provider.transport-contracts.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors text-center">
                <div class="text-2xl mb-2">👨‍🎓</div>
                <span class="text-sm font-medium text-gray-700">Estudiantes</span>
            </a>

            <a href="{{ route('provider.payments') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-yellow-300 hover:bg-yellow-50 transition-colors text-center">
                <div class="text-2xl mb-2">💰</div>
                <span class="text-sm font-medium text-gray-700">Pagos</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Estadísticas del conductor cargadas');

    // Agregar animación a las tarjetas de estadísticas
    const cards = document.querySelectorAll('.bg-white.rounded-lg.shadow-md');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('animate-fade-in');
    });
});
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out forwards;
}
</style>
@endpush
@endsection
