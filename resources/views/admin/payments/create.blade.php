{{--
    Archivo: resources/views/admin/payments/create.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/payments/create', [PaymentController::class, 'create'])
--}}

@extends('layouts.app')

@section('title', 'Crear Nuevo Pago - Route Tracker')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Pago</h1>
                <p class="text-gray-600">Registrar un nuevo pago en el sistema</p>
            </div>
            <div>
                <a href="{{ route('admin.payments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Suscripción -->
                <div class="md:col-span-2">
                    <label for="subscription_id" class="block text-sm font-medium text-gray-700">Suscripción *</label>
                    <select name="subscription_id" id="subscription_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Seleccionar suscripción</option>
                        @foreach(\App\Models\Subscription::with('transportContract.student')->get() as $subscription)
                            <option value="{{ $subscription->subscription_id }}" {{ old('subscription_id') == $subscription->subscription_id ? 'selected' : '' }}>
                                #{{ $subscription->subscription_id }} -
                                @if($subscription->transportContract && $subscription->transportContract->student)
                                    {{ $subscription->transportContract->student->first_name }} {{ $subscription->transportContract->student->last_name }}
                                @else
                                    Suscripción sin estudiante
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('subscription_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Período de Inicio -->
                <div>
                    <label for="period_start" class="block text-sm font-medium text-gray-700">Período de Inicio *</label>
                    <input type="date" name="period_start" id="period_start" value="{{ old('period_start') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    @error('period_start')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Período de Fin -->
                <div>
                    <label for="period_end" class="block text-sm font-medium text-gray-700">Período de Fin *</label>
                    <input type="date" name="period_end" id="period_end" value="{{ old('period_end') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    @error('period_end')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Monto Total -->
                <div>
                    <label for="amount_total" class="block text-sm font-medium text-gray-700">Monto Total *</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="amount_total" id="amount_total" value="{{ old('amount_total') }}" step="0.01" min="0" class="pl-7 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    @error('amount_total')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tarifa Plataforma -->
                <div>
                    <label for="platform_fee" class="block text-sm font-medium text-gray-700">Tarifa Plataforma *</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="platform_fee" id="platform_fee" value="{{ old('platform_fee') }}" step="0.01" min="0" class="pl-7 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    @error('platform_fee')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Monto Proveedor -->
                <div>
                    <label for="provider_amount" class="block text-sm font-medium text-gray-700">Monto Proveedor *</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="provider_amount" id="provider_amount" value="{{ old('provider_amount') }}" step="0.01" min="0" class="pl-7 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    </div>
                    @error('provider_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Método de Pago -->
                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700">Método de Pago *</label>
                    <select name="payment_method" id="payment_method" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Seleccionar método</option>
                        <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>Tarjeta de Crédito</option>
                        <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>Tarjeta de Débito</option>
                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Transferencia Bancaria</option>
                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Efectivo</option>
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado del Pago -->
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700">Estado del Pago *</label>
                    <select name="payment_status" id="payment_status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Seleccionar estado</option>
                        <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="processing" {{ old('payment_status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                        <option value="completed" {{ old('payment_status') == 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Fallido</option>
                        <option value="cancelled" {{ old('payment_status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        <option value="refunded" {{ old('payment_status') == 'refunded' ? 'selected' : '' }}>Reembolsado</option>
                    </select>
                    @error('payment_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.payments.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Crear Pago
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validación de fechas
document.getElementById('period_end').addEventListener('change', function() {
    const startDate = document.getElementById('period_start').value;
    const endDate = this.value;

    if (startDate && endDate && endDate <= startDate) {
        alert('La fecha de fin debe ser posterior a la fecha de inicio');
        this.value = '';
    }
});

// Cálculo automático del monto proveedor
document.getElementById('amount_total').addEventListener('input', calculateProviderAmount);
document.getElementById('platform_fee').addEventListener('input', calculateProviderAmount);

function calculateProviderAmount() {
    const total = parseFloat(document.getElementById('amount_total').value) || 0;
    const fee = parseFloat(document.getElementById('platform_fee').value) || 0;
    const providerAmount = total - fee;

    if (providerAmount >= 0) {
        document.getElementById('provider_amount').value = providerAmount.toFixed(2);
    }
}
</script>
@endsection
