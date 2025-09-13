@extends('layouts.app')

@section('title', 'Perfil de la Escuela')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">🏫 Perfil de la Escuela</h1>
                <p class="mt-2 text-gray-600">Información detallada de {{ $school->school_name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('school.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                    Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Información Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información Básica -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">📋 Información Básica</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre de la Escuela</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->school_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Código DANE</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->dane_code ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIT</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->nit ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo de Institución</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($school->institution_type ?? 'No especificado') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Naturaleza</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($school->nature ?? 'No especificado') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Calendario</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($school->calendar ?? 'No especificado') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Género</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($school->gender ?? 'No especificado') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jornada</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($school->school_day ?? 'No especificado') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">📞 Información de Contacto</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->address ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ciudad</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->city ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Departamento</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->department ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->phone ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->email ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sitio Web</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($school->website)
                                    <a href="{{ $school->website }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                        {{ $school->website }}
                                    </a>
                                @else
                                    No especificado
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Información Académica -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">🎓 Información Académica</h3>
                </div>
                <div class="p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Niveles Educativos</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->education_levels ?? 'No especificados' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Grados Ofrecidos</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->grades_offered ?? 'No especificados' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Modalidad</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($school->modality ?? 'No especificada') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Carácter</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($school->character ?? 'No especificado') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Estado de la Escuela -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">📊 Estado de la Escuela</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Activa</p>
                            <p class="text-sm text-gray-500">Escuela registrada y operativa</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Servicio de Transporte -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">🚌 Servicio de Transporte</h3>
                </div>
                <div class="p-6">
                    @if($school->transport_service)
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Disponible</p>
                                <p class="text-sm text-gray-500">Servicio de transporte activo</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('provider.school.dashboard') }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Gestionar Transporte
                            </a>
                        </div>
                    @else
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">No Disponible</p>
                                <p class="text-sm text-gray-500">Sin servicio de transporte</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">⚡ Acciones Rápidas</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('school.dashboard') }}" class="block w-full text-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    @if($school->transport_service)
                        <a href="{{ route('provider.school.dashboard') }}" class="block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            Transporte
                        </a>
                    @endif
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="bg-white shadow rounded-lg mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">ℹ️ Información Adicional</h3>
                </div>
                <div class="p-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->created_at?->format('d/m/Y') ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->updated_at?->format('d/m/Y') ?? 'No especificada' }}</dd>
                        </div>
                        @if($school->description)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $school->description }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




