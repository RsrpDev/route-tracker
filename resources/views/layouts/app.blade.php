<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Route Tracker')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>
<body class="h-full bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900">🚌 Route Tracker</h1>
                </div>

                @auth
                    <!-- Navegación para usuarios autenticados -->
                    <nav class="flex space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-home mr-1"></i>Inicio
                        </a>

                        @if(auth()->user()->account_type === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-tachometer-alt mr-1"></i>Admin Dashboard
                            </a>
                            <!-- Menú desplegable para administradores -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-cogs mr-1"></i>Gestión
                                    <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('admin.accounts.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-users mr-2"></i>Cuentas
                                    </a>
                                    <a href="{{ route('admin.routes') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-route mr-2"></i>Rutas
                                    </a>
                                    <a href="{{ route('admin.students') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user-graduate mr-2"></i>Estudiantes
                                    </a>
                                    <a href="{{ route('admin.subscriptions') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-clipboard-list mr-2"></i>Contratos
                                    </a>
                                </div>
                            </div>
                        @elseif(auth()->user()->account_type === 'provider')
                            <a href="{{ route('provider.dashboard.by.type') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-truck mr-1"></i>Provider Dashboard
                            </a>
                            <!-- Menú desplegable para proveedores -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-cogs mr-1"></i>Gestión
                                    <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('provider.routes') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-route mr-2"></i>Mis Rutas
                                    </a>
                                    <a href="{{ route('provider.drivers') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user-tie mr-2"></i>Conductores
                                    </a>
                                    <a href="{{ route('provider.driver.vehicles') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-bus mr-2"></i>Vehículos
                                    </a>
                                    <a href="{{ route('provider.subscriptions') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-clipboard-list mr-2"></i>Contratos
                                    </a>
                                </div>
                            </div>
                        @elseif(auth()->user()->account_type === 'parent')
                            <a href="{{ route('parent.dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-users mr-1"></i>Parent Dashboard
                            </a>
                            <!-- Menú desplegable para padres -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-cogs mr-1"></i>Gestión
                                    <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50">
                                    <!-- Gestión de Hijos -->
                                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b">
                                        Mis Hijos
                                    </div>
                                    <a href="{{ route('students.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user-graduate mr-2"></i>Ver Todos los Hijos
                                    </a>
                                    <a href="{{ route('students.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-plus mr-2"></i>Agregar Hijo
                                    </a>

                                    <!-- Gestión de Contratos -->
                                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b mt-2">
                                        Contratos
                                    </div>
                                    <a href="{{ route('parent.contracts') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-clipboard-list mr-2"></i>Mis Contratos
                                    </a>
                                    <a href="{{ route('parent.provider-selection.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-search mr-2"></i>Buscar Conductores
                                    </a>

                                    <!-- Gestión de Pagos -->
                                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b mt-2">
                                        Pagos
                                    </div>
                                    <a href="{{ route('payments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-receipt mr-2"></i>Historial de Pagos
                                    </a>
                                    <a href="{{ route('payments.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-credit-card mr-2"></i>Realizar Pago
                                    </a>

                                    <!-- Gestión de Rutas -->
                                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b mt-2">
                                        Rutas
                                    </div>
                                    <a href="{{ route('parent.routes') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-route mr-2"></i>Ver Rutas
                                    </a>
                                </div>
                            </div>
                        @elseif(auth()->user()->account_type === 'school')
                            <a href="{{ route('school.dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-school mr-1"></i>School Dashboard
                            </a>
                            <!-- Menú desplegable para escuelas -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-cogs mr-1"></i>Gestión
                                    <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('school.students') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user-graduate mr-2"></i>Estudiantes
                                    </a>
                                    <a href="{{ route('subscriptions.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-clipboard-list mr-2"></i>Contratos
                                    </a>
                                    <a href="{{ route('school.routes') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-route mr-2"></i>Rutas
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(auth()->user()->account_type === 'admin')
                            <a href="{{ route('settings.profile') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-cog mr-1"></i>Configuración
                            </a>
                        @elseif(auth()->user()->account_type === 'provider')
                            <a href="{{ route('settings.profile') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-cog mr-1"></i>Configuración
                            </a>
                        @elseif(auth()->user()->account_type === 'parent')
                            <a href="{{ route('settings.profile') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-cog mr-1"></i>Configuración
                            </a>
                        @elseif(auth()->user()->account_type === 'school')
                            <a href="{{ route('settings.profile') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-cog mr-1"></i>Configuración
                            </a>
                        @endif

                        <!-- Menú de usuario -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-user-circle mr-1"></i>
                                {{ auth()->user()->full_name }}
                                <i class="fas fa-chevron-down ml-1"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <div class="px-4 py-2 text-xs text-gray-500 border-b">
                                    {{ ucfirst(auth()->user()->account_type) }}
                                </div>
                                <a href="{{ route('settings.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-edit mr-2"></i>Perfil
                                </a>
                                <a href="{{ route('settings.password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-key mr-2"></i>Contraseña
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </nav>
                @else
                    <!-- Navegación para invitados -->
                    <nav class="flex space-x-4">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Inicio</a>
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">Registrarse</a>
                    </nav>
                @endauth
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('breadcrumbs')
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
            <div class="text-center text-gray-500 text-sm">
                &copy; 2024 Route Tracker. Sistema desarrollado con Laravel y Sanctum.
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
