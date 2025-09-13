{{--
    Archivo: resources/views/admin/providers/show.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/providers/{provider}', [ProviderController::class, 'show'])
--}}

@extends('layouts.app')

@section('title', 'Detalles de Proveedor - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $provider->display_name }}</h1>
                <p class="text-gray-600">Detalles del proveedor de transporte escolar</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.providers.edit', $provider) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="{{ route('admin.providers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    ← Volver a Proveedores
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
                            <dt class="text-sm font-medium text-gray-500">Nombre de la Empresa</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->display_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tipo de Proveedor</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($provider->provider_type === 'company') bg-blue-100 text-blue-800
                                    @elseif($provider->provider_type === 'school') bg-green-100 text-green-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    @if($provider->provider_type === 'company')
                                        Empresa
                                    @elseif($provider->provider_type === 'school')
                                        Escuela/Colegio
                                    @else
                                        Individual
                                    @endif
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email de Contacto</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->contact_email ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono de Contacto</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->contact_phone ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Número de Licencia</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->license_number ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIT/RUT</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->tax_id ?? 'No especificado' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->address ?? 'No especificada' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sitio Web</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($provider->website)
                                    <a href="{{ $provider->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $provider->website }}</a>
                                @else
                                    No especificado
                                @endif
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Años en el Negocio</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->years_in_business ?? 'No especificado' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Estadísticas de conductores -->
            <div class="bg-white shadow-md rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Estadísticas de Conductores</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $provider->drivers->count() }}</div>
                            <div class="text-sm text-gray-500">Total Conductores</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $provider->drivers->where('driver_status', 'active')->count() }}</div>
                            <div class="text-sm text-gray-500">Conductores Activos</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $provider->drivers->where('driver_status', 'pending')->count() }}</div>
                            <div class="text-sm text-gray-500">Conductores Pendientes</div>
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
                        <dt class="text-sm font-medium text-gray-500">Estado del Proveedor</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($provider->provider_status === 'active') bg-green-100 text-green-800
                                @elseif($provider->provider_status === 'pending') bg-orange-100 text-orange-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($provider->provider_status ?? 'pending') }}
                            </span>
                        </dd>
                    </div>

                    <div class="space-y-3">
                        @if($provider->provider_status === 'active')
                            <form action="{{ route('admin.providers.suspend', $provider) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500" onclick="return confirm('¿Estás seguro de suspender este proveedor?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Suspender Proveedor
                                </button>
                            </form>
                        @elseif($provider->provider_status === 'suspended')
                            <form action="{{ route('admin.providers.activate', $provider) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" onclick="return confirm('¿Estás seguro de activar este proveedor?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Activar Proveedor
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
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->account->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->account->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Documento</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->account->id_number ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->account->phone_number ?? 'No especificado' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado de Verificación</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($provider->account->verification_status === 'verified') bg-green-100 text-green-800
                                    @elseif($provider->account->verification_status === 'rejected') bg-red-100 text-red-800
                                    @else bg-orange-100 text-orange-800 @endif">
                                    {{ ucfirst($provider->account->verification_status ?? 'pending') }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Registro</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->account->created_at->format('d/m/Y H:i') }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500">Total de Conductores</dt>
                            <dd class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($provider->drivers->count()) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Conductores Activos</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->drivers->where('driver_status', 'active')->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Conductores Pendientes</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->drivers->where('driver_status', 'pending')->count() }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Años en el Negocio</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $provider->years_in_business ?? 'No especificado' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
