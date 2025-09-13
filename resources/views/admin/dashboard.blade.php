{{--
    Dashboard de Administrador - Route Tracker

    Este archivo contiene la vista del dashboard para administradores del sistema.
    Muestra estadísticas generales del sistema, cuentas pendientes de verificación,
    proveedores pendientes de aprobación y métricas de rendimiento.

    Funcionalidades principales:
    - Estadísticas generales del sistema
    - Cuentas pendientes de verificación
    - Proveedores pendientes de aprobación
    - Métricas de rendimiento
    - Acceso rápido a funcionalidades administrativas

    Datos requeridos:
    - $totalAccounts: Total de cuentas en el sistema
    - $totalProviders: Total de proveedores
    - $activeRoutes: Rutas activas
    - $totalStudents: Total de estudiantes
    - $activeSubscriptions: Suscripciones activas
    - $pendingProviders: Proveedores pendientes
    - $totalRevenue: Ingresos totales
    - $verificationStats: Estadísticas de verificación
    - $recentAccounts: Cuentas recientes
    - $pendingProvidersList: Lista de proveedores pendientes
    - $pendingVerificationList: Lista de cuentas pendientes de verificación
--}}

@extends('layouts.app')

@section('title', 'Dashboard Administrador - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Administrador</h1>
        <p class="text-gray-600">Panel de control del sistema de transporte escolar</p>
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
                    <p class="text-sm font-medium text-gray-500">Total Cuentas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAccounts) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pendientes Verificación</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($verificationStats['pending_count']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
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
                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
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
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
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
    </div>

    <!-- Gráficos y estadísticas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Distribución de cuentas por tipo -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribución de Cuentas</h3>
            <div class="space-y-3">
                @foreach($accountsByType as $type => $count)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 capitalize">{{ $type }}</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($count / $totalAccounts) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Estado de proveedores -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado de Proveedores</h3>
            <div class="space-y-3">
                @foreach($providersByStatus as $status => $count)
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-700 capitalize">{{ $status }}</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($count / $totalProviders) * 100 }}%"></div>
                        </div>
                        <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">⚡ Acciones de Supervisión</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.verification.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🔍</div>
                    <span class="text-sm font-medium text-gray-700">Verificar Cuentas</span>
                </div>
            </a>

            <a href="{{ route('admin.providers.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-green-300 hover:bg-green-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🚛</div>
                    <span class="text-sm font-medium text-gray-700">Gestionar Proveedores</span>
                </div>
            </a>

            <a href="{{ route('schools.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🏫</div>
                    <span class="text-sm font-medium text-gray-700">Gestionar Escuelas</span>
                </div>
            </a>

            <a href="{{ route('admin.payments.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-yellow-300 hover:bg-yellow-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">💰</div>
                    <span class="text-sm font-medium text-gray-700">Monitorear Pagos</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Acceso rápido a recursos principales -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Acceso Rápido a Recursos</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.routes') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-purple-300 hover:bg-purple-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🛣️</div>
                    <span class="text-sm font-medium text-gray-700">Ver Todas las Rutas</span>
                    <p class="text-xs text-gray-500 mt-1">Monitorear rutas del sistema</p>
                </div>
            </a>

            <a href="{{ route('admin.students') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">👥</div>
                    <span class="text-sm font-medium text-gray-700">Ver Todos los Estudiantes</span>
                    <p class="text-xs text-gray-500 mt-1">Gestionar estudiantes registrados</p>
                </div>
            </a>

            <a href="{{ route('admin.subscriptions') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-teal-300 hover:bg-teal-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">📋</div>
                    <span class="text-sm font-medium text-gray-700">Ver Todas las Suscripciones</span>
                    <p class="text-xs text-gray-500 mt-1">Monitorear contratos activos</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Información reciente -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Cuentas pendientes de verificación -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cuentas Pendientes de Verificación</h3>
            @if($pendingVerificationList->count() > 0)
                <div class="space-y-3">
                    @foreach($pendingVerificationList as $account)
                    <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg border border-orange-200">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $account->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst($account->account_type) }} • {{ $account->email }}</p>
                        </div>
                        <a href="{{ route('admin.verification.show', $account) }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                            Verificar
                        </a>
                    </div>
                    @endforeach
                </div>
                @if($verificationStats['pending_count'] > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.verification.index') }}" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                            Ver todas ({{ $verificationStats['pending_count'] }} pendientes)
                        </a>
                    </div>
                @endif
            @else
                <p class="text-gray-500 text-sm">No hay cuentas pendientes de verificación</p>
            @endif
        </div>

        <!-- Últimas cuentas creadas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Últimas Cuentas Creadas</h3>
            @if($recentAccounts->count() > 0)
                <div class="space-y-3">
                    @foreach($recentAccounts as $account)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $account->full_name }}</p>
                            <p class="text-xs text-gray-500">{{ $account->email }} • {{ ucfirst($account->account_type) }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $account->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">No hay cuentas recientes</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Aquí puedes agregar JavaScript para gráficos interactivos
// Por ejemplo, usando Chart.js o cualquier otra librería
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard de administrador cargado');
});
</script>
@endpush
@endsection
