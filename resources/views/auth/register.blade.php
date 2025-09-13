{{--
    Formulario de Registro - Route Tracker

    Este archivo contiene la vista del formulario de registro de nuevos usuarios.
    Permite a los usuarios crear una cuenta en el sistema seleccionando su tipo
    de cuenta (padre, proveedor o colegio) y proporcionando la información necesaria.

    Funcionalidades principales:
    - Formulario de registro con validación
    - Selección de tipo de cuenta
    - Creación automática de perfil según tipo
    - Autenticación automática después del registro
    - Redirección a dashboard correspondiente
    - Diseño responsivo y accesible

    Tipos de cuenta disponibles:
    - parent: Padre de familia
    - provider: Proveedor de transporte
    - school: Colegio
--}}

<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrarse - Route Tracker</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="h-full bg-gray-50">
    <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">🚌 Route Tracker</h1>
                <h2 class="text-2xl font-bold text-gray-900">Crear Cuenta</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Únete a nuestra plataforma de gestión de rutas escolares
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" action="{{ route('register') }}" method="POST">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Nombre Completo
                        </label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" autocomplete="name" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Juan Pérez">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Correo Electrónico
                        </label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="tu@email.com">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">
                            Teléfono
                        </label>
                        <div class="mt-1">
                            <input id="phone" name="phone" type="tel" autocomplete="tel"
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="+1 (555) 123-4567">
                        </div>
                    </div>

                    <div>
                        <label for="account_type" class="block text-sm font-medium text-gray-700">
                            Tipo de Cuenta
                        </label>
                        <div class="mt-1">
                            <select id="account_type" name="account_type" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Selecciona un tipo</option>
                                <option value="parent">Padre/Madre</option>
                                <option value="provider">Proveedor de Transporte</option>
                                <option value="school">Escuela</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Contraseña
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="new-password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="••••••••">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            Mínimo 8 caracteres con mayúsculas, minúsculas y números
                        </p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirmar Contraseña
                        </label>
                        <div class="mt-1">
                            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            Acepto los <a href="#" class="text-blue-600 hover:text-blue-500">términos y condiciones</a>
                        </label>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Crear Cuenta
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">¿Ya tienes una cuenta?</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('login') }}"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-blue-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 border-gray-300">
                            Iniciar Sesión
                        </a>
                    </div>
                </div>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">O regístrate con</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div>
                            <a href="#"
                                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fab fa-google text-red-600"></i>
                                <span class="ml-2">Google</span>
                            </a>
                        </div>

                        <div>
                            <a href="#"
                                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fab fa-facebook text-blue-600"></i>
                                <span class="ml-2">Facebook</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-500">
                ← Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>
