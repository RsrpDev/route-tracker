@extends('layouts.app')

@section('title', 'Estado de Licencia - Conductor Independiente')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Encabezado -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Estado de Licencia</h2>
                <p class="text-gray-600">Información detallada sobre tu licencia de conducción</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('driver.profile') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Volver al Perfil
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Estado Principal de la Licencia -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Estado Actual</h3>

            <div class="text-center mb-8">
                <div class="w-24 h-24 mx-auto mb-4 rounded-full flex items-center justify-center
                    {{ $provider->hasValidLicense() ? 'bg-green-100' : 'bg-red-100' }}">
                    @if($provider->hasValidLicense())
                        <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    @else
                        <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    @endif
                </div>

                <h4 class="text-xl font-semibold text-gray-900 mb-2">{{ $provider->getLicenseStatusText() }}</h4>

                @if($provider->driver_license_expiration)
                    <p class="text-sm text-gray-600">
                        Vence: {{ $provider->driver_license_expiration->format('d/m/Y') }}
                    </p>

                    @php
                        $daysUntilExpiration = $provider->driver_license_expiration->diffInDays(now());
                        $isExpired = $provider->driver_license_expiration->isPast();
                        $isExpiringSoon = $daysUntilExpiration <= 30 && !$isExpired;
                    @endphp

                    @if($isExpired)
                        <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                            <p class="text-sm text-red-800">
                                ⚠️ <strong>Licencia vencida</strong> - Debes renovar tu licencia para continuar operando
                            </p>
                        </div>
                    @elseif($isExpiringSoon)
                        <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                            <p class="text-sm text-yellow-800">
                                ⚠️ <strong>Licencia próxima a vencer</strong> - Te quedan {{ $daysUntilExpiration }} días para renovar
                            </p>
                        </div>
                    @else
                        <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-md">
                            <p class="text-sm text-green-800">
                                ✅ <strong>Licencia vigente</strong> - Válida por {{ $daysUntilExpiration }} días más
                            </p>
                        </div>
                    @endif
                @else
                    <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-md">
                        <p class="text-sm text-gray-800">
                            ℹ️ No se ha especificado fecha de vencimiento
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Detalles de la Licencia -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Detalles de la Licencia</h3>

            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Número de Licencia:</span>
                    <span class="text-sm text-gray-900 font-mono">{{ $provider->driver_license_number ?? 'No especificado' }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Categoría:</span>
                    <span class="text-sm text-gray-900">{{ $provider->driver_license_category ?? 'No especificada' }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Fecha de Vencimiento:</span>
                    <span class="text-sm text-gray-900">{{ $provider->driver_license_expiration ? $provider->driver_license_expiration->format('d/m/Y') : 'No especificada' }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Años de Experiencia:</span>
                    <span class="text-sm text-gray-900">{{ $provider->driver_years_experience ?? 0 }} años</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-sm font-medium text-gray-600">Estado del Conductor:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $provider->driver_status === 'approved' ? 'bg-green-100 text-green-800' :
                           ($provider->driver_status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($provider->driver_status ?? 'pending') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Información Adicional -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Requisitos para Renovación -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Requisitos para Renovación</h3>

            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700">Documento de identidad vigente</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700">Certificado médico actualizado</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700">Fotografías recientes</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700">Pago de tasas correspondientes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones Disponibles -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Disponibles</h3>

            <div class="space-y-3">
                <a href="{{ route('provider.driver.profile.edit') }}" class="block w-full text-center px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-blue-50 hover:bg-blue-100">
                    Actualizar Información de Licencia
                </a>

                <a href="{{ route('provider.driver.profile') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Ver Perfil Completo
                </a>

                <a href="{{ route('provider.driver.dashboard') }}" class="block w-full text-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Volver al Dashboard
                </a>
            </div>

            @if($provider->driver_license_expiration && $provider->driver_license_expiration->diffInDays(now()) <= 30)
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                    <h4 class="text-sm font-medium text-yellow-800 mb-2">Recordatorio Importante</h4>
                    <p class="text-sm text-yellow-700">
                        Tu licencia vence pronto. Te recomendamos iniciar el proceso de renovación con anticipación para evitar interrupciones en tu servicio.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Estado de licencia cargado');

    // Agregar animación a los iconos de estado
    const statusIcon = document.querySelector('.w-24.h-24 svg');
    if (statusIcon) {
        statusIcon.style.animation = 'pulse 2s infinite';
    }
});
</script>

<style>
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>
@endpush
@endsection
