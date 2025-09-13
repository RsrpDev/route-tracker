@extends('layouts.app')

@section('title', 'Mi Perfil - Conductor Independiente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Mi Perfil</h2>
                <p class="text-gray-600">Gestiona tu información personal y profesional</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('driver.profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Perfil
                </a>
                <a href="{{ route('driver.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información Personal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Información Personal</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-900">{{ $provider->display_name ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-900">{{ $provider->contact_email ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono de Contacto</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-900">{{ $provider->contact_phone ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado del Proveedor</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $provider->provider_status === 'active' ? 'bg-green-100 text-green-800' :
                                   ($provider->provider_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($provider->provider_status ?? 'active') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Profesional -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Información Profesional</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de Licencia</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-900">{{ $provider->driver_license_number ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoría de Licencia</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-900">{{ $provider->driver_license_category ?? 'No especificado' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Vencimiento</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-900">{{ $provider->driver_license_expiration ? $provider->driver_license_expiration->format('d/m/Y') : 'No especificada' }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Años de Experiencia</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-900">{{ $provider->driver_years_experience ?? 0 }} años</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Comisión por Defecto</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <p class="text-gray-900">{{ $provider->default_commission_rate ?? 0 }}%</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado de la Licencia</label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $provider->hasValidLicense() ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $provider->getLicenseStatusText() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel Lateral -->
        <div class="space-y-6">
            <!-- Estado de la Licencia -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado de la Licencia</h3>

                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center
                        {{ $provider->hasValidLicense() ? 'bg-green-100' : 'bg-red-100' }}">
                        <svg class="w-8 h-8 {{ $provider->hasValidLicense() ? 'text-green-600' : 'text-red-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>

                    <p class="text-sm font-medium text-gray-900 mb-2">{{ $provider->getLicenseStatusText() }}</p>

                    @if($provider->driver_license_expiration)
                        <p class="text-xs text-gray-500">
                            Vence: {{ $provider->driver_license_expiration->format('d/m/Y') }}
                        </p>
                        @if($provider->driver_license_expiration->diffInDays(now()) < 30)
                            <p class="text-xs text-red-600 mt-2">
                                ⚠️ Tu licencia vence pronto
                            </p>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>

                <div class="space-y-3">
                    <a href="{{ route('driver.profile.edit') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Editar Perfil
                    </a>

                    <a href="{{ route('driver.license-status') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Estado de Licencia
                    </a>

                    <a href="{{ route('driver.statistics') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Mis Estadísticas
                    </a>

                    <a href="{{ route('driver.dashboard') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Volver al Dashboard
                    </a>
                </div>
            </div>

            <!-- Información de Cuenta -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Cuenta</h3>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Tipo de Cuenta:</span>
                        <span class="text-sm font-medium text-gray-900">Conductor Independiente</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Miembro desde:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $provider->created_at->format('M Y') }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Última actualización:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $provider->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Perfil del conductor independiente cargado');
});
</script>
@endpush
@endsection
