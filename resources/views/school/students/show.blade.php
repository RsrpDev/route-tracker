@extends('layouts.app')

@section('title', 'Detalle del Estudiante')

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
                                    <a href="{{ route('school.students') }}" class="text-gray-400 hover:text-gray-500">Estudiantes</a>
                                </div>
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                    <span class="text-gray-500">{{ $student->first_name }} {{ $student->last_name }}</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</h1>
                    <p class="mt-2 text-gray-600">Información detallada del estudiante</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('school.students') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a Estudiantes
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Student Information -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Información Personal</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Datos básicos del estudiante</p>
                    </div>
                    <div class="px-4 py-5 sm:p-6">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Nacimiento</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $student->birth_date ? $student->birth_date->format('d/m/Y') : 'No especificada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Grado</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $student->grade }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1">
                                    @if($student->status === 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>
                                            Activo
                                        </span>
                                    @elseif($student->status === 'inactive')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>
                                            Inactivo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-graduation-cap mr-1"></i>
                                            Graduado
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Transporte</dt>
                                <dd class="mt-1">
                                    @if($student->has_transport)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-bus mr-1"></i>
                                            Con Transporte
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-walking mr-1"></i>
                                            Sin Transporte
                                        </span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $student->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Contrato de Transporte -->
                @if($student->transportContract)
                    <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Contrato de Transporte</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Información del contrato de transporte activo</p>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <div class="space-y-4">
                                @php $contract = $student->transportContract; @endphp
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $contract->provider->display_name ?? 'Proveedor no especificado' }}
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                Tipo: {{ ucfirst($contract->provider->provider_type ?? 'N/A') }}
                                            </p>
                                            @if($contract->pickupRoute)
                                                <p class="text-sm text-gray-500">
                                                    Ruta de Recogida: {{ $contract->pickupRoute->route_name }}
                                                </p>
                                            @endif
                                            @if($contract->dropoffRoute)
                                                <p class="text-sm text-gray-500">
                                                    Ruta de Entrega: {{ $contract->dropoffRoute->route_name }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $contract->contract_status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $contract->contract_status === 'suspended' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $contract->contract_status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $contract->contract_status === 'completed' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                {{ ucfirst($contract->contract_status) }}
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1">
                                                Desde: {{ $contract->contract_start_date->format('d/m/Y') }}
                                            </p>
                                            @if($contract->contract_end_date)
                                                <p class="text-xs text-gray-500">
                                                    Hasta: {{ $contract->contract_end_date->format('d/m/Y') }}
                                                </p>
                                            @endif
                                            <p class="text-sm font-medium text-gray-900 mt-2">
                                                ${{ number_format($contract->monthly_fee, 0, ',', '.') }}/mes
                                            </p>
                                        </div>
                                    </div>
                                </div>
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
                            <a href="{{ route('school.transport-contracts.index') }}?student={{ $student->student_id }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-file-contract mr-2"></i>
                                Ver Contratos de Transporte
                            </a>
                            <a href="{{ route('school.routes') }}" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-route mr-2"></i>
                                Ver Rutas Disponibles
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
                                <dt class="text-sm font-medium text-gray-500">Contrato de Transporte</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    @if($student->transportContract)
                                        <span class="text-green-600">Activo</span>
                                    @else
                                        <span class="text-gray-500">Sin contrato</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Proveedor de Transporte</dt>
                                <dd class="text-lg font-medium text-gray-900">
                                    {{ $student->transportContract->provider->display_name ?? 'N/A' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Días en la Escuela</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $student->created_at->diffInDays(now()) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Parent Information -->
                @if($student->parent)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Información del Padre/Madre</h3>
                        </div>
                        <div class="px-4 py-5 sm:p-6">
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->parent->account->full_name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->parent->account->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                    <dd class="text-sm text-gray-900">{{ $student->parent->account->phone_number }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
