@extends('layouts.app')

@section('title', 'Gestión de Rutas')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Gestión de Rutas</h1>
                    <p class="mt-2 text-gray-600">Administra las rutas de transporte disponibles para tu institución</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('school.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-route text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Rutas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $routes->total() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Activas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $routes->where('active_flag', true)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-truck text-2xl text-purple-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Proveedores</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $routes->pluck('provider.display_name')->unique()->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Estudiantes</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $routes->sum(function($route) { return $route->transportContracts->count(); }) }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Filtros</h3>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route('school.routes') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los estados</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activa</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactiva</option>
                        </select>
                    </div>

                    <div>
                        <label for="provider" class="block text-sm font-medium text-gray-700">Proveedor</label>
                        <select name="provider" id="provider" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los proveedores</option>
                            @foreach($routes->pluck('provider.display_name')->filter()->unique() as $providerName)
                                <option value="{{ $providerName }}" {{ request('provider') == $providerName ? 'selected' : '' }}>{{ $providerName }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="provider_type" class="block text-sm font-medium text-gray-700">Tipo de Proveedor</label>
                        <select name="provider_type" id="provider_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los tipos</option>
                            <option value="driver" {{ request('provider_type') == 'driver' ? 'selected' : '' }}>Conductor Independiente</option>
                            <option value="company" {{ request('provider_type') == 'company' ? 'selected' : '' }}>Empresa de Transporte</option>
                            <option value="school_provider" {{ request('provider_type') == 'school_provider' ? 'selected' : '' }}>Colegio Prestador</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <i class="fas fa-search mr-2"></i>
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Routes Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Lista de Rutas</h3>
            </div>

            @if($routes->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($routes as $route)
                        <li>
                            <a href="{{ route('school.routes.show', $route->route_id) }}" class="block hover:bg-gray-50 px-6 py-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-route text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <p class="text-sm font-medium text-gray-900">{{ $route->route_name }}</p>
                                                @if($route->active_flag)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Activa
                                                    </span>
                                                @else
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Inactiva
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                                <i class="fas fa-truck mr-1"></i>
                                                <span>{{ $route->provider->display_name ?? 'Proveedor no especificado' }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                <span>{{ $route->origin_address ?? 'Origen no especificado' }}</span>
                                                <span class="mx-2">→</span>
                                                <span>{{ $route->destination_address ?? 'Destino no especificado' }}</span>
                                            </div>
                                            <div class="mt-1 flex items-center text-sm text-gray-500">
                                                <i class="fas fa-clock mr-1"></i>
                                                <span>Salida: {{ $route->departure_time ?? 'No especificada' }}</span>
                                                <span class="mx-2">•</span>
                                                <span>Llegada: {{ $route->arrival_time ?? 'No especificada' }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-users mr-1"></i>
                                                <span>{{ $route->transportContracts->count() }} estudiantes</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <div class="text-right">
                                            <div class="text-xs text-gray-400">
                                                {{ $route->provider->provider_type ? ucfirst($route->provider->provider_type) : 'N/A' }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                Capacidad: {{ $route->capacity ?? 'N/A' }}
                                            </div>
                                        </div>
                                        <i class="fas fa-chevron-right ml-2"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $routes->appends(request()->query())->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-route text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay rutas registradas</h3>
                    <p class="text-gray-500">No se encontraron rutas que coincidan con los filtros aplicados.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
