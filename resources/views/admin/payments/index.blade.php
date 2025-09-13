{{--
    Archivo: resources/views/admin/payments/index.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/payments', [PaymentController::class, 'index'])
--}}

@extends('layouts.app')

@section('title', 'Gestión de Pagos - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header del Dashboard -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Pagos</h1>
                <p class="text-gray-600">Monitoreo y administración de todos los pagos del sistema</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Nuevo Pago
                </a>
                <button onclick="openExportModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-download mr-2"></i>Exportar Avanzado
                </button>
            </div>
        </div>
    </div>

    <!-- Estadísticas principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Pagos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($payments->total()) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pagados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($payments->where('payment_status', 'paid')->count()) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($payments->where('payment_status', 'pending')->count()) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Fallidos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($payments->where('payment_status', 'failed')->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Buscar por ID o método..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="paid">Pagado</option>
                    <option value="pending">Pendiente</option>
                    <option value="failed">Fallido</option>
                    <option value="cancelled">Cancelado</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los métodos</option>
                    <option value="credit_card">Tarjeta de Crédito</option>
                    <option value="debit_card">Tarjeta de Débito</option>
                    <option value="bank_transfer">Transferencia Bancaria</option>
                    <option value="cash">Efectivo</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filtrar
                </button>
                <button class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                    <i class="fas fa-redo mr-2"></i>Limpiar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de pagos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Lista de Pagos</h3>
        </div>

        @if($payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Suscripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Pago</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $payment->payment_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($payment->subscription)
                                        <a href="{{ route('subscriptions.show', $payment->subscription->subscription_id) }}" class="text-blue-600 hover:text-blue-900">
                                            #{{ $payment->subscription->subscription_id }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->period_start ? $payment->period_start->format('d/m/Y') : 'N/A' }} -
                                    {{ $payment->period_end ? $payment->period_end->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex flex-col">
                                        <span class="font-semibold">${{ number_format($payment->amount_total, 2) }}</span>
                                        @if($payment->platform_fee > 0)
                                            <span class="text-xs text-gray-500">Comisión: ${{ number_format($payment->platform_fee, 2) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'paid' => 'bg-green-100 text-green-800',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $statusLabels = [
                                            'paid' => 'Pagado',
                                            'pending' => 'Pendiente',
                                            'failed' => 'Fallido',
                                            'cancelled' => 'Cancelado'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$payment->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusLabels[$payment->payment_status] ?? ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.payments.show', $payment->payment_id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.payments.edit', $payment->payment_id) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($payment->payment_status === 'pending')
                                            <button class="text-green-600 hover:text-green-900" onclick="processPayment({{ $payment->payment_id }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                        @if($payment->payment_status === 'paid')
                                            <button class="text-red-600 hover:text-red-900" onclick="refundPayment({{ $payment->payment_id }})">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay pagos</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron pagos en el sistema.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i>Crear Primer Pago
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Exportación Avanzada -->
<div id="exportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Exportar Pagos</h3>
                <button onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="exportForm" method="GET" action="{{ route('admin.payments.export') }}">
                <div class="space-y-4">
                    <!-- Formato de Exportación -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Formato de Archivo</label>
                        <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="csv">CSV (Excel compatible)</option>
                            <option value="excel">Excel (.xlsx)</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>

                    <!-- Filtros -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos los estados</option>
                                <option value="pending">Pendiente</option>
                                <option value="processing">Procesando</option>
                                <option value="completed">Completado</option>
                                <option value="failed">Fallido</option>
                                <option value="cancelled">Cancelado</option>
                                <option value="refunded">Reembolsado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                            <select name="method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos los métodos</option>
                                <option value="credit_card">Tarjeta de Crédito</option>
                                <option value="debit_card">Tarjeta de Débito</option>
                                <option value="bank_transfer">Transferencia Bancaria</option>
                                <option value="cash">Efectivo</option>
                            </select>
                        </div>
                    </div>

                    <!-- Rango de Fechas -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Desde</label>
                            <input type="date" name="date_from" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Hasta</label>
                            <input type="date" name="date_to" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Opciones Adicionales -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="include_details" value="1" checked class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Incluir detalles completos (teléfonos, emails, direcciones)</span>
                        </label>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeExportModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            <i class="fas fa-download mr-2"></i>Exportar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Funciones del Modal de Exportación
function openExportModal() {
    document.getElementById('exportModal').classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera
document.getElementById('exportModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExportModal();
    }
});

// Validación de fechas en el formulario de exportación
document.getElementById('exportForm').addEventListener('submit', function(e) {
    const dateFrom = document.querySelector('input[name="date_from"]').value;
    const dateTo = document.querySelector('input[name="date_to"]').value;

    if (dateFrom && dateTo && dateTo <= dateFrom) {
        e.preventDefault();
        alert('La fecha "hasta" debe ser posterior a la fecha "desde"');
        return false;
    }
});

function processPayment(paymentId) {
    if (confirm('¿Está seguro de que desea procesar este pago?')) {
        fetch(`/admin/payments/${paymentId}/process`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al procesar el pago');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar el pago');
        });
    }
}

function refundPayment(paymentId) {
    if (confirm('¿Está seguro de que desea reembolsar este pago?')) {
        fetch(`/admin/payments/${paymentId}/refund`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al reembolsar el pago');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al reembolsar el pago');
        });
    }
}
</script>
@endsection
