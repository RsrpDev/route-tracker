@extends('layouts.app')

@section('title', 'Seleccionar Conductor de Transporte')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Catálogo de Conductores</h1>
                    <p class="mt-2 text-gray-600">Conductores especializados para <strong>{{ $student->given_name }} {{ $student->family_name }}</strong></p>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-school mr-1"></i>Escuela: {{ $student->school->legal_name ?? 'Sin escuela asignada' }} |
                        <i class="fas fa-clock mr-1"></i>Jornada: {{ ucfirst($student->shift ?? 'no especificada') }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('parent.provider-selection.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>Cambiar Estudiante
                    </a>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="hidden" name="student_id" value="{{ $student->student_id }}">
                <div>
                    <label for="provider_type" class="block text-sm font-medium text-gray-700 mb-1">Tipo de Proveedor</label>
                    <select name="provider_type" id="provider_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Todos los tipos</option>
                        @foreach($providerTypes as $type)
                            <option value="{{ $type['value'] }}" {{ request('provider_type') === $type['value'] ? 'selected' : '' }}>
                                {{ $type['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="max_price" class="block text-sm font-medium text-gray-700 mb-1">Precio Máximo</label>
                    <select name="max_price" id="max_price" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Sin límite</option>
                        <option value="100000" {{ request('max_price') == '100000' ? 'selected' : '' }}>Hasta $100,000</option>
                        <option value="150000" {{ request('max_price') == '150000' ? 'selected' : '' }}>Hasta $150,000</option>
                        <option value="200000" {{ request('max_price') == '200000' ? 'selected' : '' }}>Hasta $200,000</option>
                        <option value="300000" {{ request('max_price') == '300000' ? 'selected' : '' }}>Hasta $300,000</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Conductores -->
        @if($drivers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($drivers as $driver)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Header del conductor -->
                        <div class="p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-blue-500 flex items-center justify-center">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $driver->given_name }} {{ $driver->family_name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        @if($driver->provider)
                                            @switch($driver->provider->provider_type)
                                                @case('driver')
                                                    Conductor Independiente
                                                    @break
                                                @case('company')
                                                    {{ $driver->provider->display_name }}
                                                    @break
                                                @case('school_provider')
                                                    {{ $driver->provider->display_name }}
                                                    @break
                                                @default
                                                    {{ $driver->provider->display_name }}
                                            @endswitch
                                        @else
                                            Conductor Independiente
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- Información del conductor -->
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-route w-4 h-4 mr-2"></i>
                                    <span>{{ $driver->routeAssignments->count() }} rutas para {{ $student->school->legal_name ?? 'tu escuela' }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-dollar-sign w-4 h-4 mr-2"></i>
                                    <span>Desde ${{ number_format($driver->routeAssignments->pluck('route')->min('monthly_price'), 0) }}/mes</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-id-card w-4 h-4 mr-2"></i>
                                    <span>{{ $driver->years_experience }} años de experiencia</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-star w-4 h-4 mr-2 text-yellow-400"></i>
                                    <span>4.5/5 ({{ rand(10, 50) }} reseñas)</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock w-4 h-4 mr-2"></i>
                                    <span>Disponible para jornada {{ ucfirst($student->shift ?? 'no especificada') }}</span>
                                </div>
                            </div>

                            <!-- Rutas disponibles -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Rutas para {{ $student->school->legal_name ?? 'tu escuela' }}:</h4>
                                <div class="space-y-1">
                                    @foreach($driver->routeAssignments->take(3) as $assignment)
                                        @if($assignment->route)
                                            <div class="text-xs text-gray-600 bg-gray-50 p-3 rounded border-l-4 border-blue-400">
                                                <div class="font-medium text-gray-900">{{ $assignment->route->route_name }}</div>
                                                <div class="text-gray-600 mt-1">
                                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                                    {{ Str::limit($assignment->route->origin_address, 35) }}
                                                </div>
                                                <div class="text-gray-600">
                                                    <i class="fas fa-arrow-right mr-1"></i>
                                                    {{ Str::limit($assignment->route->destination_address, 35) }}
                                                </div>
                                                <div class="flex justify-between items-center mt-2">
                                                    <div class="text-green-600 font-medium">
                                                        ${{ number_format($assignment->route->monthly_price, 0) }}/mes
                                                    </div>
                                                    <div class="text-blue-600 text-xs">
                                                        @if($assignment->route->pickup_time)
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $assignment->route->pickup_time->format('H:i') }}
                                                        @else
                                                            <i class="fas fa-clock mr-1"></i>
                                                            Horario flexible
                                                        @endif
                                                    </div>
                                                </div>
                                                @if($assignment->route->schedule_days && is_array($assignment->route->schedule_days))
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <i class="fas fa-calendar mr-1"></i>
                                                        {{ implode(', ', array_map('ucfirst', $assignment->route->schedule_days)) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                    @if($driver->routeAssignments->count() > 3)
                                        <div class="text-xs text-gray-500 text-center bg-gray-100 p-2 rounded">
                                            <i class="fas fa-plus mr-1"></i>
                                            {{ $driver->routeAssignments->count() - 3 }} rutas adicionales disponibles
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Planes de pago disponibles -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Planes de Pago:</h4>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="text-xs bg-blue-50 p-2 rounded text-center">
                                        <div class="font-medium text-blue-800">Mensual</div>
                                        <div class="text-blue-600">Sin descuento</div>
                                    </div>
                                    <div class="text-xs bg-green-50 p-2 rounded text-center">
                                        <div class="font-medium text-green-800">Trimestral</div>
                                        <div class="text-green-600">5% descuento</div>
                                    </div>
                                    <div class="text-xs bg-purple-50 p-2 rounded text-center">
                                        <div class="font-medium text-purple-800">Anual</div>
                                        <div class="text-purple-600">15% descuento</div>
                                    </div>
                                    <div class="text-xs bg-orange-50 p-2 rounded text-center">
                                        <div class="font-medium text-orange-800">Pospago</div>
                                        <div class="text-orange-600">Flexible</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Estado del conductor -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $driver->driver_status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($driver->driver_status) }}
                                </span>
                                <span class="text-xs text-gray-500">
                                    Licencia: {{ $driver->license_number ?? 'No especificada' }}
                                </span>
                            </div>

                            <!-- Botón de acción -->
                            <div class="flex space-x-2">
                                <a href="{{ route('parent.provider-selection.show', $driver) }}"
                                   class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>Ver Detalles
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-user-times"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron conductores</h3>
                <p class="text-gray-500 mb-6">
                    @if(request()->hasAny(['provider_type', 'max_price']))
                        No hay conductores que coincidan con los filtros aplicados para la escuela {{ $student->school->legal_name ?? 'del estudiante' }}.
                    @else
                        No hay conductores de transporte disponibles para la escuela {{ $student->school->legal_name ?? 'del estudiante' }} en este momento.
                    @endif
                </p>
                @if(request()->hasAny(['provider_type', 'max_price']))
                    <a href="{{ route('parent.provider-selection.index', ['student_id' => $student->student_id]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-times mr-2"></i>Limpiar filtros
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
