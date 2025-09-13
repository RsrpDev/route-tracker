{{--
    Archivo: resources/views/admin/accounts/show.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/accounts/{account}', [ResourceController::class, 'showAccount'])
--}}

@extends('layouts.app')

@section('title', 'Detalles de Cuenta - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $account->full_name }}</h1>
                <p class="text-gray-600">Detalles de la cuenta</p>
            </div>
            <div class="flex space-x-3">
                @if($account->verification_status === 'pending')
                    <a href="{{ route('admin.verification.show', $account) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Verificar Cuenta
                    </a>
                @endif
                <a href="{{ route('admin.accounts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    ← Volver a Cuentas
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
                            <dt class="text-sm font-medium text-gray-500">Documento de Identidad</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->id_number ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->phone_number ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo de Cuenta</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($account->account_type === 'admin') bg-purple-100 text-purple-800
                                    @elseif($account->account_type === 'provider') bg-blue-100 text-blue-800
                                    @elseif($account->account_type === 'parent') bg-green-100 text-green-800
                                    @elseif($account->account_type === 'school') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($account->account_type) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Información del perfil específico -->
            @if($account->provider)
                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Información del Proveedor</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre de la Empresa</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->provider->display_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo de Proveedor</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($account->provider->provider_type === 'company') bg-blue-100 text-blue-800
                                        @elseif($account->provider->provider_type === 'school') bg-green-100 text-green-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                        @if($account->provider->provider_type === 'company')
                                            Empresa
                                        @elseif($account->provider->provider_type === 'school')
                                            Escuela/Colegio
                                        @else
                                            Individual
                                        @endif
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email de Contacto</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->provider->contact_email ?? 'No especificado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono de Contacto</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->provider->contact_phone ?? 'No especificado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Número de Licencia</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->provider->license_number ?? 'No especificado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado del Proveedor</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($account->provider->provider_status === 'active') bg-green-100 text-green-800
                                        @elseif($account->provider->provider_status === 'pending') bg-orange-100 text-orange-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($account->provider->provider_status ?? 'pending') }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @elseif($account->parentProfile)
                <div class="bg-white shadow-md rounded-lg mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Información del Padre/Madre</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->parentProfile->phone_number ?? 'No especificado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->parentProfile->address ?? 'No especificada' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @elseif($account->school)
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
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->school->dane_code ?? 'No asignado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->school->phone_number ?? 'No especificado' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->school->address ?? 'No especificada' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Niveles Educativos</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->school->grade_levels ?? 'No especificados' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total de Estudiantes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($account->school->total_students ?? 0) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            @endif
        </div>

        <!-- Panel lateral -->
        <div>
            <!-- Estado de verificación -->
            <div class="bg-white shadow-md rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estado de Verificación</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="mb-4">
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($account->verification_status === 'verified') bg-green-100 text-green-800
                                @elseif($account->verification_status === 'rejected') bg-red-100 text-red-800
                                @else bg-orange-100 text-orange-800 @endif">
                                {{ ucfirst($account->verification_status ?? 'pending') }}
                            </span>
                        </dd>
                    </div>

                    @if($account->verified_at)
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500">Fecha de Verificación</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->verified_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif

                    @if($account->verifier)
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500">Verificado por</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->verifier->full_name }}</dd>
                        </div>
                    @endif

                    @if($account->verification_notes)
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500">Notas de Verificación</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->verification_notes }}</dd>
                        </div>
                    @endif

                    @if($account->verification_status === 'pending')
                        <div class="mt-4">
                            <a href="{{ route('admin.verification.show', $account) }}" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Verificar Cuenta
                            </a>
                        </div>
                    @endif
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
                            <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $account->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        @if($account->provider)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Conductores</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->provider->drivers->count() }}</dd>
                            </div>
                        @elseif($account->school)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estudiantes</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $account->school->students->count() }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
