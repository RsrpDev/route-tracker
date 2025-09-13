@extends('layouts.app')

@section('title', 'Detalle del Contrato de Transporte - Escuela')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detalle del Contrato de Transporte</h1>
                    <p class="mt-2 text-gray-600">Información completa del contrato de transporte del estudiante</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('school.transport-contracts.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Volver a Contratos
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información del Contrato -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Información del Contrato</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Detalles Generales</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado del Contrato</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $transportContract->contract_status === 'active' ? 'bg-green-100 text-green-800' :
                                               ($transportContract->contract_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                               ($transportContract->contract_status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($transportContract->contract_status) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transportContract->contract_start_date->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $transportContract->contract_end_date ? $transportContract->contract_end_date->format('d/m/Y') : 'Sin fecha de fin' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tarifa Mensual</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-semibold">${{ number_format($transportContract->monthly_fee, 2) }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Rutas</h3>
                            <dl class="space-y-3">
                                @if($transportContract->pickupRoute)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ruta de Recogida</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transportContract->pickupRoute->route_name }}</dd>
                                    <dd class="text-xs text-gray-500">{{ $transportContract->pickupRoute->origin_address }} → {{ $transportContract->pickupRoute->destination_address }}</dd>
                                </div>
                                @endif
                                @if($transportContract->dropoffRoute && $transportContract->dropoffRoute->route_id !== $transportContract->pickupRoute->route_id)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ruta de Entrega</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transportContract->dropoffRoute->route_name }}</dd>
                                    <dd class="text-xs text-gray-500">{{ $transportContract->dropoffRoute->origin_address }} → {{ $transportContract->dropoffRoute->destination_address }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    @if($transportContract->special_instructions)
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Instrucciones Especiales</h3>
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-md">{{ $transportContract->special_instructions }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Información del Estudiante y Proveedor -->
            <div class="space-y-6">
                <!-- Información del Estudiante -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Estudiante</h2>

                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center">
                                <span class="text-lg font-medium text-white">
                                    {{ substr($transportContract->student->given_name, 0, 1) }}{{ substr($transportContract->student->family_name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $transportContract->student->given_name }} {{ $transportContract->student->family_name }}
                            </h3>
                            <p class="text-sm text-gray-500">Grado {{ $transportContract->student->grade }}</p>
                        </div>
                    </div>

                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Escuela</dt>
                            <dd class="text-sm text-gray-900">{{ $transportContract->student->school->legal_name ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                            <dd class="text-sm text-gray-900">{{ $transportContract->student->address ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="text-sm text-gray-900">{{ $transportContract->student->phone_number ?? 'No especificado' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4">
                        <a href="{{ route('school.students.show', $transportContract->student) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>Ver perfil completo
                        </a>
                    </div>
                </div>

                <!-- Información del Proveedor -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Proveedor</h2>

                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-full bg-green-500 flex items-center justify-center">
                                <i class="fas fa-truck text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">{{ $transportContract->provider->display_name }}</h3>
                            <p class="text-sm text-gray-500">{{ ucfirst($transportContract->provider->provider_type) }}</p>
                        </div>
                    </div>

                    <dl class="space-y-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email de Contacto</dt>
                            <dd class="text-sm text-gray-900">{{ $transportContract->provider->contact_email ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono de Contacto</dt>
                            <dd class="text-sm text-gray-900">{{ $transportContract->provider->contact_phone ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $transportContract->provider->provider_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($transportContract->provider->provider_status) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Información de Suscripción -->
                @if($transportContract->subscription)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información de Suscripción</h2>

                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado de Suscripción</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $transportContract->subscription->subscription_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($transportContract->subscription->subscription_status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ciclo de Facturación</dt>
                            <dd class="text-sm text-gray-900">{{ ucfirst($transportContract->subscription->billing_cycle) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Próxima Facturación</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $transportContract->subscription->next_billing_date ? $transportContract->subscription->next_billing_date->format('d/m/Y') : 'No definida' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tarifa Actual</dt>
                            <dd class="text-sm text-gray-900 font-semibold">${{ number_format($transportContract->subscription->price_snapshot, 2) }}</dd>
                        </div>
                    </dl>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

