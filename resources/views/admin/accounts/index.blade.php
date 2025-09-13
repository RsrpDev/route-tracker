{{--
    Archivo: resources/views/admin/accounts/index.blade.php
    Roles: admin
    Rutas necesarias: Route::get('admin/accounts', [ResourceController::class, 'accounts'])
--}}

@extends('layouts.app')

@section('title', 'Gestión de Cuentas - Route Tracker')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Cuentas</h1>
                <p class="text-gray-600">Administración de todas las cuentas del sistema</p>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Cuentas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($accounts->total()) }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($accounts->where('verification_status', 'verified')->count()) }}</p>
                </div>
            </div>
        </div>

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
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($accounts->where('verification_status', 'pending')->count()) }}</p>
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
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($accounts->where('verification_status', 'rejected')->count()) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filtros y Búsqueda</h3>
        </div>
        <div class="px-6 py-4">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nombre, email..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="account_type" class="block text-sm font-medium text-gray-700">Tipo de Cuenta</label>
                    <select name="account_type" id="account_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="admin" {{ request('account_type') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="provider" {{ request('account_type') == 'provider' ? 'selected' : '' }}>Proveedor</option>
                        <option value="parent" {{ request('account_type') == 'parent' ? 'selected' : '' }}>Padre/Madre</option>
                        <option value="school" {{ request('account_type') == 'school' ? 'selected' : '' }}>Escuela</option>
                    </select>
                </div>
                <div>
                    <label for="verification_status" class="block text-sm font-medium text-gray-700">Estado de Verificación</label>
                    <select name="verification_status" id="verification_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Todos</option>
                        <option value="verified" {{ request('verification_status') == 'verified' ? 'selected' : '' }}>Verificado</option>
                        <option value="pending" {{ request('verification_status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="rejected" {{ request('verification_status') == 'rejected' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Lista de cuentas -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Lista de Cuentas</h3>
        </div>
        @if($accounts->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cuenta</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perfil</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verificación</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($accounts as $account)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <span class="text-gray-600 font-medium text-sm">
                                                @if($account->account_type === 'admin') 👑
                                                @elseif($account->account_type === 'provider') 🚛
                                                @elseif($account->account_type === 'parent') 👨‍👩‍👧‍👦
                                                @elseif($account->account_type === 'school') 🏫
                                                @else 👤
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $account->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $account->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($account->account_type === 'admin') bg-purple-100 text-purple-800
                                    @elseif($account->account_type === 'provider') bg-blue-100 text-blue-800
                                    @elseif($account->account_type === 'parent') bg-green-100 text-green-800
                                    @elseif($account->account_type === 'school') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($account->account_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($account->provider)
                                    <div class="text-sm">{{ $account->provider->display_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $account->provider->provider_type }}</div>
                                @elseif($account->parentProfile)
                                    <div class="text-sm">Padre/Madre</div>
                                    <div class="text-xs text-gray-500">{{ $account->parentProfile->phone_number ?? 'Sin teléfono' }}</div>
                                @elseif($account->school)
                                    <div class="text-sm">{{ $account->school->school_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $account->school->dane_code ?? 'Sin código DANE' }}</div>
                                @else
                                    <span class="text-gray-400">Sin perfil</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($account->verification_status === 'verified') bg-green-100 text-green-800
                                    @elseif($account->verification_status === 'rejected') bg-red-100 text-red-800
                                    @else bg-orange-100 text-orange-800 @endif">
                                    {{ ucfirst($account->verification_status ?? 'pending') }}
                                </span>
                                @if($account->verified_at)
                                    <div class="text-xs text-gray-500 mt-1">{{ $account->verified_at->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $account->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.accounts.show', $account) }}" class="text-blue-600 hover:text-blue-900">
                                        Ver
                                    </a>
                                    @if($account->verification_status === 'pending')
                                        <a href="{{ route('admin.verification.show', $account) }}" class="text-orange-600 hover:text-orange-900">
                                            Verificar
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $accounts->appends(request()->query())->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay cuentas</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron cuentas con los criterios especificados.</p>
            </div>
        @endif
    </div>
</div>
@endsection
