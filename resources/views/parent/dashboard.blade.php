{{--
    Dashboard de Padre de Familia - Route Tracker

    Este archivo contiene la vista del dashboard para padres de familia.
    Muestra información relevante sobre sus hijos, contratos de transporte,
    pagos pendientes y notificaciones importantes.

    Funcionalidades principales:
    - Información del padre y sus hijos
    - Contratos de transporte activos
    - Pagos pendientes y vencidos
    - Notificaciones y alertas
    - Acceso rápido a funcionalidades principales

    Datos requeridos:
    - $parent: Perfil del padre
    - $students: Lista de hijos
    - $activeEnrollments: Contratos activos
    - $upcomingPayments: Pagos próximos
    - $notifications: Notificaciones y alertas
--}}

@extends('layouts.app')

@section('title', 'Dashboard Padre - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Padre</h1>
        <p class="text-gray-600">Panel de control para el transporte de tus hijos</p>
    </div>

    <!-- Información del padre -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ auth()->user()->full_name }}</h2>
                <p class="text-gray-600">{{ auth()->user()->email }}</p>
                <p class="text-sm text-gray-500">{{ $parent->address ?? 'Dirección no especificada' }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Hijos registrados</p>
                <p class="text-lg font-semibold text-gray-900">{{ $totalStudents }}</p>
            </div>
        </div>
    </div>

    <!-- Notificaciones y Alertas -->
    @if($notifications->count() > 0)
    <div class="mb-8">
        @foreach($notifications as $notification)
        <div class="bg-white rounded-lg shadow-md p-4 mb-4 border-l-4
            @if($notification['type'] === 'warning') border-yellow-400 bg-yellow-50
            @elseif($notification['type'] === 'error') border-red-400 bg-red-50
            @else border-blue-400 bg-blue-50
            @endif">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if($notification['type'] === 'warning')
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($notification['type'] === 'error')
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                </div>
                <div class="ml-3 flex-1">
                    <h3 class="text-sm font-medium
                        @if($notification['type'] === 'warning') text-yellow-800
                        @elseif($notification['type'] === 'error') text-red-800
                        @else text-blue-800
                        @endif">
                        {{ $notification['title'] }}
                    </h3>
                    <p class="mt-1 text-sm
                        @if($notification['type'] === 'warning') text-yellow-700
                        @elseif($notification['type'] === 'error') text-red-700
                        @else text-blue-700
                        @endif">
                        {{ $notification['message'] }}
                    </p>
                    @if(isset($notification['action']))
                    <div class="mt-2">
                        <a href="{{ $notification['action'] }}" class="text-sm font-medium
                            @if($notification['type'] === 'warning') text-yellow-800 hover:text-yellow-900
                            @elseif($notification['type'] === 'error') text-red-800 hover:text-red-900
                            @else text-blue-800 hover:text-blue-900
                            @endif">
                            {{ $notification['action_text'] }} →
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

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
                    <p class="text-sm font-medium text-gray-500">Hijos</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($activeEnrollmentsCount) }}</p>
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
                    <p class="text-sm font-medium text-gray-500">Pagos Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($upcomingPaymentsCount) }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($routes->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Estado de contratos -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado de Contratos</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ $activeSubscriptions }}</div>
                <div class="text-sm text-green-700">Activos</div>
            </div>
            <div class="text-center p-4 bg-yellow-50 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ $pendingSubscriptions }}</div>
                <div class="text-sm text-yellow-700">Pendientes</div>
            </div>
            <div class="text-center p-4 bg-red-50 rounded-lg">
                <div class="text-2xl font-bold text-red-600">{{ $overdueSubscriptions }}</div>
                <div class="text-sm text-red-700">Vencidos</div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas Principales -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-6 mb-8 border border-blue-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900 flex items-center">
                <span class="text-2xl mr-3">⚡</span>
                Acciones Rápidas
            </h3>
            <span class="text-sm text-gray-600 bg-white px-3 py-1 rounded-full border">Acceso Directo</span>
        </div>

        <!-- Primera fila - Acciones más importantes -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <a href="{{ route('students.create') }}" class="group block p-6 bg-white rounded-lg border-2 border-transparent hover:border-blue-300 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <span class="text-xl">👶</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 group-hover:text-blue-700">Agregar Hijo</h4>
                        <p class="text-sm text-gray-600">Registrar nuevo estudiante</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('parent.contracts') }}" class="group block p-6 bg-white rounded-lg border-2 border-transparent hover:border-green-300 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                        <span class="text-xl">📝</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 group-hover:text-green-700">Mis Contratos</h4>
                        <p class="text-sm text-gray-600">Gestionar contratos activos</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('parent.provider-selection.index') }}" class="group block p-6 bg-white rounded-lg border-2 border-transparent hover:border-purple-300 hover:shadow-lg transition-all duration-200">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                        <span class="text-xl">🚌</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 group-hover:text-purple-700">Buscar Conductores</h4>
                        <p class="text-sm text-gray-600">Encontrar transporte</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Segunda fila - Acciones secundarias -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('students.index') }}" class="group block p-4 bg-white rounded-lg border border-gray-200 hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                <div class="text-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:bg-blue-200 transition-colors">
                        <span class="text-lg">👥</span>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-blue-700">Ver Hijos</span>
                </div>
            </a>

            <a href="{{ route('payments.index') }}" class="group block p-4 bg-white rounded-lg border border-gray-200 hover:border-yellow-300 hover:bg-yellow-50 transition-all duration-200">
                <div class="text-center">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:bg-yellow-200 transition-colors">
                        <span class="text-lg">💳</span>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700">Ver Pagos</span>
                </div>
            </a>

            <a href="{{ route('payments.create') }}" class="group block p-4 bg-white rounded-lg border border-gray-200 hover:border-green-300 hover:bg-green-50 transition-all duration-200">
                <div class="text-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:bg-green-200 transition-colors">
                        <span class="text-lg">💰</span>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-green-700">Realizar Pago</span>
                </div>
            </a>

            <a href="{{ route('parent.routes') }}" class="group block p-4 bg-white rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all duration-200">
                <div class="text-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-2 group-hover:bg-purple-200 transition-colors">
                        <span class="text-lg">🗺️</span>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-purple-700">Ver Rutas</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Información detallada -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Hijos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Mis Hijos</h3>
                <a href="{{ route('students.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    + Agregar Hijo
                </a>
            </div>
            @if($students->count() > 0)
                <div class="space-y-3">
                    @foreach($students as $student)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-blue-800">
                                    {{ substr($student->given_name, 0, 1) }}{{ substr($student->family_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $student->given_name }} {{ $student->family_name }}</p>
                                <p class="text-xs text-gray-500">{{ $student->school->legal_name ?? 'Escuela no especificada' }} • Grado {{ $student->grade }}</p>
                                <p class="text-xs text-gray-500">
                                    @if($student->transportContract)
                                        <span class="text-green-600">✓ Contrato activo</span>
                                    @else
                                        <span class="text-yellow-600">⚠ Sin contrato</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('students.show', $student) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver detalles
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($students->count() > 3)
                    <div class="mt-4 text-center">
                        <a href="{{ route('students.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todos los hijos ({{ $students->count() }})
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">👶</div>
                    <p class="text-gray-500 text-sm mb-4">No tienes hijos registrados</p>
                    <a href="{{ route('students.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors text-sm">
                        Registrar Primer Hijo
                    </a>
                </div>
            @endif
        </div>

        <!-- Próximos pagos -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Próximos Pagos</h3>
                <a href="{{ route('payments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todos
                </a>
            </div>
            @if($upcomingPayments->count() > 0)
                <div class="space-y-3">
                    @foreach($upcomingPayments->take(5) as $subscription)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-yellow-800">💳</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $subscription->transportContract->student->given_name }} {{ $subscription->transportContract->student->family_name }}</p>
                                <p class="text-xs text-gray-500">{{ $subscription->transportContract->pickupRoute->route_name ?? $subscription->transportContract->dropoffRoute->route_name ?? 'Ruta no especificada' }}</p>
                                <p class="text-xs text-gray-500">
                                    Vence: {{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d/m/Y') : 'No definida' }}
                                    @if($subscription->next_billing_date && $subscription->next_billing_date->isPast())
                                        <span class="text-red-600 ml-2">⚠ Vencido</span>
                                    @elseif($subscription->next_billing_date && $subscription->next_billing_date->diffInDays() <= 3)
                                        <span class="text-orange-600 ml-2">⚠ Próximo</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">${{ number_format($subscription->price_snapshot, 0, ',', '.') }}</p>
                            <a href="{{ route('payments.index') }}" class="text-blue-600 hover:text-blue-800 text-xs">
                                Pagar ahora
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($upcomingPayments->count() > 5)
                    <div class="mt-4 text-center">
                        <a href="{{ route('payments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ver todos los pagos ({{ $upcomingPayments->count() }})
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-4">💳</div>
                    <p class="text-gray-500 text-sm mb-4">No hay pagos próximos</p>
                    <p class="text-xs text-gray-400">Todos tus pagos están al día</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Contratos Activos -->
    @if($activeEnrollments->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Contratos Activos</h3>
            <a href="{{ route('parent.contracts') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Ver todos los contratos
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($activeEnrollments->take(6) as $contract)
            <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-xs font-medium text-blue-800">🚌</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $contract->student->given_name }} {{ $contract->student->family_name }}</p>
                            <p class="text-xs text-gray-500">Grado {{ $contract->student->grade }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Activo
                    </span>
                </div>

                <div class="space-y-2 text-xs text-gray-600">
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                        <span>{{ $contract->pickupRoute->route_name ?? 'Ruta de recogida' }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        <span>{{ $contract->dropoffRoute->route_name ?? 'Ruta de entrega' }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                        <span>{{ $contract->provider->business_name ?? 'Proveedor' }}</span>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-900">
                            ${{ number_format($contract->subscription->price_snapshot ?? 0, 0, ',', '.') }}/mes
                        </span>
                        <a href="{{ route('parent.contracts.show', $contract->subscription->subscription_id ?? $contract->contract_id) }}" class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Ver detalles
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($activeEnrollments->count() > 6)
            <div class="mt-4 text-center">
                <a href="{{ route('parent.contracts') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todos los contratos ({{ $activeEnrollments->count() }})
                </a>
            </div>
        @endif
    </div>
    @endif

    <!-- Historial de pagos recientes -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Historial de Pagos Recientes</h3>
            <a href="{{ route('payments.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Ver historial completo
            </a>
        </div>
        @if($recentPayments->count() > 0)
            <div class="space-y-3">
                @foreach($recentPayments as $payment)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <span class="text-lg">💰</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $payment->subscription->transportContract->student->given_name }} {{ $payment->subscription->transportContract->student->family_name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $payment->subscription->transportContract->pickupRoute->route_name ?? $payment->subscription->transportContract->dropoffRoute->route_name ?? 'Ruta no especificada' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">${{ number_format($payment->amount_total, 0, ',', '.') }}</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $payment->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                               ($payment->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($payment->payment_status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-4xl mb-4">💰</div>
                <p class="text-gray-500 text-sm mb-4">No hay pagos recientes</p>
                <p class="text-xs text-gray-400">Los pagos aparecerán aquí una vez que se realicen</p>
            </div>
        @endif
    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard de padre cargado');
});
</script>
@endpush
@endsection
