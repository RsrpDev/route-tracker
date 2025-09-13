@extends('layouts.app')

@section('title', 'Pagos de Contrato')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pagos de Contrato</h1>
                <p class="text-gray-600 mt-2">Historial de pagos para el contrato #{{ $contract->subscription_id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('parent.contracts.show', $contract->subscription_id) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                    ← Volver a Contrato
                </a>
                <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    + Nuevo Pago
                </button>
            </div>
        </div>
    </div>

    <!-- Información del Contrato -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Información del Contrato</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-500">Estudiante</label>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $contract->transportContract->student->first_name }} {{ $contract->transportContract->student->last_name }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Colegio</label>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $contract->transportContract->student->school->school_name }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Proveedor</label>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $contract->transportContract->provider->business_name }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Plan de Pago</label>
                <p class="text-lg font-semibold text-gray-900">{{ $contract->plan_name }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Precio Mensual</label>
                <p class="text-lg font-semibold text-gray-900">
                    ${{ number_format($contract->discounted_price, 0, ',', '.') }}
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Estado</label>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($contract->subscription_status === 'active') bg-green-100 text-green-800
                    @elseif($contract->subscription_status === 'paused') bg-yellow-100 text-yellow-800
                    @elseif($contract->subscription_status === 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($contract->subscription_status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Resumen de Pagos -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pagado</p>
                    <p class="text-2xl font-bold text-gray-900">
                        ${{ number_format($payments->sum('amount_total'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pagos Exitosos</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $payments->where('payment_status', 'paid')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pagos Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $payments->where('payment_status', 'pending')->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pagos Fallidos</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $payments->where('payment_status', 'failed')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Pagos -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Historial de Pagos</h2>
        </div>

        @if($payments->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID de Pago
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Período
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Monto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Método
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha de Pago
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($payments as $payment)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $payment->payment_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->period_start ? $payment->period_start->format('d/m/Y') : 'N/A' }} -
                                    {{ $payment->period_end ? $payment->period_end->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ${{ number_format($payment->amount_total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($payment->payment_status === 'paid') bg-green-100 text-green-800
                                        @elseif($payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($payment->payment_status === 'failed') bg-red-100 text-red-800
                                        @elseif($payment->payment_status === 'refunded') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($payment->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ ucfirst($payment->payment_method) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'No pagado' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('payments.show', $payment->payment_id) }}" class="text-blue-600 hover:text-blue-900">
                                            Ver
                                        </a>
                                        @if($payment->payment_status === 'pending')
                                            <button class="text-green-600 hover:text-green-900">
                                                Pagar
                                            </button>
                                        @endif
                                        @if($payment->payment_status === 'paid')
                                            <button class="text-red-600 hover:text-red-900">
                                                Reembolsar
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
                <p class="mt-1 text-sm text-gray-500">No se encontraron pagos para este contrato.</p>
                <div class="mt-6">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        Crear Primer Pago
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
