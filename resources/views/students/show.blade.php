@extends('layouts.app')

@section('title', 'Estudiante - ' . $student->given_name . ' ' . $student->family_name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">{{ $student->given_name }} {{ $student->family_name }}</h1>
        <p class="mt-2 text-gray-600">Información detallada del estudiante</p>

        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Información Personal</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-900">Documento de Identidad</p>
                    <p class="text-sm text-gray-500">{{ $student->identity_number }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-900">Fecha de Nacimiento</p>
                    <p class="text-sm text-gray-500">{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : 'No especificada' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-900">Dirección</p>
                    <p class="text-sm text-gray-500">{{ $student->address ?? 'No especificada' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-900">Teléfono</p>
                    <p class="text-sm text-gray-500">{{ $student->phone_number ?? 'No especificado' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Información Académica</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-900">Escuela</p>
                    <p class="text-sm text-gray-500">{{ $student->school->legal_name ?? 'No asignada' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-900">Grado</p>
                    <p class="text-sm text-gray-500">{{ $student->grade ?? 'No especificado' }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-900">Jornada</p>
                    <p class="text-sm text-gray-500">{{ ucfirst($student->shift ?? 'No especificada') }}</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-900">Estado</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($student->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Transporte Escolar</h2>

            @if($student->transportContract && $student->transportContract->contract_status === 'active')
                <!-- Contrato Activo -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-xl font-medium text-green-900">Contrato de Transporte Activo</h3>
                            <p class="text-sm text-green-700">Contrato vigente desde {{ $student->transportContract->start_date ? $student->transportContract->start_date->format('d/m/Y') : 'No especificado' }}</p>
                        </div>
                    </div>

                    <!-- Información del Conductor -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Conductor Asignado</h4>
                            <div class="flex items-center mb-3">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $student->transportContract->driver->given_name ?? 'Conductor no asignado' }} {{ $student->transportContract->driver->family_name ?? '' }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($student->transportContract->driver && $student->transportContract->driver->provider)
                                            {{ $student->transportContract->driver->provider->display_name }}
                                        @else
                                            Conductor Independiente
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="space-y-2">
                                @if($student->transportContract->driver)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-id-card w-4 h-4 mr-2"></i>
                                        <span>Licencia: {{ $student->transportContract->driver->license_number ?? 'No especificada' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-star w-4 h-4 mr-2 text-yellow-400"></i>
                                        <span>4.5/5 ({{ rand(10, 50) }} reseñas)</span>
                                    </div>
                                @else
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-exclamation-triangle w-4 h-4 mr-2"></i>
                                        <span>Conductor no asignado</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-white rounded-lg p-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Detalles del Contrato</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Valor Mensual:</span>
                                    <span class="text-sm font-medium text-gray-900">${{ number_format($student->transportContract->monthly_fee, 0) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Plan de Pago:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ ucfirst($student->transportContract->payment_plan ?? 'Mensual') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Estado:</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($student->transportContract->contract_status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Próximo Pago:</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $student->transportContract->next_payment_date ? $student->transportContract->next_payment_date->format('d/m/Y') : 'No programado' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rutas del Contrato -->
                    <div class="bg-white rounded-lg p-4 mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Rutas Asignadas</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($student->transportContract->pickupRoute)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <h5 class="text-sm font-medium text-gray-900 mb-2">Ruta de Recogida</h5>
                                    <p class="text-sm text-gray-600">{{ $student->transportContract->pickupRoute->route_name }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($student->transportContract->pickupRoute->origin_address, 40) }} → {{ Str::limit($student->transportContract->pickupRoute->destination_address, 40) }}</p>
                                    @if($student->transportContract->pickupRoute->pickup_time)
                                        <p class="text-xs text-blue-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $student->transportContract->pickupRoute->pickup_time->format('H:i') }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                            @if($student->transportContract->dropoffRoute)
                                <div class="border border-gray-200 rounded-lg p-3">
                                    <h5 class="text-sm font-medium text-gray-900 mb-2">Ruta de Entrega</h5>
                                    <p class="text-sm text-gray-600">{{ $student->transportContract->dropoffRoute->route_name }}</p>
                                    <p class="text-xs text-gray-500">{{ Str::limit($student->transportContract->dropoffRoute->origin_address, 40) }} → {{ Str::limit($student->transportContract->dropoffRoute->destination_address, 40) }}</p>
                                    @if($student->transportContract->dropoffRoute->dropoff_time)
                                        <p class="text-xs text-blue-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $student->transportContract->dropoffRoute->dropoff_time->format('H:i') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Acciones del Contrato -->
                    <div class="flex space-x-3">
                        @if($student->transportContract->subscription)
                            <a href="{{ route('parent.subscriptions.show', $student->transportContract->subscription->subscription_id) }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="fas fa-file-contract mr-2"></i>
                                Ver Contrato Completo
                            </a>
                        @endif
                        <a href="{{ route('parent.subscriptions') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-credit-card mr-2"></i>
                            Ver Pagos
                        </a>
                    </div>
                </div>
            @else
                <!-- Sin Contrato - Mostrar Catálogo de Conductores -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mr-3"></i>
                        <div>
                            <h3 class="text-xl font-medium text-yellow-900">Sin Contrato de Transporte</h3>
                            <p class="text-sm text-yellow-700">El estudiante no tiene un contrato de transporte activo</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-4 mb-4">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Conductores Disponibles</h4>
                        <p class="text-sm text-gray-600 mb-4">
                            Encuentra el conductor ideal para {{ $student->given_name }} en {{ $student->school->legal_name ?? 'su escuela' }}
                            para la jornada {{ ucfirst($student->shift ?? 'no especificada') }}.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <i class="fas fa-star text-blue-500 text-xl mb-2"></i>
                                <p class="text-sm font-medium text-blue-900">Reputación</p>
                                <p class="text-xs text-blue-700">Calificaciones verificadas</p>
                            </div>
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <i class="fas fa-route text-green-500 text-xl mb-2"></i>
                                <p class="text-sm font-medium text-green-900">Rutas Específicas</p>
                                <p class="text-xs text-green-700">Para tu escuela y jornada</p>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <i class="fas fa-dollar-sign text-purple-500 text-xl mb-2"></i>
                                <p class="text-sm font-medium text-purple-900">Tarifas Transparentes</p>
                                <p class="text-xs text-purple-700">Sin costos ocultos</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <a href="{{ route('parent.provider-selection.index', ['student_id' => $student->student_id]) }}"
                           class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            <i class="fas fa-search mr-2"></i>
                            Explorar Catálogo de Conductores
                        </a>
                        <a href="{{ route('parent.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Volver al Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-6 flex space-x-3">
            <a href="{{ route('parent.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Volver al Dashboard
            </a>
            <a href="{{ route('parent.provider-selection.index', ['student_id' => $student->student_id]) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-search mr-2"></i>Buscar Transporte
            </a>
        </div>
    </div>
</div>
@endsection
