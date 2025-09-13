{{--
    Archivo: resources/views/provider/transport-contracts/index.blade.php
    Roles: provider
    Rutas necesarias: Route::get('provider/transport-contracts', [ProviderController::class, 'transportContracts'])->name('provider.transport-contracts.index')
--}}

@extends('layouts.app')

@section('title', 'Mis Contratos de Transporte - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mis Contratos de Transporte</h1>
                <p class="text-gray-600">Gestiona los contratos de transporte de tus estudiantes</p>
            </div>
            <a href="{{ route('provider.driver.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                ← Volver al Dashboard
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">👨‍🎓</div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Estudiantes</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $contracts->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">✅</div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Contratos Activos</dt>
                            <dd class="text-lg font-medium text-green-600">{{ $contracts->where('contract_status', 'active')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">⏸️</div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Contratos Suspendidos</dt>
                            <dd class="text-lg font-medium text-yellow-600">{{ $contracts->where('contract_status', 'suspended')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="text-2xl">💰</div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ingresos Mensuales</dt>
                            <dd class="text-lg font-medium text-blue-600">${{ number_format($contracts->where('contract_status', 'active')->sum('monthly_fee'), 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Contratos -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Lista de Contratos</h3>
        </div>

        @if($contracts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Escuela</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ruta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarifa Mensual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($contracts as $contract)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">{{ substr($contract->student->given_name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $contract->student->given_name }} {{ $contract->student->family_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $contract->student->grade }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $contract->student->school->legal_name ?? 'Sin escuela' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($contract->pickupRoute)
                                            <div>{{ $contract->pickupRoute->route_name }}</div>
                                            <div class="text-xs text-gray-500">{{ $contract->pickupRoute->origin_address }} → {{ $contract->pickupRoute->destination_address }}</div>
                                        @else
                                            <span class="text-gray-400">Sin ruta asignada</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $contract->contract_status === 'active' ? 'bg-green-100 text-green-800' :
                                           ($contract->contract_status === 'suspended' ? 'bg-yellow-100 text-yellow-800' :
                                           ($contract->contract_status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                        {{ ucfirst($contract->contract_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($contract->monthly_fee, 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $contract->contract_start_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('provider.transport-contracts.show', $contract) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $contracts->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <div class="text-6xl mb-4">📋</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay contratos de transporte</h3>
                <p class="text-gray-500">Aún no tienes contratos de transporte registrados.</p>
            </div>
        @endif
    </div>
</div>
@endsection







