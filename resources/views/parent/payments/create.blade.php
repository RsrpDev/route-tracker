{{--
    Archivo: resources/views/parent/payments/create.blade.php
    Roles: parent
    Rutas necesarias: Route::get('parent/payments/create', [ParentPaymentController::class, 'create'])->name('payments.create')
--}}

@extends('layouts.app')

@section('title', 'Realizar Pago Manual')

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
                    <span class="text-sm font-medium text-gray-500">Realizar Pago Manual</span>
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
                    <h1 class="text-3xl font-bold text-gray-900">Realizar Pago Manual</h1>
                    <p class="mt-2 text-sm text-gray-600">Registra un pago manual para una suscripción activa</p>
                </div>
                <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver al Historial
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
                        <form method="POST" action="{{ route('payments.store') }}">
                            @csrf

                            <div class="space-y-6">
                                <!-- Selección de Suscripción -->
                                <div>
                                    <label for="subscription_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Suscripción <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        name="subscription_id"
                                        id="subscription_id"
                                        required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('subscription_id') border-red-300 @enderror"
                                    >
                                        <option value="">Selecciona una suscripción</option>
                                        @foreach($subscriptions as $subscription)
                                            <option value="{{ $subscription->subscription_id }}" {{ old('subscription_id') == $subscription->subscription_id ? 'selected' : '' }}>
                                                {{ $subscription->transportContract->student->given_name }} {{ $subscription->transportContract->student->family_name }} -
                                                {{ $subscription->transportContract->pickupRoute->route_name ?? 'N/A' }} -
                                                ${{ number_format($subscription->monthly_fee, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subscription_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Monto -->
                                <div>
                                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                        Monto <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input
                                            type="number"
                                            name="amount"
                                            id="amount"
                                            required
                                            min="0"
                                            step="0.01"
                                            class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('amount') border-red-300 @enderror"
                                            placeholder="0.00"
                                            value="{{ old('amount') }}"
                                        >
                                    </div>
                                    @error('amount')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
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
                                        <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>Tarjeta de Crédito</option>
                                        <option value="debit_card" {{ old('payment_method') === 'debit_card' ? 'selected' : '' }}>Tarjeta de Débito</option>
                                        <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                        <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Efectivo</option>
                                        <option value="check" {{ old('payment_method') === 'check' ? 'selected' : '' }}>Cheque</option>
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
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Botones -->
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('payments.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Crear Pago
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Información de Suscripciones -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Suscripciones Activas</h3>
                    </div>
                    <div class="p-6">
                        @if($subscriptions->count() > 0)
                            <div class="space-y-4">
                                @foreach($subscriptions as $subscription)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $subscription->transportContract->student->given_name }} {{ $subscription->transportContract->student->family_name }}
                                                </h4>
                                                <p class="text-sm text-gray-500">
                                                    {{ $subscription->transportContract->pickupRoute->route_name ?? 'N/A' }}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">
                                                    ${{ number_format($subscription->monthly_fee, 0, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-gray-500">mensual</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-2xl text-yellow-500 mb-2"></i>
                                <p class="text-sm text-gray-500">No hay suscripciones activas</p>
                                <a href="{{ route('parent.contracts') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Ver contratos
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Información de Seguridad -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Información</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Los pagos manuales se registran como pendientes hasta que sean confirmados por el sistema.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
