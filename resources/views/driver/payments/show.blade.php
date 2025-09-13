@extends('layouts.app')

@section('title', 'Detalle de Pago - Conductor')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Pago #{{ $payment->payment_id }}</h1>
                    <p class="text-gray-600">{{ $payment->subscription->pickupRoute->route_name ?? 'N/A' }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('driver.payments') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Pagos
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Información del Pago -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Información del Pago</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">ID del Pago</p>
                                <p class="mt-1 text-lg text-gray-900">#{{ $payment->payment_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Monto</p>
                                <p class="mt-1 text-lg font-bold text-gray-900">${{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Método de Pago</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $payment->payment_method ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Estado</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($payment->payment_status === 'completed') bg-green-100 text-green-800
                                    @elseif($payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($payment->payment_status === 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($payment->payment_status) }}
                                </span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Fecha del Pago</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $payment->payment_date ? $payment->payment_date->format('d/m/Y H:i') : 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Referencia</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $payment->transaction_reference ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Fecha de Creación</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Última Actualización</p>
                                <p class="mt-1 text-lg text-gray-900">{{ $payment->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Estudiante -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Información del Estudiante</h3>

                    @if($payment->subscription->transportContract->student)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nombre Completo</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->transportContract->student->first_name }} {{ $payment->subscription->transportContract->student->last_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">ID del Estudiante</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->transportContract->student->student_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Grado</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->transportContract->student->grade ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Teléfono</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->transportContract->student->phone_number ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Email</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->transportContract->student->email ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Dirección</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->transportContract->student->address ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No hay información del estudiante disponible</p>
                        </div>
                    @endif
                </div>

                <!-- Información de la Ruta -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Información de la Ruta</h3>

                    @if($payment->subscription->pickupRoute)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Nombre de la Ruta</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->pickupRoute->route_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Punto de Recogida</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->pickupRoute->pickup_location }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Punto de Entrega</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->pickupRoute->dropoff_location }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Hora de Recogida</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->pickupRoute->pickup_time ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Hora de Entrega</p>
                                    <p class="mt-1 text-lg text-gray-900">{{ $payment->subscription->pickupRoute->dropoff_time ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Estado de la Ruta</p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $payment->subscription->pickupRoute->active_flag ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $payment->subscription->pickupRoute->active_flag ? 'Activa' : 'Inactiva' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">No hay información de la ruta disponible</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Estado del Pago -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado del Pago</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Estado:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payment->payment_status === 'completed') bg-green-100 text-green-800
                                @elseif($payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($payment->payment_status === 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Monto:</span>
                            <span class="text-sm font-medium text-gray-900">${{ number_format($payment->amount, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Método:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $payment->payment_method ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Referencia:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $payment->transaction_reference ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Información de la Suscripción -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de la Suscripción</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">ID Suscripción:</span>
                            <span class="text-sm font-medium text-gray-900">#{{ $payment->subscription->subscription_id }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Estado:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $payment->subscription->subscription_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($payment->subscription->subscription_status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Fecha de Inicio:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $payment->subscription->start_date ? $payment->subscription->start_date->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Fecha de Fin:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $payment->subscription->end_date ? $payment->subscription->end_date->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>

                    <div class="space-y-3">
                        <a href="{{ route('driver.payments') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Ver Todos los Pagos
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




