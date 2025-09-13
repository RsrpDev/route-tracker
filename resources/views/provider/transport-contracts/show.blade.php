{{--
    Archivo: resources/views/provider/transport-contracts/show.blade.php
    Roles: provider
    Rutas necesarias: Route::get('provider/transport-contracts/{contract}', [ProviderController::class, 'showTransportContract'])->name('provider.transport-contracts.show')
--}}

@extends('layouts.app')

@section('title', 'Detalle del Contrato - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detalle del Contrato</h1>
                <p class="text-gray-600">Información completa del contrato de transporte</p>
            </div>
            <a href="{{ route('provider.transport-contracts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                ← Volver a Contratos
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información Principal -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información del Contrato</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID del Contrato</dt>
                            <dd class="mt-1 text-sm text-gray-900">#{{ $contract->contract_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $contract->contract_status === 'active' ? 'bg-green-100 text-green-800' :
                                       ($contract->contract_status === 'suspended' ? 'bg-yellow-100 text-yellow-800' :
                                       ($contract->contract_status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($contract->contract_status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contract->contract_start_date->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $contract->contract_end_date ? $contract->contract_end_date->format('d/m/Y') : 'Sin fecha de fin' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tarifa Mensual</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">${{ number_format($contract->monthly_fee, 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contract->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>

                    @if($contract->special_instructions)
                        <div class="mt-6">
                            <dt class="text-sm font-medium text-gray-500">Instrucciones Especiales</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-md">{{ $contract->special_instructions }}</dd>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información del Estudiante -->
            <div class="bg-white shadow-md rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información del Estudiante</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contract->student->given_name }} {{ $contract->student->family_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Grado</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contract->student->grade ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Escuela</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contract->student->school->legal_name ?? 'Sin escuela' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Turno</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($contract->student->shift) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contract->student->phone_number ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $contract->student->address ?? 'No especificada' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Información de Rutas -->
            @if($contract->pickupRoute || $contract->dropoffRoute)
                <div class="bg-white shadow-md rounded-lg mt-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Información de Rutas</h3>
                    </div>
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            @if($contract->pickupRoute)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Ruta de Recogida</h4>
                                    <div class="bg-green-50 p-3 rounded-md">
                                        <p class="text-sm font-medium text-gray-900">{{ $contract->pickupRoute->route_name }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $contract->pickupRoute->origin_address }} → {{ $contract->pickupRoute->destination_address }}</p>
                                        @if($contract->pickupRoute->pickup_time)
                                            <p class="text-xs text-gray-600 mt-1">Hora: {{ $contract->pickupRoute->pickup_time }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($contract->dropoffRoute)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Ruta de Entrega</h4>
                                    <div class="bg-blue-50 p-3 rounded-md">
                                        <p class="text-sm font-medium text-gray-900">{{ $contract->dropoffRoute->route_name }}</p>
                                        <p class="text-xs text-gray-600 mt-1">{{ $contract->dropoffRoute->origin_address }} → {{ $contract->dropoffRoute->destination_address }}</p>
                                        @if($contract->dropoffRoute->dropoff_time)
                                            <p class="text-xs text-gray-600 mt-1">Hora: {{ $contract->dropoffRoute->dropoff_time }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Información de Suscripción -->
            @if($contract->subscription)
                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Suscripción</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $contract->subscription->subscription_status === 'active' ? 'bg-green-100 text-green-800' :
                                           ($contract->subscription->subscription_status === 'paused' ? 'bg-yellow-100 text-yellow-800' :
                                           ($contract->subscription->subscription_status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($contract->subscription->subscription_status) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ciclo de Facturación</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($contract->subscription->billing_cycle) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Próxima Facturación</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $contract->subscription->next_billing_date->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Comisión de Plataforma</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $contract->subscription->platform_fee_rate }}%</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif

            <!-- Información del Padre -->
            @if($contract->student->parentProfile)
                <div class="bg-white shadow-md rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Información del Padre</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $contract->student->parentProfile->account->full_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $contract->student->parentProfile->account->email ?? 'No especificado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $contract->student->parentProfile->account->phone_number ?? 'No especificado' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection







