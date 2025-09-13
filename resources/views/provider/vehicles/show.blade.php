@extends('layouts.app')

@section('title', 'Detalle del Vehículo - Conductor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model_year }}</h2>
                <p class="text-gray-600">Placa: {{ $vehicle->plate }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('driver.vehicles.edit', $vehicle) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Editar Vehículo
                </a>
                <a href="{{ route('driver.vehicles') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver a Vehículos
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Información del Vehículo -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Información del Vehículo</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">ID del Vehículo</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->vehicle_id }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Placa</p>
                            <p class="mt-1 text-lg text-gray-900 font-mono">{{ $vehicle->plate }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Marca</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->brand }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Año del Modelo</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->model_year }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Color</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->color ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tipo de Combustible</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->fuel_type ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Capacidad</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->capacity }} pasajeros</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Clase de Vehículo</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->vehicle_class ?? 'No especificada' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Especificaciones Técnicas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Especificaciones Técnicas</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Número de Serie</p>
                            <p class="mt-1 text-lg text-gray-900 font-mono">{{ $vehicle->serial_number ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Número de Motor</p>
                            <p class="mt-1 text-lg text-gray-900 font-mono">{{ $vehicle->engine_number ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Número de Chasis</p>
                            <p class="mt-1 text-lg text-gray-900 font-mono">{{ $vehicle->chassis_number ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Cilindrada</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->cylinder_capacity ? $vehicle->cylinder_capacity . ' cc' : 'No especificada' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tipo de Servicio</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->service_type ?? 'No especificado' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Lectura del Odómetro</p>
                            <p class="mt-1 text-lg text-gray-900">{{ $vehicle->odometer_reading ? number_format($vehicle->odometer_reading) . ' km' : 'No registrada' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rutas Asignadas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Rutas Asignadas</h3>

                @if($vehicle->routeAssignments && $vehicle->routeAssignments->count() > 0)
                    <div class="space-y-4">
                        @foreach($vehicle->routeAssignments as $assignment)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900">{{ $assignment->route->route_name ?? 'Ruta sin nombre' }}</h4>
                                        <p class="text-sm text-gray-600">
                                            {{ $assignment->route->origin ?? 'Origen no especificado' }} →
                                            {{ $assignment->route->destination ?? 'Destino no especificado' }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $assignment->assignment_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($assignment->assignment_status ?? 'inactive') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay rutas asignadas</h3>
                        <p class="mt-1 text-sm text-gray-500">Este vehículo no tiene rutas asignadas actualmente.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-8">
            <!-- Estado del Vehículo -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado del Vehículo</h3>

                <div class="text-center mb-6">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center
                        {{ $vehicle->vehicle_status === 'active' ? 'bg-green-100' :
                           ($vehicle->vehicle_status === 'maintenance' ? 'bg-yellow-100' : 'bg-red-100') }}">
                        @if($vehicle->vehicle_status === 'active')
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($vehicle->vehicle_status === 'maintenance')
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>

                    <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ ucfirst($vehicle->vehicle_status) }}</h4>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $vehicle->vehicle_status === 'active' ? 'bg-green-100 text-green-800' :
                           ($vehicle->vehicle_status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($vehicle->vehicle_status) }}
                    </span>
                </div>
            </div>

            <!-- Documentos y Seguros -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Documentos y Seguros</h3>

                <div class="space-y-4">
                    <!-- SOAT -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-900">SOAT</h4>
                            @if($vehicle->soat_expiration)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $vehicle->soat_expiration->isPast() ? 'bg-red-100 text-red-800' :
                                       ($vehicle->soat_expiration->diffInDays(now()) <= 30 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $vehicle->soat_expiration->isPast() ? 'Vencido' : 'Válido' }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">Número: {{ $vehicle->soat_number ?? 'No especificado' }}</p>
                        <p class="text-sm text-gray-600">Vence: {{ $vehicle->soat_expiration ? $vehicle->soat_expiration->format('d/m/Y') : 'No especificado' }}</p>
                    </div>

                    <!-- Seguro -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-900">Seguro</h4>
                            @if($vehicle->insurance_expiration)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $vehicle->insurance_expiration->isPast() ? 'bg-red-100 text-red-800' :
                                       ($vehicle->insurance_expiration->diffInDays(now()) <= 30 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $vehicle->insurance_expiration->isPast() ? 'Vencido' : 'Válido' }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">Compañía: {{ $vehicle->insurance_company ?? 'No especificada' }}</p>
                        <p class="text-sm text-gray-600">Póliza: {{ $vehicle->insurance_policy_number ?? 'No especificada' }}</p>
                        <p class="text-sm text-gray-600">Vence: {{ $vehicle->insurance_expiration ? $vehicle->insurance_expiration->format('d/m/Y') : 'No especificado' }}</p>
                    </div>

                    <!-- Revisión Técnica -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-900">Revisión Técnica</h4>
                            @if($vehicle->technical_inspection_expiration)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $vehicle->technical_inspection_expiration->isPast() ? 'bg-red-100 text-red-800' :
                                       ($vehicle->technical_inspection_expiration->diffInDays(now()) <= 30 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $vehicle->technical_inspection_expiration->isPast() ? 'Vencida' : 'Válida' }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">Vence: {{ $vehicle->technical_inspection_expiration ? $vehicle->technical_inspection_expiration->format('d/m/Y') : 'No especificado' }}</p>
                    </div>

                    <!-- Revisión -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-900">Revisión</h4>
                            @if($vehicle->revision_expiration)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $vehicle->revision_expiration->isPast() ? 'bg-red-100 text-red-800' :
                                       ($vehicle->revision_expiration->diffInDays(now()) <= 30 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $vehicle->revision_expiration->isPast() ? 'Vencida' : 'Válida' }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600">Vence: {{ $vehicle->revision_expiration ? $vehicle->revision_expiration->format('d/m/Y') : 'No especificado' }}</p>
                    </div>
                </div>
            </div>

            <!-- Mantenimiento -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Mantenimiento</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Último Mantenimiento</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $vehicle->last_maintenance_date ? $vehicle->last_maintenance_date->format('d/m/Y') : 'No registrado' }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Próximo Mantenimiento</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $vehicle->next_maintenance_date ? $vehicle->next_maintenance_date->format('d/m/Y') : 'No programado' }}</p>

                        @if($vehicle->next_maintenance_date)
                            @php
                                $daysUntilMaintenance = $vehicle->next_maintenance_date->diffInDays(now());
                                $isMaintenanceDue = $vehicle->next_maintenance_date->isPast();
                                $isMaintenanceSoon = $daysUntilMaintenance <= 7 && !$isMaintenanceDue;
                            @endphp

                            @if($isMaintenanceDue)
                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-md">
                                    <p class="text-xs text-red-800">⚠️ Mantenimiento vencido</p>
                                </div>
                            @elseif($isMaintenanceSoon)
                                <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-md">
                                    <p class="text-xs text-yellow-800">⚠️ Mantenimiento en {{ $daysUntilMaintenance }} días</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
