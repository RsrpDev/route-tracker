@extends('layouts.app')

@section('title', 'Detalles de Suscripción')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detalles de Suscripción</h1>
                <p class="text-gray-600 mt-2">Información completa de la suscripción #{{ $subscription->subscription_id }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.subscriptions') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
                <a href="{{ route('subscriptions.edit', $subscription) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Editar
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información General</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID de Suscripción</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $subscription->subscription_id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID de Contrato</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $subscription->contract_id }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ciclo de Facturación</label>
                            <p class="mt-1 text-sm text-gray-900 capitalize">{{ $subscription->billing_cycle }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo de Plan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $subscription->payment_plan_type ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre del Plan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $subscription->payment_plan_name ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($subscription->subscription_status === 'active') bg-green-100 text-green-800
                                @elseif($subscription->subscription_status === 'paused') bg-yellow-100 text-yellow-800
                                @elseif($subscription->subscription_status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($subscription->subscription_status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Información Financiera -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Información Financiera</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio Snapshot</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">${{ number_format($subscription->price_snapshot, 2) }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tarifa de Plataforma</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">{{ $subscription->platform_fee_rate }}%</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tasa de Descuento</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $subscription->discount_rate }}%</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                            <p class="mt-1 text-sm text-gray-900 capitalize">{{ $subscription->payment_method ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Fechas Importantes -->
                <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Fechas Importantes</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Inicio del Plan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $subscription->plan_start_date ? $subscription->plan_start_date->format('d/m/Y') : 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Fin del Plan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $subscription->plan_end_date ? $subscription->plan_end_date->format('d/m/Y') : 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Próxima Facturación</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $subscription->next_billing_date ? $subscription->next_billing_date->format('d/m/Y') : 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Renovación Automática</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $subscription->auto_renewal ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $subscription->auto_renewal ? 'Activada' : 'Desactivada' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Acciones Rápidas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>

                    <div class="space-y-3">
                        @if($subscription->subscription_status === 'active')
                            <form method="POST" action="{{ route('subscriptions.suspend', $subscription) }}" class="inline-block w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition duration-200"
                                        onclick="return confirm('¿Estás seguro de que quieres suspender esta suscripción? El servicio se pausará temporalmente.')">
                                    <i class="fas fa-pause mr-2"></i>Suspender Suscripción
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-1">Pausa temporal del servicio</p>
                        @elseif($subscription->subscription_status === 'paused')
                            <form method="POST" action="{{ route('subscriptions.activate', $subscription) }}" class="inline-block w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                    <i class="fas fa-play mr-2"></i>Reactivar Suscripción
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-1">Reanuda el servicio inmediatamente</p>
                        @endif

                        @if($subscription->subscription_status !== 'cancelled')
                            <form method="POST" action="{{ route('subscriptions.cancel', $subscription) }}" class="inline-block w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200"
                                        onclick="return confirm('⚠️ ATENCIÓN: Esta acción cancelará permanentemente la suscripción y desactivará la renovación automática. ¿Estás seguro?')">
                                    <i class="fas fa-times mr-2"></i>Cancelar Suscripción
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-1">Cancelación permanente - Sin renovación automática</p>
                        @endif

                        @if($subscription->subscription_status !== 'cancelled')
                            <form method="POST" action="{{ route('subscriptions.renew', $subscription) }}" class="inline-block w-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200"
                                        onclick="return confirm('¿Renovar suscripción? Se calculará una nueva fecha de facturación basada en el ciclo actual ({{ $subscription->billing_cycle }}).')">
                                    <i class="fas fa-redo mr-2"></i>Renovar Suscripción
                                </button>
                            </form>
                            <p class="text-xs text-gray-500 mt-1">Nueva fecha de facturación: {{ $subscription->billing_cycle }}</p>
                        @endif
                    </div>

                    <!-- Información de Estado -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Estado Actual</h4>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($subscription->subscription_status === 'active') bg-green-100 text-green-800
                                @elseif($subscription->subscription_status === 'paused') bg-yellow-100 text-yellow-800
                                @elseif($subscription->subscription_status === 'cancelled') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($subscription->subscription_status) }}
                            </span>
                            @if($subscription->auto_renewal)
                                <span class="text-xs text-green-600">
                                    <i class="fas fa-sync-alt mr-1"></i>Renovación automática activa
                                </span>
                            @else
                                <span class="text-xs text-gray-500">
                                    <i class="fas fa-ban mr-1"></i>Sin renovación automática
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Historial de Acciones -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Historial de Acciones</h3>

                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-plus text-blue-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Suscripción Creada</p>
                                <p class="text-xs text-gray-500">{{ $subscription->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-edit text-green-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Última Actualización</p>
                                <p class="text-xs text-gray-500">{{ $subscription->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>

                        @if($subscription->next_billing_date)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-check text-purple-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Próxima Facturación</p>
                                <p class="text-xs text-gray-500">{{ $subscription->next_billing_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endif

                        @if($subscription->plan_end_date)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-times text-orange-500"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">Fin del Plan</p>
                                <p class="text-xs text-gray-500">{{ $subscription->plan_end_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Información de Stripe -->
                @if($subscription->stripe_subscription_id || $subscription->stripe_customer_id)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Stripe</h3>

                    <div class="space-y-3">
                        @if($subscription->stripe_subscription_id)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID de Suscripción Stripe</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $subscription->stripe_subscription_id }}</p>
                        </div>
                        @endif

                        @if($subscription->stripe_customer_id)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID de Cliente Stripe</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $subscription->stripe_customer_id }}</p>
                        </div>
                        @endif

                        @if($subscription->stripe_price_id)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID de Precio Stripe</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $subscription->stripe_price_id }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Metadatos -->
                @if($subscription->payment_metadata)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Metadatos de Pago</h3>
                    <div class="bg-gray-50 rounded-lg p-3">
                        <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ $subscription->payment_metadata }}</pre>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Información de Creación -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Información del Sistema</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $subscription->created_at->format('d/m/Y H:i:s') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Última Actualización</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $subscription->updated_at->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
