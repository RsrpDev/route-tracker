@extends('layouts.app')

@section('title', 'Configuración de Contraseña - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Configuración de Contraseña</h1>
        <p class="text-gray-600">Cambia la contraseña de tu cuenta</p>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">🔐 Cambiar Contraseña</h2>
        <p class="text-gray-600 mb-6">Esta funcionalidad estará disponible próximamente.</p>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        El sistema de cambio de contraseña está en desarrollo.
                        Mientras tanto, puedes usar la API para actualizar tu contraseña.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
