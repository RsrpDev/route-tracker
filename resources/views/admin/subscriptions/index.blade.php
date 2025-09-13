@extends('layouts.app')

@section('title', 'Gestión de Suscripciones')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Suscripciones</h1>
                <p class="text-gray-600 mt-2">Administra todas las suscripciones del sistema</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('subscriptions.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-plus mr-2"></i>Nueva Suscripción
                </a>
                <a href="{{ route('subscriptions.export') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-download mr-2"></i>Exportar
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h2>
            <form method="GET" action="{{ route('admin.subscriptions') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Pausado</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirado</option>
                    </select>
                </div>

                <div>
                    <label for="billing_cycle" class="block text-sm font-medium text-gray-700">Ciclo de Facturación</label>
                    <select name="billing_cycle" id="billing_cycle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="monthly" {{ request('billing_cycle') === 'monthly' ? 'selected' : '' }}>Mensual</option>
                        <option value="quarterly" {{ request('billing_cycle') === 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                        <option value="semiannual" {{ request('billing_cycle') === 'semiannual' ? 'selected' : '' }}>Semestral</option>
                        <option value="annual" {{ request('billing_cycle') === 'annual' ? 'selected' : '' }}>Anual</option>
                    </select>
                </div>

                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                           placeholder="ID, contrato..."
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-search mr-2"></i>Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de Suscripciones -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="select-all" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contrato</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ciclo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Próxima Facturación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($subscriptions as $subscription)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="subscription_ids[]" value="{{ $subscription->subscription_id }}"
                                       class="subscription-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $subscription->subscription_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $subscription->contract_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 capitalize">
                                {{ $subscription->billing_cycle }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($subscription->price_snapshot, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($subscription->subscription_status === 'active') bg-green-100 text-green-800
                                    @elseif($subscription->subscription_status === 'paused') bg-yellow-100 text-yellow-800
                                    @elseif($subscription->subscription_status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($subscription->subscription_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('subscriptions.show', $subscription) }}"
                                       class="text-indigo-600 hover:text-indigo-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('subscriptions.edit', $subscription) }}"
                                       class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('subscriptions.destroy', $subscription) }}"
                                          class="inline"
                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta suscripción?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                No se encontraron suscripciones
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginación -->
        @if($subscriptions->hasPages())
        <div class="mt-6">
            {{ $subscriptions->links() }}
        </div>
        @endif

        <!-- Acciones en Lote -->
        @if($subscriptions->count() > 0)
        <div class="mt-6 bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones en Lote</h3>
            <form method="POST" action="{{ route('subscriptions.bulk-update') }}" class="flex space-x-4">
                @csrf
                <input type="hidden" name="subscription_ids" id="bulk-subscription-ids">

                <select name="subscription_status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Seleccionar acción...</option>
                    <option value="active">Activar</option>
                    <option value="paused">Suspender</option>
                    <option value="cancelled">Cancelar</option>
                </select>

                <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    Aplicar a Seleccionados
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('select-all');
    const subscriptionCheckboxes = document.querySelectorAll('.subscription-checkbox');
    const bulkSubscriptionIds = document.getElementById('bulk-subscription-ids');

    selectAllCheckbox.addEventListener('change', function() {
        subscriptionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkIds();
    });

    subscriptionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkIds();
            updateSelectAllState();
        });
    });

    function updateBulkIds() {
        const selectedIds = Array.from(subscriptionCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        bulkSubscriptionIds.value = JSON.stringify(selectedIds);
    }

    function updateSelectAllState() {
        const checkedCount = Array.from(subscriptionCheckboxes).filter(cb => cb.checked).length;
        selectAllCheckbox.checked = checkedCount === subscriptionCheckboxes.length;
        selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < subscriptionCheckboxes.length;
    }
});
</script>
@endsection
