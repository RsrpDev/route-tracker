{{--
    Archivo: resources/views/admin/verification/index.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/verification', [AdminVerificationController::class, 'index'])
--}}

@extends('layouts.app')

@section('title', 'Verificación de Cuentas - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Verificación de Cuentas</h1>
        <p class="text-gray-600">Supervisión y autorización de registros de proveedores y escuelas</p>
    </div>

    <!-- Estadísticas de verificación -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($pendingAccounts->total()) }}</p>
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
                    <p class="text-sm font-medium text-gray-500">Verificadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($verifiedAccounts->count()) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Rechazadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($rejectedAccounts->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas -->
    <div class="mb-6">
        <nav class="flex space-x-8" aria-label="Tabs">
            <button onclick="showTab('pending')" id="pending-tab" class="tab-button active py-2 px-1 border-b-2 border-orange-500 font-medium text-sm text-orange-600">
                Pendientes ({{ $pendingAccounts->total() }})
            </button>
            <button onclick="showTab('verified')" id="verified-tab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Verificadas ({{ $verifiedAccounts->count() }})
            </button>
            <button onclick="showTab('rejected')" id="rejected-tab" class="tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Rechazadas ({{ $rejectedAccounts->count() }})
            </button>
        </nav>
    </div>

    <!-- Contenido de pestañas -->
    <!-- Pestaña Pendientes -->
    <div id="pending-content" class="tab-content">
        <div class="bg-white shadow-md rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Cuentas Pendientes de Verificación</h3>
            </div>
            @if($pendingAccounts->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($pendingAccounts as $account)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                        <span class="text-orange-600 font-medium text-sm">
                                            {{ strtoupper(substr($account->account_type, 0, 1)) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $account->full_name }}</p>
                                    <p class="text-sm text-gray-500">{{ ucfirst($account->account_type) }} • {{ $account->email }}</p>
                                    <p class="text-xs text-gray-400">Registrado {{ $account->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.verification.show', $account) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    Verificar
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $pendingAccounts->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cuentas pendientes</h3>
                    <p class="mt-1 text-sm text-gray-500">Todas las cuentas han sido verificadas.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pestaña Verificadas -->
    <div id="verified-content" class="tab-content hidden">
        <div class="bg-white shadow-md rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Cuentas Verificadas</h3>
            </div>
            @if($verifiedAccounts->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($verifiedAccounts as $account)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $account->full_name }}</p>
                                    <p class="text-sm text-gray-500">{{ ucfirst($account->account_type) }} • {{ $account->email }}</p>
                                    <p class="text-xs text-gray-400">Verificado {{ $account->verified_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Verificado
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cuentas verificadas</h3>
                    <p class="mt-1 text-sm text-gray-500">Las cuentas verificadas aparecerán aquí.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pestaña Rechazadas -->
    <div id="rejected-content" class="tab-content hidden">
        <div class="bg-white shadow-md rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Cuentas Rechazadas</h3>
            </div>
            @if($rejectedAccounts->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($rejectedAccounts as $account)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $account->full_name }}</p>
                                    <p class="text-sm text-gray-500">{{ ucfirst($account->account_type) }} • {{ $account->email }}</p>
                                    <p class="text-xs text-gray-400">Rechazado {{ $account->verified_at->diffForHumans() }}</p>
                                    @if($account->verification_notes)
                                        <p class="text-xs text-red-600 mt-1">{{ Str::limit($account->verification_notes, 100) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Rechazado
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cuentas rechazadas</h3>
                    <p class="mt-1 text-sm text-gray-500">Las cuentas rechazadas aparecerán aquí.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function showTab(tabName) {
    // Ocultar todos los contenidos
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Remover clase active de todos los tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-orange-500', 'text-orange-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    // Mostrar contenido seleccionado
    document.getElementById(tabName + '-content').classList.remove('hidden');

    // Activar tab seleccionado
    const activeTab = document.getElementById(tabName + '-tab');
    activeTab.classList.add('active', 'border-orange-500', 'text-orange-600');
    activeTab.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endpush
@endsection
