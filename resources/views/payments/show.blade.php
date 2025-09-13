@extends('layouts.app')

@section('title', 'Detalle del Pago')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detalle del Pago</h1>
                <p class="text-gray-600 mt-2">Información completa del pago realizado</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
                @if($payment->subscription && $payment->subscription->transportContract)
                    <a href="{{ route('parent.subscriptions.payments', $payment->subscription->subscription_id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-list mr-2"></i>Ver Todos los Pagos
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Información del Pago -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detalles del Pago -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Información del Pago</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información Básica -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID del Pago</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">#{{ $payment->payment_id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado del Pago</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($payment->payment_status === 'paid') bg-green-100 text-green-800
                                @elseif($payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($payment->payment_status === 'failed') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($payment->payment_status === 'paid')
                                    <i class="fas fa-check-circle mr-2"></i>Pagado
                                @elseif($payment->payment_status === 'pending')
                                    <i class="fas fa-clock mr-2"></i>Pendiente
                                @elseif($payment->payment_status === 'failed')
                                    <i class="fas fa-times-circle mr-2"></i>Fallido
                                @else
                                    <i class="fas fa-question-circle mr-2"></i>{{ ucfirst($payment->payment_status) }}
                                @endif
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                            <p class="mt-1 text-lg text-gray-900">
                                @if($payment->payment_method === 'pse')
                                    <i class="fas fa-university mr-2"></i>PSE
                                @elseif($payment->payment_method === 'credit_card')
                                    <i class="fas fa-credit-card mr-2"></i>Tarjeta de Crédito
                                @elseif($payment->payment_method === 'debit_card')
                                    <i class="fas fa-credit-card mr-2"></i>Tarjeta Débito
                                @elseif($payment->payment_method === 'bank_transfer')
                                    <i class="fas fa-exchange-alt mr-2"></i>Transferencia Bancaria
                                @else
                                    <i class="fas fa-money-bill-wave mr-2"></i>{{ ucfirst($payment->payment_method) }}
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                            <p class="mt-1 text-lg text-gray-900">
                                <i class="fas fa-calendar mr-2"></i>
                                {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'No pagado' }}
                            </p>
                        </div>
                    </div>

                    <!-- Información Financiera -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Período de Facturación</label>
                            <p class="mt-1 text-lg text-gray-900">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ \Carbon\Carbon::parse($payment->period_start)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($payment->period_end)->format('d/m/Y') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                            <p class="mt-1 text-lg text-gray-900">
                                <i class="fas fa-plus-circle mr-2"></i>
                                {{ $payment->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Última Actualización</label>
                            <p class="mt-1 text-lg text-gray-900">
                                <i class="fas fa-edit mr-2"></i>
                                {{ $payment->updated_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen Financiero -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Resumen Financiero</h2>

                <div class="space-y-4">
                    <!-- Monto Total -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-blue-700">Monto Total</span>
                            <span class="text-xl font-bold text-blue-900">${{ number_format($payment->amount_total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Comisión de Plataforma -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">Comisión Plataforma</span>
                            <span class="text-lg font-semibold text-gray-900">${{ number_format($payment->platform_fee, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            {{ $payment->platform_fee_rate }}% del monto total
                        </div>
                    </div>

                    <!-- Monto Proveedor -->
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-green-700">Monto Proveedor</span>
                            <span class="text-lg font-semibold text-green-900">${{ number_format($payment->provider_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Contrato -->
    @if($payment->subscription && $payment->subscription->transportContract)
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Información del Contrato</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Información del Estudiante -->
                    @if($payment->subscription->transportContract->student)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estudiante</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $payment->subscription->transportContract->student->first_name }}
                                {{ $payment->subscription->transportContract->student->last_name }}
                            </p>
                            @if($payment->subscription->transportContract->student->school)
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-school mr-1"></i>
                                    {{ $payment->subscription->transportContract->student->school->school_name }}
                                </p>
                            @endif
                        </div>
                    @endif

                    <!-- Información del Proveedor -->
                    @if($payment->subscription->transportContract->provider)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proveedor de Transporte</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $payment->subscription->transportContract->provider->business_name }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-building mr-1"></i>
                                {{ ucfirst($payment->subscription->transportContract->provider->provider_type) }}
                            </p>
                        </div>
                    @endif

                    <!-- Información de la Suscripción -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Suscripción</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            Plan {{ ucfirst($payment->subscription->payment_plan_type) }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-calendar-check mr-1"></i>
                            Estado: {{ ucfirst($payment->subscription->subscription_status) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Información del Padre -->
    @if($payment->subscription && $payment->subscription->transportContract && $payment->subscription->transportContract->student && $payment->subscription->transportContract->student->parentProfile)
        <div class="mt-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Información del Padre</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                        <p class="mt-1 text-lg font-semibold text-gray-900">
                            {{ $payment->subscription->transportContract->student->parentProfile->first_name }}
                            {{ $payment->subscription->transportContract->student->parentProfile->last_name }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-lg text-gray-900">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $payment->subscription->transportContract->student->parentProfile->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

