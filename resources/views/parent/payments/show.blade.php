{{--
    Archivo: resources/views/parent/payments/show.blade.php
    Roles: parent
    Rutas necesarias: Route::get('parent/payments/{payment}', [ParentPaymentController::class, 'show'])->name('payments.show')
--}}

@extends('layouts.app')

@section('title', 'Detalles del Pago')

@section('breadcrumbs')
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Inicio
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('parent.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Dashboard de Padre
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('payments.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Historial de Pagos
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Detalles del Pago</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detalles del Pago</h1>
                    <p class="mt-2 text-sm text-gray-600">Información completa del pago de transporte escolar</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Historial
                    </a>
                        @if($payment->payment_status === 'pending')
                            <a href="{{ route('payments.edit', $payment) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-credit-card mr-2"></i>
                                Realizar Pago
                            </a>
                        @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Información Principal -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Información del Pago</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Número de Factura</dt>
                                <dd class="mt-1 text-sm text-gray-900">#{{ $payment->invoice_number ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID de Pago</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->payment_id }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Monto</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900">${{ number_format($payment->amount_total, 0, ',', '.') }}</dd>
                            </div>
                            @if($payment->platform_fee > 0)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Comisión</dt>
                                <dd class="mt-1 text-sm text-gray-900">${{ number_format($payment->platform_fee, 0, ',', '.') }}</dd>
                            </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1">
                                    <x-badge
                                        type="{{ $payment->payment_status }}"
                                        text="{{ ucfirst($payment->payment_status) }}"
                                    />
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Método de Pago</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($payment->payment_method ?? 'N/A') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Pago</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>

                        @if($payment->description)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $payment->description }}</dd>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Información del Estudiante y Contrato -->
                <div class="bg-white shadow rounded-lg mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Información del Contrato</h3>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estudiante</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $payment->subscription->transportContract->student->given_name }} {{ $payment->subscription->transportContract->student->family_name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Proveedor</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->subscription->transportContract->provider->display_name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ruta de Recogida</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->subscription->transportContract->pickupRoute->route_name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ruta de Dejada</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->subscription->transportContract->dropoffRoute->route_name ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Estado del Pago -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Estado del Pago</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($payment->payment_status === 'paid')
                                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                                @elseif($payment->payment_status === 'pending')
                                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                                @elseif($payment->payment_status === 'failed')
                                    <i class="fas fa-times-circle text-2xl text-red-600"></i>
                                @else
                                    <i class="fas fa-question-circle text-2xl text-gray-600"></i>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucfirst($payment->payment_status) }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    @if($payment->payment_status === 'paid')
                                        Pago completado exitosamente
                                    @elseif($payment->payment_status === 'pending')
                                        Pago pendiente de procesamiento
                                    @elseif($payment->payment_status === 'failed')
                                        El pago no pudo ser procesado
                                    @else
                                        Estado desconocido
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Acciones</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('payments.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-list mr-2"></i>
                            Ver Historial
                        </a>

                        @if($payment->payment_status === 'pending')
                            <a href="{{ route('payments.edit', $payment) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-credit-card mr-2"></i>
                                Realizar Pago
                            </a>
                        @endif

                        <a href="{{ route('parent.contracts') }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-clipboard-list mr-2"></i>
                            Ver Contratos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
