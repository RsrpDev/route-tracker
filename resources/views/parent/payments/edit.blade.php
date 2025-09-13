{{--
    Archivo: resources/views/parent/payments/edit.blade.php
    Roles: parent
    Rutas necesarias: Route::get('parent/payments/{payment}/edit', [ParentPaymentController::class, 'edit'])->name('payments.edit')
--}}

@extends('layouts.app')

@section('title', 'Realizar Pago')

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
                    <a href="{{ route('payments.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                        Historial de Pagos
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Realizar Pago</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Realizar Pago</h1>
                    <p class="mt-2 text-sm text-gray-600">Completa el pago del transporte escolar</p>
                </div>
                <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Formulario de Pago -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Información del Pago</h3>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="{{ route('payments.update', $payment) }}">
                            @csrf
                            @method('PUT')

                            <div class="space-y-6">
                                <!-- Información del Pago -->
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Número de Factura
                                        </label>
                                        <input type="text" value="#{{ $payment->invoice_number ?? 'N/A' }}" readonly class="block w-full rounded-md border-gray-300 bg-gray-50 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Monto a Pagar
                                        </label>
                                        <input type="text" value="${{ number_format($payment->amount_total, 0, ',', '.') }}" readonly class="block w-full rounded-md border-gray-300 bg-gray-50 text-sm">
                                    </div>
                                </div>

                                <!-- Método de Pago -->
                                <div>
                                    <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                                        Método de Pago <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        name="payment_method"
                                        id="payment_method"
                                        required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('payment_method') border-red-300 @enderror"
                                    >
                                        <option value="">Selecciona un método de pago</option>
                                        <option value="credit_card" {{ old('payment_method', $payment->payment_method) === 'credit_card' ? 'selected' : '' }}>Tarjeta de Crédito</option>
                                        <option value="debit_card" {{ old('payment_method', $payment->payment_method) === 'debit_card' ? 'selected' : '' }}>Tarjeta de Débito</option>
                                        <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) === 'bank_transfer' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                        <option value="cash" {{ old('payment_method', $payment->payment_method) === 'cash' ? 'selected' : '' }}>Efectivo</option>
                                        <option value="check" {{ old('payment_method', $payment->payment_method) === 'check' ? 'selected' : '' }}>Cheque</option>
                                    </select>
                                    @error('payment_method')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Descripción -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Descripción (Opcional)
                                    </label>
                                    <textarea
                                        name="description"
                                        id="description"
                                        rows="3"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-300 @enderror"
                                        placeholder="Información adicional sobre el pago..."
                                    >{{ old('description', $payment->description) }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Botones -->
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('payments.show', $payment) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Confirmar Pago
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Información del Contrato -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Detalles del Contrato</h3>
                    </div>
                    <div class="p-6">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estudiante</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $payment->subscription->transportContract->student->given_name }} {{ $payment->subscription->transportContract->student->family_name }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Proveedor</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->subscription->transportContract->provider->display_name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ruta</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $payment->subscription->transportContract->pickupRoute->route_name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Período</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $payment->subscription->billing_period ?? 'N/A' }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Información de Seguridad -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-shield-alt text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Pago Seguro</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Tu información de pago está protegida con encriptación SSL. No almacenamos datos de tarjetas de crédito.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
