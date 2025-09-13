@extends('layouts.app')

@section('title', 'Crear Suscripción')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Crear Nueva Suscripción</h1>
                <p class="text-gray-600 mt-2">Registra una nueva suscripción en el sistema</p>
            </div>
            <a href="{{ route('admin.subscriptions') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('subscriptions.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información Básica -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900">Información Básica</h2>

                        <div>
                            <label for="contract_id" class="block text-sm font-medium text-gray-700">ID de Contrato *</label>
                            <input type="text" name="contract_id" id="contract_id" value="{{ old('contract_id') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">ID del contrato de transporte asociado</p>
                        </div>

                        <div>
                            <label for="billing_cycle" class="block text-sm font-medium text-gray-700">Ciclo de Facturación *</label>
                            <select name="billing_cycle" id="billing_cycle"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Seleccionar...</option>
                                <option value="monthly" {{ old('billing_cycle') === 'monthly' ? 'selected' : '' }}>Mensual</option>
                                <option value="quarterly" {{ old('billing_cycle') === 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                                <option value="semiannual" {{ old('billing_cycle') === 'semiannual' ? 'selected' : '' }}>Semestral</option>
                                <option value="annual" {{ old('billing_cycle') === 'annual' ? 'selected' : '' }}>Anual</option>
                            </select>
                        </div>

                        <div>
                            <label for="subscription_status" class="block text-sm font-medium text-gray-700">Estado *</label>
                            <select name="subscription_status" id="subscription_status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Seleccionar...</option>
                                <option value="active" {{ old('subscription_status') === 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="paused" {{ old('subscription_status') === 'paused' ? 'selected' : '' }}>Pausado</option>
                                <option value="cancelled" {{ old('subscription_status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                <option value="expired" {{ old('subscription_status') === 'expired' ? 'selected' : '' }}>Expirado</option>
                            </select>
                        </div>
                    </div>

                    <!-- Información Financiera -->
                    <div class="space-y-4">
                        <h2 class="text-xl font-semibold text-gray-900">Información Financiera</h2>

                        <div>
                            <label for="price_snapshot" class="block text-sm font-medium text-gray-700">Precio Snapshot *</label>
                            <input type="number" name="price_snapshot" id="price_snapshot" value="{{ old('price_snapshot') }}"
                                   step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Precio fijo al momento de crear la suscripción</p>
                        </div>

                        <div>
                            <label for="platform_fee_rate" class="block text-sm font-medium text-gray-700">Tarifa de Plataforma *</label>
                            <input type="number" name="platform_fee_rate" id="platform_fee_rate" value="{{ old('platform_fee_rate') }}"
                                   step="0.01" min="0" max="100"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            <p class="mt-1 text-sm text-gray-500">Porcentaje de comisión de la plataforma (0-100)</p>
                        </div>

                        <div>
                            <label for="next_billing_date" class="block text-sm font-medium text-gray-700">Próxima Facturación *</label>
                            <input type="date" name="next_billing_date" id="next_billing_date" value="{{ old('next_billing_date') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="mt-8 space-y-4">
                    <h2 class="text-xl font-semibold text-gray-900">Información Adicional</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="payment_plan_type" class="block text-sm font-medium text-gray-700">Tipo de Plan</label>
                            <input type="text" name="payment_plan_type" id="payment_plan_type" value="{{ old('payment_plan_type') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="payment_plan_name" class="block text-sm font-medium text-gray-700">Nombre del Plan</label>
                            <input type="text" name="payment_plan_name" id="payment_plan_name" value="{{ old('payment_plan_name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="payment_plan_description" class="block text-sm font-medium text-gray-700">Descripción del Plan</label>
                            <textarea name="payment_plan_description" id="payment_plan_description" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('payment_plan_description') }}</textarea>
                        </div>

                        <div>
                            <label for="discount_rate" class="block text-sm font-medium text-gray-700">Tasa de Descuento</label>
                            <input type="number" name="discount_rate" id="discount_rate" value="{{ old('discount_rate', 0) }}"
                                   step="0.01" min="0" max="100"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Método de Pago</label>
                            <select name="payment_method" id="payment_method"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccionar...</option>
                                <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>Tarjeta de Crédito</option>
                                <option value="debit_card" {{ old('payment_method') === 'debit_card' ? 'selected' : '' }}>Tarjeta Débito</option>
                                <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Transferencia Bancaria</option>
                                <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Efectivo</option>
                                <option value="pse" {{ old('payment_method') === 'pse' ? 'selected' : '' }}>PSE</option>
                            </select>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="auto_renewal" id="auto_renewal" value="1"
                                   {{ old('auto_renewal') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <label for="auto_renewal" class="ml-2 block text-sm text-gray-900">
                                Renovación Automática
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.subscriptions') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-save mr-2"></i>Crear Suscripción
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
