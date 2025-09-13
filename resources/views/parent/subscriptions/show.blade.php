@extends('layouts.app')

@section('title', 'Detalles de Contrato')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detalles de Contrato</h1>
                <p class="text-gray-600 mt-2">Información completa del plan de pago y contrato de transporte</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('parent.contracts') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    ← Volver a Contratos
                </a>
                <a href="{{ route('students.show', $contract->transportContract->student_id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    Ver Estudiante
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Estado del Contrato -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Estado del Contrato</h2>
                    <span class="px-3 py-1 rounded-full text-sm font-medium
                        @if($contract->subscription_status === 'active') bg-green-100 text-green-800
                        @elseif($contract->subscription_status === 'paused') bg-yellow-100 text-yellow-800
                        @elseif($contract->subscription_status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($contract->subscription_status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">ID de Contrato</label>
                        <p class="text-lg font-semibold text-gray-900">#{{ $contract->subscription_id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Plan de Pago</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $contract->plan_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Ciclo de Facturación</label>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($contract->billing_cycle) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Renovación Automática</label>
                        <p class="text-lg font-semibold text-gray-900">
                            @if($contract->auto_renewal)
                                <span class="text-green-600">✓ Activada</span>
                            @else
                                <span class="text-red-600">✗ Desactivada</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información de Pago -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Información de Pago</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Precio Base</label>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($contract->price_snapshot, 0, ',', '.') }}</p>
                    </div>
                    @if($contract->discount_rate > 0)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Descuento ({{ $contract->discount_rate }}%)</label>
                        <p class="text-lg font-semibold text-green-600">-${{ number_format($contract->savings, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Precio Final</label>
                        <p class="text-2xl font-bold text-green-600">${{ number_format($contract->discounted_price, 0, ',', '.') }}</p>
                    </div>
                    @endif
                    <div>
                        <label class="text-sm font-medium text-gray-500">Método de Pago</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $contract->payment_method_text }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Próximo Pago</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $contract->next_billing_date ? $contract->next_billing_date->format('d/m/Y') : 'No programado' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información del Contrato -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Contrato</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">ID del Contrato</label>
                        <p class="text-lg font-semibold text-gray-900">#{{ $contract->transportContract->contract_id }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Estado del Contrato</label>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($contract->transportContract->contract_status) }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Fecha de Inicio</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $contract->transportContract->start_date ? $contract->transportContract->start_date->format('d/m/Y') : 'No definida' }}
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Fecha de Fin</label>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ $contract->transportContract->end_date ? $contract->transportContract->end_date->format('d/m/Y') : 'Sin fecha límite' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Información del Conductor -->
            @if($contract->transportContract->driver)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Conductor Asignado</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Nombre del Conductor</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $contract->transportContract->driver->first_name }} {{ $contract->transportContract->driver->last_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Licencia de Conducir</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $contract->transportContract->driver->license_number }}</p>
                    </div>
                    @if($contract->transportContract->driver->provider)
                    <div>
                        <label class="text-sm font-medium text-gray-500">Proveedor</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $contract->transportContract->driver->provider->business_name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Tipo de Proveedor</label>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($contract->transportContract->driver->provider->provider_type) }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Rutas Asignadas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Rutas Asignadas</h2>

                <div class="space-y-4">
                    @if($contract->transportContract->pickupRoute)
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h3 class="font-semibold text-gray-900">Ruta de Recogida</h3>
                        <p class="text-gray-600">{{ $contract->transportContract->pickupRoute->route_name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $contract->transportContract->pickupRoute->origin_address }} →
                            {{ $contract->transportContract->pickupRoute->destination_address }}
                        </p>
                        @if($contract->transportContract->pickupRoute->pickup_time)
                        <p class="text-sm text-gray-500">
                            Hora de recogida: {{ $contract->transportContract->pickupRoute->pickup_time }}
                        </p>
                        @endif
                    </div>
                    @endif

                    @if($contract->transportContract->dropoffRoute)
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="font-semibold text-gray-900">Ruta de Entrega</h3>
                        <p class="text-gray-600">{{ $contract->transportContract->dropoffRoute->route_name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $contract->transportContract->dropoffRoute->origin_address }} →
                            {{ $contract->transportContract->dropoffRoute->destination_address }}
                        </p>
                        @if($contract->transportContract->dropoffRoute->dropoff_time)
                        <p class="text-sm text-gray-500">
                            Hora de entrega: {{ $contract->transportContract->dropoffRoute->dropoff_time }}
                        </p>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Resumen de Pago -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen de Pago</h3>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Precio Base</span>
                        <span class="font-semibold">${{ number_format($contract->price_snapshot, 0, ',', '.') }}</span>
                    </div>

                    @if($contract->discount_rate > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Descuento ({{ $contract->discount_rate }}%)</span>
                        <span class="font-semibold">-${{ number_format($contract->savings, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <hr class="my-2">

                    <div class="flex justify-between text-lg font-bold">
                        <span>Total a Pagar</span>
                        <span class="text-green-600">${{ number_format($contract->discounted_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>

                <div class="space-y-3">
                    <a href="{{ route('parent.contracts.payments', $contract->subscription_id) }}" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors text-center block">
                        Ver Historial de Pagos
                    </a>

                    @if($contract->auto_renewal)
                    <button class="w-full bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors">
                        Pausar Renovación
                    </button>
                    @else
                    <button class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                        Activar Renovación
                    </button>
                    @endif

                    <button class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors">
                        Cancelar Suscripción
                    </button>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Contacto</h3>

                <div class="space-y-2">
                    <p class="text-sm text-gray-600">
                        <strong>Estudiante:</strong> {{ $contract->transportContract->student->first_name }} {{ $contract->transportContract->student->last_name }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <strong>Colegio:</strong> {{ $contract->transportContract->student->school->school_name }}
                    </p>
                    @if($contract->transportContract->driver && $contract->transportContract->driver->provider)
                    <p class="text-sm text-gray-600">
                        <strong>Proveedor:</strong> {{ $contract->transportContract->driver->provider->business_name }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
