{{--
    Archivo: resources/views/admin/schools/show.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/schools/{school}', [SchoolController::class, 'show'])
--}}

@extends('layouts.app')

@section('title', 'Detalles de Escuela - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $school->school_name }}</h1>
                <p class="text-gray-600">Detalles de la institución educativa</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('schools.edit', $school) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('schools.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    ← Volver a Escuelas
                </a>
            </div>
        </div>
    </div>

    <!-- Información general -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información principal -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información General</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre de la Institución</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->school_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Código DANE</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->dane_code ?? 'No asignado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email de Contacto</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->account->email ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->phone_number ?? 'No especificado' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->address ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Niveles Educativos</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->grade_levels ?? 'No especificados' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total de Estudiantes</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ number_format($school->total_students ?? 0) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Servicio de transporte -->
            <div class="bg-white shadow-md rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Servicio de Transporte</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($school->has_transport_service)
                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $school->has_transport_service ? 'Ofrece servicio de transporte' : 'No ofrece servicio de transporte' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $school->has_transport_service ? 'Esta escuela puede gestionar rutas y conductores' : 'Esta escuela solo gestiona estudiantes' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div>
            <!-- Estado y acciones -->
            <div class="bg-white shadow-md rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estado y Acciones</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($school->school_status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($school->school_status ?? 'active') }}
                            </span>
                        </dd>
                    </div>

                    <div class="space-y-3">
                        @if($school->school_status === 'active')
                            <form action="{{ route('schools.suspend', $school) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" onclick="return confirm('¿Estás seguro de suspender esta escuela?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Suspender Escuela
                                </button>
                            </form>
                        @else
                            <form action="{{ route('schools.activate', $school) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="return confirm('¿Estás seguro de activar esta escuela?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Activar Escuela
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información de la cuenta -->
            <div class="bg-white shadow-md rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información de la Cuenta</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Representante</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->account->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->account->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Documento</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->account->id_number ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->account->phone_number ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado de Verificación</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($school->account->verification_status === 'verified') bg-green-100 text-green-800
                                    @elseif($school->account->verification_status === 'rejected') bg-red-100 text-red-800
                                    @else bg-orange-100 text-orange-800 @endif">
                                    {{ ucfirst($school->account->verification_status ?? 'pending') }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->account->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estadísticas</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total de Estudiantes</dt>
                            <dd class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($school->total_students ?? 0) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Niveles Educativos</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->grade_levels ?? 'No especificados' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Servicio de Transporte</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($school->has_transport_service) bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $school->has_transport_service ? 'Disponible' : 'No disponible' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
