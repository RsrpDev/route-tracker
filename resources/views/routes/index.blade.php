@extends('layouts.app')

@section('breadcrumbs')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <i class="fas fa-home mr-2"></i>
                Inicio
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                <span class="text-sm font-medium text-gray-500">Rutas</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-route mr-2"></i>
                Gestión de Rutas
            </h2>
            @can('create', App\Models\Route::class)
            <a href="{{ route('routes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Nueva Ruta
            </a>
            @endcan
        </div>
    </div>

    <div class="p-6">
        <!-- Filtros -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="q" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <input type="text" name="q" id="q" value="{{ request('q') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Nombre, origen, destino...">
                </div>
                <div>
                    <label for="provider_id" class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
                    <select name="provider_id" id="provider_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los proveedores</option>
                        @foreach($routes->pluck('provider')->unique() as $provider)
                            @if($provider)
                            <option value="{{ $provider->provider_id }}" {{ request('provider_id') == $provider->provider_id ? 'selected' : '' }}>
                                {{ $provider->display_name }}
                            </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="active_flag" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="active_flag" id="active_flag"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ request('active_flag') === '1' ? 'selected' : '' }}>Activa</option>
                        <option value="0" {{ request('active_flag') === '0' ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de rutas -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ruta
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Proveedor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Origen
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Destino
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Capacidad
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Precio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($routes as $route)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $route->route_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $route->provider->display_name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ Str::limit($route->origin_address, 30) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ Str::limit($route->destination_address, 30) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $route->capacity }} estudiantes</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${{ number_format($route->monthly_price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($route->active_flag)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Activa
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactiva
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('routes.show', $route) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('update', $route)
                                <a href="{{ route('routes.edit', $route) }}"
                                   class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center py-8">
                                <i class="fas fa-route text-4xl text-gray-300 mb-4"></i>
                                <p class="text-lg font-medium text-gray-400">No se encontraron rutas</p>
                                <p class="text-sm text-gray-300">Crea la primera ruta para comenzar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($routes->hasPages())
        <div class="mt-6">
            {{ $routes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
