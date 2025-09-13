{{--
    Archivo: resources/views/admin/students/show.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/students/{student}', [StudentController::class, 'show'])
--}}

@extends('layouts.app')

@section('title', 'Detalles del Estudiante - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $student->given_name }} {{ $student->family_name }}</h1>
                <p class="text-gray-600">Información detallada del estudiante</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.students.edit', $student) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('admin.students') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Mensajes de éxito -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información Personal -->
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información Personal</h3>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre completo</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->given_name }} {{ $student->family_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Cédula de identidad</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->identity_number ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de nacimiento</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->birth_date?->format('d/m/Y') ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->phone_number ?? 'No especificado' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->address ?? 'No especificada' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Información Académica -->
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información Académica</h3>
                </div>
                <div class="px-6 py-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Escuela</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($student->school)
                                    <a href="{{ route('schools.show', $student->school) }}" class="text-blue-600 hover:text-blue-900">
                                        {{ $student->school->legal_name }}
                                    </a>
                                @else
                                    No asignada
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Grado</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->grade ?? 'No especificado' }}° Grado</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Turno</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($student->shift)
                                    @switch($student->shift)
                                        @case('morning')
                                            Mañana
                                            @break
                                        @case('afternoon')
                                            Tarde
                                            @break
                                        @case('evening')
                                            Noche
                                            @break
                                        @default
                                            {{ ucfirst($student->shift) }}
                                    @endswitch
                                @else
                                    No especificado
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($student->status === 'active') bg-green-100 text-green-800
                                    @elseif($student->status === 'inactive') bg-red-100 text-red-800
                                    @elseif($student->status === 'graduated') bg-blue-100 text-blue-800
                                    @else bg-orange-100 text-orange-800 @endif">
                                    {{ ucfirst($student->status ?? 'active') }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Información de Transporte -->
            @if($student->transportContracts->count() > 0)
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Contratos de Transporte</h3>
                </div>
                <div class="px-6 py-6">
                    <div class="space-y-4">
                        @foreach($student->transportContracts as $contract)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-900">Contrato #{{ $contract->contract_id }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($contract->contract_status === 'active') bg-green-100 text-green-800
                                    @elseif($contract->contract_status === 'pending') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($contract->contract_status) }}
                                </span>
                            </div>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-2 text-sm">
                                <div>
                                    <dt class="font-medium text-gray-500">Proveedor</dt>
                                    <dd class="text-gray-900">{{ $contract->provider->display_name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Ruta de recogida</dt>
                                    <dd class="text-gray-900">{{ $contract->pickupRoute->route_name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Ruta de entrega</dt>
                                    <dd class="text-gray-900">{{ $contract->dropoffRoute->route_name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium text-gray-500">Fecha de inicio</dt>
                                    <dd class="text-gray-900">{{ $contract->start_date?->format('d/m/Y') ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Información del Padre/Madre -->
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Padre/Madre</h3>
                </div>
                <div class="px-6 py-6">
                    @if($student->parentProfile)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="text-green-600 font-medium text-sm">👨‍👩‍👧‍👦</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $student->parentProfile->account->full_name }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $student->parentProfile->account->email }}</div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('parents.show', $student->parentProfile) }}" class="text-blue-600 hover:text-blue-900 text-sm">
                                Ver perfil completo →
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500">No asignado</p>
                    @endif
                </div>
            </div>

            <!-- Estado de Transporte -->
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estado de Transporte</h3>
                </div>
                <div class="px-6 py-6">
                    <div class="flex items-center">
                        @if($student->has_transport)
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">Con Transporte</p>
                                <p class="text-sm text-green-600">{{ $student->transportContracts->where('contract_status', 'active')->count() }} contrato(s) activo(s)</p>
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-800">Sin Transporte</p>
                                <p class="text-sm text-gray-600">No tiene transporte asignado</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Acciones</h3>
                </div>
                <div class="px-6 py-6 space-y-3">
                    @if($student->status === 'active')
                        <form action="{{ route('admin.students.suspend', $student) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" onclick="return confirm('¿Estás seguro de suspender este estudiante?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                </svg>
                                Suspender Estudiante
                            </button>
                        </form>
                    @elseif($student->status === 'inactive')
                        <form action="{{ route('admin.students.activate', $student) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="return confirm('¿Estás seguro de activar este estudiante?')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Activar Estudiante
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('admin.students.destroy', $student) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este estudiante? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Eliminar Estudiante
                        </button>
                    </form>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Información del Sistema</h3>
                </div>
                <div class="px-6 py-6">
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">ID del Estudiante</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->student_id }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de registro</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $student->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
