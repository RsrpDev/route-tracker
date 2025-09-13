@extends('layouts.app')

@section('title', 'Detalle de Ruta')

@section('content')
<div class="min-h-screen bg-gray-50 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="flex items-center space-x-4">
                            <li>
                                <a href="{{ route('school.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-home"></i>
                                    <span class="sr-only">Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <a href="{{ route('school.routes') }}" class="text-gray-400 hover:text-gray-500">Rutas</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-gray-500">{{ $route->route_name }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $route->route_name }}</h1>
                    <p class="mt-2 text-gray-600">Información detallada de la ruta de transporte</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('school.routes') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a Rutas
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Route Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Route Details -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Información de la Ruta</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre de la Ruta</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $route->route_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1">
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
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dirección de Origen</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $route->origin_address ?? 'No especificada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dirección de Destino</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $route->destination_address ?? 'No especificada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Hora de Salida</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $route->departure_time ?? 'No especificada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Hora de Llegada</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $route->arrival_time ?? 'No especificada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Capacidad</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $route->capacity ?? 'No especificada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $route->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Provider Information -->
                @if($route->provider)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Proveedor</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                                        <i class="fas fa-truck text-purple-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $route->provider->display_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ ucfirst($route->provider->provider_type) }}</p>
                                </div>
                            </div>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email de Contacto</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $route->provider->contact_email ?? 'No especificado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teléfono de Contacto</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $route->provider->contact_phone ?? 'No especificado' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Estado del Proveedor</dt>
                                    <dd class="mt-1">
                                        @if($route->provider->provider_status === 'active')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Activo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Inactivo
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Comisión por Defecto</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $route->provider->default_commission_rate ?? 'No especificada' }}%</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @endif

                <!-- Enrolled Students -->
                @if($route->transportContracts && $route->transportContracts->count() > 0)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Estudiantes Inscritos</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Lista de estudiantes que utilizan esta ruta</p>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="space-y-4">
                                @foreach($route->transportContracts as $contract)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <h4 class="text-sm font-medium text-gray-900">
                                                        {{ $contract->student->given_name }} {{ $contract->student->family_name }}
                                                    </h4>
                                                    <p class="text-sm text-gray-500">
                                                        Grado: {{ $contract->student->grade_level }} •
                                                        Contrato: {{ $contract->contract_start_date ? $contract->contract_start_date->format('d/m/Y') : 'No definida' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                @if($contract->contract_status === 'active')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Activa
                                                    </span>
                                                @elseif($contract->contract_status === 'pending')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Pendiente
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ ucfirst($contract->contract_status) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Acciones Rápidas</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <div class="space-y-3">
                            <a href="{{ route('school.transport-contracts.index') }}?route={{ $route->route_id }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-clipboard-list mr-2"></i>
                                Ver Inscripciones
                            </a>
                            <a href="{{ route('school.students') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-user-graduate mr-2"></i>
                                Ver Estudiantes
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Estadísticas</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estudiantes Inscritos</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $route->transportContracts->count() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Inscripciones Activas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $route->transportContracts->where('contract_status', 'active')->count() }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Capacidad Utilizada</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $route->capacity ? round(($route->transportContracts->count() / $route->capacity) * 100, 1) : 0 }}%
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Días Activa</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $route->created_at->diffInDays(now()) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Route Schedule -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Horario</h3>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Hora de Salida</dt>
                                <dd class="text-sm text-gray-900">{{ $route->departure_time ?? 'No especificada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Hora de Llegada</dt>
                                <dd class="text-sm text-gray-900">{{ $route->arrival_time ?? 'No especificada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Duración Estimada</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($route->departure_time && $route->arrival_time)
                                        {{ \Carbon\Carbon::parse($route->departure_time)->diffInMinutes(\Carbon\Carbon::parse($route->arrival_time)) }} minutos
                                    @else
                                        No calculable
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
