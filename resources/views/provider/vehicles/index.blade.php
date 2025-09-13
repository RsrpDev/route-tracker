@extends('layouts.app')

@section('title', 'Mis Vehículos - Conductor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Mis Vehículos</h2>
                <p class="text-gray-600">Gestiona tus vehículos de transporte</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('driver.vehicles.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Agregar Vehículo
                </a>
                <a href="{{ route('driver.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Vehículos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $vehicles->total() }}</p>
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
                    <p class="text-sm font-medium text-gray-500">Vehículos Activos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $vehicles->where('vehicle_status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">En Mantenimiento</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $vehicles->where('vehicle_status', 'maintenance')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Documentos Vencidos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $vehicles->filter(function($v) { return $v->soat_expiration && $v->soat_expiration->isPast(); })->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Vehículos -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Lista de Vehículos</h3>
                </div>

        @if($vehicles->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehículo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especificaciones</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documentos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($vehicles as $vehicle)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $vehicle->brand }} {{ $vehicle->model_year }}</div>
                                        <div class="text-sm text-gray-500">Placa: {{ $vehicle->plate }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">Capacidad: {{ $vehicle->capacity }} pasajeros</div>
                                <div class="text-sm text-gray-500">{{ $vehicle->fuel_type }} • {{ $vehicle->vehicle_class }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col space-y-1">
                                    @if($vehicle->soat_expiration)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $vehicle->soat_expiration->isPast() ? 'bg-red-100 text-red-800' :
                                               ($vehicle->soat_expiration->diffInDays(now()) <= 30 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            SOAT {{ $vehicle->soat_expiration->isPast() ? 'Vencido' : 'Válido' }}
                                        </span>
                                    @endif
                                    @if($vehicle->technical_inspection_expiration)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $vehicle->technical_inspection_expiration->isPast() ? 'bg-red-100 text-red-800' :
                                               ($vehicle->technical_inspection_expiration->diffInDays(now()) <= 30 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            Revisión {{ $vehicle->technical_inspection_expiration->isPast() ? 'Vencida' : 'Válida' }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $vehicle->vehicle_status === 'active' ? 'bg-green-100 text-green-800' :
                                       ($vehicle->vehicle_status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' :
                                        ($vehicle->vehicle_status === 'inactive' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ ucfirst($vehicle->vehicle_status) }}
                                        </span>
                                    </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('driver.vehicles.show', $vehicle) }}" class="text-blue-600 hover:text-blue-900">
                                        Ver
                                    </a>
                                    <a href="{{ route('driver.vehicles.edit', $vehicle) }}" class="text-green-600 hover:text-green-900">
                                        Editar
                                    </a>
                                </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-200">
                        {{ $vehicles->links() }}
                    </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-1.447-.894L15 4m0 13V4m-6 3l6-3"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay vehículos registrados</h3>
                <p class="mt-1 text-sm text-gray-500">Comienza agregando tu primer vehículo al sistema.</p>
                </div>
        @endif
    </div>
</div>
@endsection

