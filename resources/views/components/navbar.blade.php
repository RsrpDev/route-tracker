<nav class="sticky top-0 z-10 bg-white shadow">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <!-- Botón de sidebar para móvil -->
                <div class="flex items-center lg:hidden">
                    <button @click="sidebarOpen = true" type="button" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Abrir sidebar</span>
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <!-- Logo/Título -->
                <div class="flex flex-shrink-0 items-center">
                    <h1 class="text-xl font-bold text-gray-900">Route Tracker</h1>
                </div>
            </div>

            <!-- Menú derecho -->
            <div class="flex items-center">
                <!-- Notificaciones -->
                <button type="button" class="rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span class="sr-only">Ver notificaciones</span>
                    <i class="fas fa-bell"></i>
                </button>

                <!-- Dropdown del usuario -->
                <div class="relative ml-3" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button" class="flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Abrir menú de usuario</span>
                            <div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">
                                    {{ substr(auth()->user()->full_name, 0, 1) }}
                                </span>
                            </div>
                            <span class="ml-3 hidden md:block text-sm font-medium text-gray-700">
                                {{ auth()->user()->full_name }}
                            </span>
                            <i class="fas fa-chevron-down ml-1 text-gray-400"></i>
                        </button>
                    </div>

                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                         role="menu"
                         aria-orientation="vertical"
                         aria-labelledby="user-menu-button"
                         tabindex="-1"
                         x-cloak>

                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                            <i class="fas fa-user mr-2"></i>
                            Mi Perfil
                        </a>

                        <a href="{{ route('auth.change-password') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                            <i class="fas fa-key mr-2"></i>
                            Cambiar Contraseña
                        </a>

                        <hr class="my-1">

                        <form method="POST" action="{{ route('auth.logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" tabindex="-1">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
