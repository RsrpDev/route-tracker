@extends('layouts.app')

@section('title', 'Contratos de Transporte - Escuela')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Contratos de Transporte</h1>
                    <p class="mt-2 text-gray-600">Gestiona los contratos de transporte de los estudiantes de tu escuela</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('school.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todos los estados</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>
                <div>
                    <label for="provider" class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                    <select name="provider" id="provider" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todos los proveedores</option>
                        @foreach($transportContracts->pluck('provider.display_name')->unique() as $providerName)
                            <option value="{{ $providerName }}" {{ request('provider') === $providerName ? 'selected' : '' }}>
                                {{ $providerName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-1">Grado</label>
                    <select name="grade" id="grade" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todos los grados</option>
                        @foreach($transportContracts->pluck('student.grade')->unique()->sort() as $grade)
                            <option value="{{ $grade }}" {{ request('grade') === $grade ? 'selected' : '' }}>
                                Grado {{ $grade }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de contratos -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($transportContracts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rutas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarifa Mensual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transportContracts as $contract)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">
                                                    {{ substr($contract->student->given_name, 0, 1) }}{{ substr($contract->student->family_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $contract->student->given_name }} {{ $contract->student->family_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">Grado {{ $contract->student->grade }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $contract->provider->display_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $contract->provider->provider_type }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($contract->pickupRoute)
                                            <div>Recogida: {{ $contract->pickupRoute->route_name }}</div>
                                        @endif
                                        @if($contract->dropoffRoute && $contract->dropoffRoute->route_id !== $contract->pickupRoute->route_id)
                                            <div>Entrega: {{ $contract->dropoffRoute->route_name }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $contract->contract_status === 'active' ? 'bg-green-100 text-green-800' :
                                           ($contract->contract_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           ($contract->contract_status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($contract->contract_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($contract->monthly_fee, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $contract->contract_start_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('school.transport-contracts.show', $contract) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                        <i class="fas fa-eye mr-1"></i>Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $transportContracts->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay contratos de transporte</h3>
                    <p class="text-gray-500 mb-6">
                        @if(request()->hasAny(['status', 'provider', 'grade']))
                            No se encontraron contratos que coincidan con los filtros aplicados.
                        @else
                            Los estudiantes de tu escuela aún no tienen contratos de transporte registrados.
                        @endif
                    </p>
                    @if(request()->hasAny(['status', 'provider', 'grade']))
                        <a href="{{ route('school.transport-contracts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-times mr-2"></i>Limpiar filtros
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

