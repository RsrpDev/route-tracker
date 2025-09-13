<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Route Tracker - Sistema de Gestión de Rutas Escolares</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-gray-900">🚌 Route Tracker</h1>
                </div>
                <nav class="flex space-x-4">
                    <a href="/" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Inicio</a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition-colors">Registrarse</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Contenido principal -->
    <main class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header principal -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">
                    🚌 Route Tracker
                </h1>
                <p class="text-xl text-gray-600 mb-6">
                    Sistema de Gestión Integral de Rutas Escolares
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('login') }}"
                       class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors font-medium">
                        Crear Cuenta
                    </a>
                </div>
            </div>

            <!-- Misión y Visión -->
            <div class="grid md:grid-cols-2 gap-8 mb-12">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center">
                        <div class="text-3xl mb-4">🎯</div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Misión</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Proporcionar una plataforma integral y confiable para la gestión eficiente de rutas escolares,
                            conectando padres, proveedores de transporte y escuelas para garantizar la seguridad y puntualidad
                            en el traslado de estudiantes.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center">
                        <div class="text-3xl mb-4">🌟</div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">Visión</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Ser la plataforma líder en Latinoamérica para la gestión de transporte escolar,
                            innovando constantemente en tecnología y servicios para crear un ecosistema
                            de movilidad educativa seguro, eficiente y sostenible.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Características principales -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">✨ Características Principales</h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl mb-3">🔒</div>
                        <h3 class="font-semibold text-gray-800 mb-2">Seguridad</h3>
                        <p class="text-gray-600 text-sm">Seguimiento en tiempo real y notificaciones de seguridad</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl mb-3">⚡</div>
                        <h3 class="font-semibold text-gray-800 mb-2">Eficiencia</h3>
                        <p class="text-gray-600 text-sm">Optimización de rutas y gestión inteligente de horarios</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl mb-3">📱</div>
                        <h3 class="font-semibold text-gray-800 mb-2">Accesibilidad</h3>
                        <p class="text-gray-600 text-sm">Interfaz intuitiva disponible en cualquier dispositivo</p>
                    </div>
                </div>
            </div>

            <!-- Status Cards -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center">
                        <div class="text-3xl mb-2">✅</div>
                        <h3 class="text-lg font-semibold text-gray-800">Sistema Operativo</h3>
                        <p class="text-gray-600">API funcionando correctamente</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center">
                        <div class="text-3xl mb-2">🔐</div>
                        <h3 class="text-lg font-semibold text-gray-800">Autenticación</h3>
                        <p class="text-gray-600">Sistema de tokens configurado</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center">
                        <div class="text-3xl mb-2">📊</div>
                        <h3 class="text-lg font-semibold text-gray-800">Base de Datos</h3>
                        <p class="text-gray-600">Migraciones ejecutadas</p>
                    </div>
                </div>
            </div>

            <!-- API Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">📡 Información de la API</h2>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <span class="font-semibold text-gray-700 w-32">Versión:</span>
                        <span class="text-gray-600">1.0.0</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-semibold text-gray-700 w-32">Estado:</span>
                        <span class="text-green-600 font-semibold">Operativo</span>
                    </div>
                    <div class="flex items-center">
                        <span class="font-semibold text-gray-700 w-32">Base URL:</span>
                        <span class="text-gray-600">/api/v1</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">🚀 Acciones Rápidas</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Documentación API</h3>
                        <p class="text-gray-600 text-sm mb-3">Consulta los endpoints disponibles y su uso</p>
                        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-colors">
                            Ver Documentación
                        </button>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-gray-800 mb-2">Panel de Control</h3>
                        <p class="text-gray-600 text-sm mb-3">Accede al dashboard principal de la aplicación</p>
                        <a href="{{ route('login') }}" class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition-colors">
                            Ir al Dashboard
                        </a>
                    </div>
                </div>
            </div>
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
</body>
</html>

