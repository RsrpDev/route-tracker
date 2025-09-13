{{--
    Archivo: resources/views/parent/payments/index.blade.php
    Roles: parent
    Rutas necesarias: Route::get('parent/payments', [ParentPaymentController::class, 'index'])->name('parent.payments.index')
--}}

@extends('layouts.app')

@section('title', 'Historial de Pagos')

@section('breadcrumbs')
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Inicio
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('parent.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Dashboard de Padre
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Historial de Pagos</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Historial de Pagos</h1>
            <p class="mt-2 text-sm text-gray-600">Consulta el historial de pagos por el transporte escolar de tus hijos</p>
        </div>

        <!-- Resumen de Pagos -->
        <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-credit-card text-2xl text-green-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Pagado</dt>
                                <dd class="text-lg font-medium text-gray-900">${{ number_format($totalPaid ?? 0, 0, ',', '.') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-2xl text-yellow-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pendientes</dt>
                                <dd class="text-lg font-medium text-gray-900">${{ number_format($totalPending ?? 0, 0, ',', '.') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Este Mes</dt>
                                <dd class="text-lg font-medium text-gray-900">${{ number_format($thisMonth ?? 0, 0, ',', '.') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-receipt text-2xl text-purple-600"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Facturas</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $totalInvoices ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white shadow rounded-lg mb-6">
            <div class="p-6">
                <form method="GET" action="{{ route('payments.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                        <div>
                            <label for="student_filter" class="block text-sm font-medium text-gray-700 mb-2">
                                Filtrar por Hijo
                            </label>
                            <select
                                name="student_filter"
                                id="student_filter"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="">Todos los hijos</option>
                                @foreach($students ?? [] as $student)
                                    <option value="{{ $student->student_id }}" {{ request('student_filter') == $student->student_id ? 'selected' : '' }}>
                                        {{ $student->given_name }} {{ $student->family_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="status_filter" class="block text-sm font-medium text-gray-700 mb-2">
                                Estado del Pago
                            </label>
                            <select
                                name="status_filter"
                                id="status_filter"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="">Todos los estados</option>
                                <option value="paid" {{ request('status_filter') == 'paid' ? 'selected' : '' }}>Pagado</option>
                                <option value="pending" {{ request('status_filter') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="failed" {{ request('status_filter') == 'failed' ? 'selected' : '' }}>Fallido</option>
                                <option value="refunded" {{ request('status_filter') == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                            </select>
                        </div>

                        <div>
                            <label for="date_filter" class="block text-sm font-medium text-gray-700 mb-2">
                                Período
                            </label>
                            <select
                                name="date_filter"
                                id="date_filter"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                                <option value="">Todos los períodos</option>
                                <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>Este mes</option>
                                <option value="last_month" {{ request('date_filter') == 'last_month' ? 'selected' : '' }}>Mes pasado</option>
                                <option value="this_year" {{ request('date_filter') == 'this_year' ? 'selected' : '' }}>Este año</option>
                                <option value="last_year" {{ request('date_filter') == 'last_year' ? 'selected' : '' }}>Año pasado</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-search mr-2"></i>
                                Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Pagos -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Historial de Transacciones</h3>
            </div>

            @if($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Factura
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hijo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Concepto
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Monto
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">#{{ $payment->invoice_number ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->payment_id }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                    <span class="text-xs font-medium text-indigo-600">
                                                        {{ substr($payment->subscription->transportContract->student->given_name ?? 'N', 0, 1) }}{{ substr($payment->subscription->transportContract->student->family_name ?? 'A', 0, 1) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $payment->subscription->transportContract->student->given_name ?? 'N/A' }} {{ $payment->subscription->transportContract->student->family_name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $payment->subscription->transportContract->pickupRoute->route_name ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $payment->description ?? 'Transporte escolar' }}</div>
                                        <div class="text-sm text-gray-500">{{ $payment->subscription->transportContract->provider->display_name ?? 'N/A' }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">${{ number_format($payment->amount_total, 0, ',', '.') }}</div>
                                        @if($payment->platform_fee > 0)
                                            <div class="text-sm text-gray-500">+ Comisión: ${{ number_format($payment->platform_fee, 0, ',', '.') }}</div>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <x-badge
                                            type="{{ $payment->payment_status }}"
                                            text="{{ ucfirst($payment->payment_status) }}"
                                        />
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y') : 'N/A' }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('payments.show', $payment) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($payment->payment_status === 'pending')
                                                <a href="{{ route('payments.edit', $payment) }}" class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-credit-card"></i>
                                                </a>
                                            @endif

                                            @if($payment->payment_status === 'paid')
                                                <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-download"></i>
                                                </a>
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
                    <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay pagos registrados</h3>
                    <p class="text-gray-500 mb-6">Aún no se han generado facturas de pago para el transporte escolar.</p>
                    <a href="{{ route('parent.contracts') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-clipboard-list mr-2"></i>
                        Ver Contratos
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
