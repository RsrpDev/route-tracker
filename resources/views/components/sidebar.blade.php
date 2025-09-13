<div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4">
    <div class="flex h-16 shrink-0 items-center">
        <h2 class="text-lg font-semibold text-gray-900">Navegación</h2>
    </div>

    <nav class="flex flex-1 flex-col">
        <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <!-- Dashboard -->
            <li>
                <ul role="list" class="-mx-2 space-y-1">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <i class="fas fa-tachometer-alt text-gray-400 group-hover:text-indigo-600"></i>
                            Dashboard
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Navegación según tipo de cuenta -->
            @if(auth()->user()->account_type === 'admin')
                <!-- Menú de Administrador -->
                <li>
                    <h3 class="text-xs font-semibold leading-6 text-gray-500 uppercase tracking-wider">Administración</h3>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('admin.accounts.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-users text-gray-400 group-hover:text-indigo-600"></i>
                                Cuentas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.providers.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-truck text-gray-400 group-hover:text-indigo-600"></i>
                                Proveedores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('schools.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-school text-gray-400 group-hover:text-indigo-600"></i>
                                Escuelas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.routes') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-route text-gray-400 group-hover:text-indigo-600"></i>
                                Rutas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.students') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-user-graduate text-gray-400 group-hover:text-indigo-600"></i>
                                Estudiantes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.subscriptions') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-clipboard-list text-gray-400 group-hover:text-indigo-600"></i>
                                Suscripciones
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('payments.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-credit-card text-gray-400 group-hover:text-indigo-600"></i>
                                Pagos
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if(auth()->user()->account_type === 'parent')
                <!-- Menú de Padre -->
                <li>
                    <h3 class="text-xs font-semibold leading-6 text-gray-500 uppercase tracking-wider">Gestión Familiar</h3>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('parent.students.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-child text-gray-400 group-hover:text-indigo-600"></i>
                                Mis Hijos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('parent.subscriptions') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-graduation-cap text-gray-400 group-hover:text-indigo-600"></i>
                                Contratos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('payments.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-credit-card text-gray-400 group-hover:text-indigo-600"></i>
                                Mis Pagos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('parent.routes.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-route text-gray-400 group-hover:text-indigo-600"></i>
                                Ver Rutas
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if(auth()->user()->account_type === 'provider')
                <!-- Menú de Proveedor -->
                <li>
                    <h3 class="text-xs font-semibold leading-6 text-gray-500 uppercase tracking-wider">Gestión de Servicios</h3>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('provider.routes.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-route text-gray-400 group-hover:text-indigo-600"></i>
                                Mis Rutas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('provider.drivers.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-user-tie text-gray-400 group-hover:text-indigo-600"></i>
                                Conductores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('provider.driver.vehicles') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-car text-gray-400 group-hover:text-indigo-600"></i>
                                Vehículos
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('provider.assignments.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-calendar-check text-gray-400 group-hover:text-indigo-600"></i>
                                Asignaciones
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('provider.transport-contracts.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-users text-gray-400 group-hover:text-indigo-600"></i>
                                Estudiantes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('provider.payments.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-money-bill-wave text-gray-400 group-hover:text-indigo-600"></i>
                                Ingresos
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if(auth()->user()->account_type === 'school')
                <!-- Menú de Escuela -->
                <li>
                    <h3 class="text-xs font-semibold leading-6 text-gray-500 uppercase tracking-wider">Gestión Escolar</h3>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('school.students.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-child text-gray-400 group-hover:text-indigo-600"></i>
                                Estudiantes
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('school.providers.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-truck text-gray-400 group-hover:text-indigo-600"></i>
                                Proveedores
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('school.routes.index') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                                <i class="fas fa-route text-gray-400 group-hover:text-indigo-600"></i>
                                Rutas Disponibles
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            <!-- Configuración común -->
            <li class="mt-auto">
                <h3 class="text-xs font-semibold leading-6 text-gray-500 uppercase tracking-wider">Configuración</h3>
                <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li>
                        <a href="{{ route('profile.edit') }}" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                            <i class="fas fa-cog text-gray-400 group-hover:text-indigo-600"></i>
                            Configuración
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</div>
