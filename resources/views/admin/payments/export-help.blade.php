{{--
    Archivo: resources/views/admin/payments/export-help.blade.php
    Propósito: Página de ayuda para exportación de pagos
--}}

@extends('layouts.app')

@section('title', 'Ayuda - Exportación de Pagos')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Ayuda - Exportación de Pagos</h1>
                <p class="text-gray-600">Guía para usar la funcionalidad de exportación</p>
            </div>
            <div>
                <a href="{{ route('admin.payments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Volver a Pagos
                </a>
            </div>
        </div>
    </div>

    <!-- Información de Acceso -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-blue-800">Requisitos de Acceso</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>Para exportar pagos necesitas:</p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Estar <strong>autenticado</strong> en el sistema</li>
                        <li>Tener rol de <strong>administrador</strong></li>
                        <li>Acceder desde el dashboard de administración</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Pasos para Exportar -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Pasos para Exportar Pagos</h3>

        <div class="space-y-4">
            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                    1
                </div>
                <div class="ml-4">
                    <h4 class="text-md font-medium text-gray-900">Iniciar Sesión como Administrador</h4>
                    <p class="text-sm text-gray-600 mt-1">Ve a <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Iniciar Sesión</a> y usa tus credenciales de administrador.</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                    2
                </div>
                <div class="ml-4">
                    <h4 class="text-md font-medium text-gray-900">Acceder al Dashboard de Administración</h4>
                    <p class="text-sm text-gray-600 mt-1">Ve a <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard de Administración</a>.</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                    3
                </div>
                <div class="ml-4">
                    <h4 class="text-md font-medium text-gray-900">Ir a Gestión de Pagos</h4>
                    <p class="text-sm text-gray-600 mt-1">Haz clic en "Monitorear Pagos" o ve directamente a <a href="{{ route('admin.payments.index') }}" class="text-blue-600 hover:text-blue-800">Gestión de Pagos</a>.</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                    4
                </div>
                <div class="ml-4">
                    <h4 class="text-md font-medium text-gray-900">Usar Exportación Avanzada</h4>
                    <p class="text-sm text-gray-600 mt-1">Haz clic en el botón "Exportar Avanzado" para abrir el modal con opciones de filtrado.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formatos Disponibles -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">📄 Formatos de Exportación</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4 border border-gray-200 rounded-lg">
                <div class="text-3xl mb-2">📊</div>
                <h4 class="font-medium text-gray-900">CSV</h4>
                <p class="text-sm text-gray-600 mt-1">Compatible con Excel, LibreOffice y Google Sheets</p>
            </div>

            <div class="text-center p-4 border border-gray-200 rounded-lg">
                <div class="text-3xl mb-2">📈</div>
                <h4 class="font-medium text-gray-900">Excel</h4>
                <p class="text-sm text-gray-600 mt-1">Archivo .xlsx nativo con formato profesional</p>
            </div>

            <div class="text-center p-4 border border-gray-200 rounded-lg">
                <div class="text-3xl mb-2">📋</div>
                <h4 class="font-medium text-gray-900">PDF</h4>
                <p class="text-sm text-gray-600 mt-1">Documento PDF para impresión y archivo</p>
            </div>
        </div>
    </div>

    <!-- Filtros Disponibles -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔍 Filtros Disponibles</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Por Estado</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Pendiente</li>
                    <li>• Procesando</li>
                    <li>• Completado</li>
                    <li>• Fallido</li>
                    <li>• Cancelado</li>
                    <li>• Reembolsado</li>
                </ul>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Por Método de Pago</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Tarjeta de Crédito</li>
                    <li>• Tarjeta de Débito</li>
                    <li>• Transferencia Bancaria</li>
                    <li>• Efectivo</li>
                </ul>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Por Fechas</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Fecha desde</li>
                    <li>• Fecha hasta</li>
                    <li>• Rango personalizable</li>
                </ul>
            </div>

            <div>
                <h4 class="font-medium text-gray-900 mb-2">Opciones Adicionales</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Incluir detalles completos</li>
                    <li>• Teléfonos y emails</li>
                    <li>• Direcciones</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Solución de Problemas -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-yellow-800">⚠️ Solución de Problemas</h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p><strong>Si recibes error 404:</strong></p>
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>Asegúrate de estar logueado como administrador</li>
                        <li>Verifica que tu cuenta tenga rol de 'admin'</li>
                        <li>Accede desde el dashboard de administración</li>
                        <li>No uses la URL directamente sin estar autenticado</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlaces Rápidos -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">🔗 Enlaces Rápidos</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('login') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">🔐</div>
                    <span class="text-sm font-medium text-gray-700">Iniciar Sesión</span>
                </div>
            </a>

            <a href="{{ route('admin.dashboard') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">📊</div>
                    <span class="text-sm font-medium text-gray-700">Dashboard Admin</span>
                </div>
            </a>

            <a href="{{ route('admin.payments.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">💰</div>
                    <span class="text-sm font-medium text-gray-700">Gestión de Pagos</span>
                </div>
            </a>

            <a href="{{ route('admin.accounts.index') }}" class="block p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors">
                <div class="text-center">
                    <div class="text-2xl mb-2">👥</div>
                    <span class="text-sm font-medium text-gray-700">Gestión de Cuentas</span>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
