{{--
    Archivo: resources/views/admin/payments/show.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/payments/{payment}', [PaymentController::class, 'show'])
--}}

@extends('layouts.app')

@section('title', 'Detalles del Pago - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detalles del Pago</h1>
                <p class="text-gray-600">Información completa del pago #{{ $payment->payment_id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.payments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
                <a href="{{ route('admin.payments.edit', $payment->payment_id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
            </div>
        </div>
    </div>

    <!-- Información del Pago -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detalles Principales -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Pago</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID del Pago</label>
                        <p class="mt-1 text-sm text-gray-900">#{{ $payment->payment_id }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <div class="mt-1">
                            @php
                                $statusColors = [
                                    'paid' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                    'refunded' => 'bg-blue-100 text-blue-800'
                                ];
                                $statusLabels = [
                                    'paid' => 'Pagado',
                                    'pending' => 'Pendiente',
                                    'failed' => 'Fallido',
                                    'cancelled' => 'Cancelado',
                                    'refunded' => 'Reembolsado'
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$payment->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$payment->payment_status] ?? ucfirst($payment->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Pago</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'No pagado' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Período de Inicio</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->period_start ? $payment->period_start->format('d/m/Y') : 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Período de Fin</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->period_end ? $payment->period_end->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Información Financiera -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Financiera</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700">Monto Total</label>
                        <p class="mt-1 text-2xl font-bold text-blue-600">${{ number_format($payment->amount_total, 2) }}</p>
                    </div>

                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700">Tarifa Plataforma</label>
                        <p class="mt-1 text-2xl font-bold text-green-600">${{ number_format($payment->platform_fee, 2) }}</p>
                    </div>

                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700">Monto Proveedor</label>
                        <p class="mt-1 text-2xl font-bold text-purple-600">${{ number_format($payment->provider_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Suscripción Relacionada -->
            @if($payment->subscription)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Suscripción Relacionada</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID de Suscripción</label>
                            <a href="{{ route('subscriptions.show', $payment->subscription->subscription_id) }}" class="mt-1 text-sm text-blue-600 hover:text-blue-900">
                                #{{ $payment->subscription->subscription_id }}
                            </a>
                        </div>

                        @if($payment->subscription->transportContract && $payment->subscription->transportContract->student)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estudiante</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $payment->subscription->transportContract->student->first_name }} {{ $payment->subscription->transportContract->student->last_name }}
                                </p>
                            </div>
                        @endif

                        @if($payment->subscription->transportContract && $payment->subscription->transportContract->pickupRoute)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ruta</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $payment->subscription->transportContract->pickupRoute->route_name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Acciones -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h3>
                <div class="space-y-3">
                    @if($payment->payment_status === 'pending')
                        <button onclick="processPayment({{ $payment->payment_id }})" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-check mr-2"></i>Procesar Pago
                        </button>
                    @endif

                    @if($payment->payment_status === 'paid')
                        <button onclick="refundPayment({{ $payment->payment_id }})" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-undo mr-2"></i>Reembolsar
                        </button>
                    @endif

                    @if($payment->payment_status === 'pending')
                        <button onclick="cancelPayment({{ $payment->payment_id }})" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>Cancelar Pago
                        </button>
                    @endif
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información del Sistema</h3>
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Última Actualización</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function processPayment(paymentId) {
    if (confirm('¿Está seguro de que desea procesar este pago?')) {
        fetch(`/admin/payments/${paymentId}/process`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al procesar el pago');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar el pago');
        });
    }
}

function refundPayment(paymentId) {
    if (confirm('¿Está seguro de que desea reembolsar este pago?')) {
        fetch(`/admin/payments/${paymentId}/refund`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al reembolsar el pago');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al reembolsar el pago');
        });
    }
}

function cancelPayment(paymentId) {
    if (confirm('¿Está seguro de que desea cancelar este pago?')) {
        fetch(`/admin/payments/${paymentId}/cancel`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al cancelar el pago');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cancelar el pago');
        });
    }
}
</script>
@endsection
