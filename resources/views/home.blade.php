@extends('layouts.app')

@section('title', 'Route Tracker - Sistema de Gestión de Rutas Escolares')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">
            🚌 Route Tracker
        </h1>
        <p class="text-xl text-gray-600">
            Sistema de Gestión Integral de Rutas Escolares
        </p>
    </div>

    <!-- Status Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-3xl mb-2">✅</div>
                <h3 class="text-lg font-semibold text-gray-800">Sistema Operativo</h3>
                <p class="text-gray-600">API funcionando correctamente</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-3xl mb-2">🔐</div>
                <h3 class="text-lg font-semibold text-gray-800">Autenticación</h3>
                <p class="text-gray-600">Sistema de tokens configurado</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="text-center">
                <div class="text-3xl mb-2">📊</div>
                <h3 class="text-lg font-semibold text-gray-800">Base de Datos</h3>
                <p class="text-gray-600">Migraciones ejecutadas</p>
            </div>
        </div>
    </div>

    <!-- API Information -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">📡 Información de la API</h2>
        <div class="space-y-3">
            <div class="flex items-center">
                <span class="font-semibold text-gray-700 w-32">Versión:</span>
                <span class="text-gray-600">1.0.0</span>
            </div>
            <div class="flex items-center">
                <span class="font-semibold text-gray-700 w-32">Estado:</span>
                <span class="text-green-600 font-semibold">Operativo</span>
            </div>
            <div class="flex items-center">
                <span class="font-semibold text-gray-700 w-32">Base URL:</span>
                <span class="text-gray-600">/api/v1</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">🚀 Acciones Rápidas</h2>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-2">Documentación API</h3>
                <p class="text-gray-600 text-sm mb-3">Consulta los endpoints disponibles y su uso</p>
                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                    Ver Documentación
                </button>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-800 mb-2">Panel de Control</h3>
                <p class="text-gray-600 text-sm mb-3">Accede al dashboard principal de la aplicación</p>
                <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                    Ir al Dashboard
                </button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-12 text-gray-500">
        <p>&copy; 2024 Route Tracker. Sistema desarrollado con Laravel y Sanctum.</p>
    </div>
</div>
@endsection
