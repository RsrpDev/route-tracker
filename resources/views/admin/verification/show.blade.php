{{--
    Archivo: resources/views/admin/verification/show.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/verification/{account}', [AdminVerificationController::class, 'show'])
--}}

@extends('layouts.app')

@section('title', 'Verificar Cuenta - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Verificar Cuenta</h1>
                <p class="text-gray-600">Revisar información y autorizar registro</p>
            </div>
            <a href="{{ route('admin.verification.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                ← Volver a Verificaciones
            </a>
        </div>
    </div>

    <!-- Información de la cuenta -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Información de la Cuenta</h3>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $account->full_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $account->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Tipo de Cuenta</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($account->account_type) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $account->phone_number ?? 'No proporcionado' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Documento de Identidad</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $account->id_number ?? 'No proporcionado' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $account->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Información específica según el tipo de cuenta -->
    @if($account->provider)
        <div class="bg-white shadow-md rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Información del Proveedor</h3>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre Comercial</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $account->provider->display_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Proveedor</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($account->provider->provider_type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($account->provider->provider_status === 'active') bg-green-100 text-green-800
                                @elseif($account->provider->provider_status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($account->provider->provider_status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $account->provider->address ?? 'No proporcionada' }}</dd>
                    </div>
                    @if($account->provider->provider_type === 'company')
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIT</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->provider->nit ?? 'No proporcionado' }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    @endif

    @if($account->school)
        <div class="bg-white shadow-md rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Información de la Escuela</h3>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre de la Institución</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $account->school->school_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Código DANE</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $account->school->dane_code ?? 'No proporcionado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $account->school->address ?? 'No proporcionada' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $account->school->phone_number ?? 'No proporcionado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Niveles Educativos</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $account->school->grade_levels ?? 'No especificados' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Servicio de Transporte</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($account->school->has_transport_service) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $account->school->has_transport_service ? 'Sí' : 'No' }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    @endif

    <!-- Estado actual de verificación -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Estado de Verificación</h3>
        </div>
        <div class="px-6 py-4">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    @if($account->verification_status === 'verified')
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    @elseif($account->verification_status === 'rejected')
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    @else
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">
                        Estado:
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($account->verification_status === 'verified') bg-green-100 text-green-800
                            @elseif($account->verification_status === 'rejected') bg-red-100 text-red-800
                            @else bg-orange-100 text-orange-800 @endif">
                            {{ ucfirst($account->verification_status) }}
                        </span>
                    </p>
                    @if($account->verified_at)
                        <p class="text-sm text-gray-500">
                            {{ $account->verification_status === 'verified' ? 'Verificado' : 'Rechazado' }}
                            {{ $account->verified_at->diffForHumans() }}
                        </p>
                    @endif
                    @if($account->verification_notes)
                        <p class="text-sm text-gray-600 mt-2">{{ $account->verification_notes }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones de verificación -->
    @if($account->verification_status === 'pending')
        <div class="bg-white shadow-md rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Acciones de Verificación</h3>
            </div>
            <div class="px-6 py-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <!-- Aprobar -->
                    <form action="{{ route('admin.verification.approve', $account) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="approve_notes" class="block text-sm font-medium text-gray-700">Notas de Aprobación (Opcional)</label>
                            <textarea id="approve_notes" name="verification_notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Agregar comentarios sobre la aprobación..."></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Aprobar Cuenta
                        </button>
                    </form>

                    <!-- Rechazar -->
                    <form action="{{ route('admin.verification.reject', $account) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="reject_notes" class="block text-sm font-medium text-gray-700">Motivo del Rechazo *</label>
                            <textarea id="reject_notes" name="verification_notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" placeholder="Explicar el motivo del rechazo..." required></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Rechazar Cuenta
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <!-- Revertir verificación -->
        <div class="bg-white shadow-md rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Acciones Disponibles</h3>
            </div>
            <div class="px-6 py-4">
                <form action="{{ route('admin.verification.revert', $account) }}" method="POST" class="space-y-4">
                    @csrf
                    <p class="text-sm text-gray-600">
                        Esta cuenta ya ha sido {{ $account->verification_status === 'verified' ? 'verificada' : 'rechazada' }}.
                        Puedes revertir la decisión y volver a ponerla en estado pendiente.
                    </p>
                    <button type="submit" class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Revertir Verificación
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
