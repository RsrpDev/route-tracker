<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de Dashboards
    |--------------------------------------------------------------------------
    |
    | Este archivo contiene la configuración para los dashboards del sistema
    | de transporte escolar.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Roles Disponibles
    |--------------------------------------------------------------------------
    |
    | Lista de roles que tienen acceso a dashboards específicos.
    |
    */
    'roles' => [
        'admin' => [
            'name' => 'Administrador',
            'description' => 'Acceso completo al sistema',
            'dashboard_route' => 'admin.dashboard',
            'api_endpoint' => '/api/v1/admin/dashboard',
            'permissions' => ['*'],
        ],
        'provider' => [
            'name' => 'Proveedor',
            'description' => 'Gestión de servicios de transporte',
            'dashboard_route' => 'provider.dashboard',
            'api_endpoint' => '/api/v1/provider/dashboard',
            'permissions' => [
                'routes:manage',
                'drivers:manage',
                'vehicles:manage',
                'route-assignments:manage',
                'payments:read'
            ],
        ],
        'parent' => [
            'name' => 'Padre de Familia',
            'description' => 'Gestión de estudiantes y pagos',
            'dashboard_route' => 'parent.dashboard',
            'api_endpoint' => '/api/v1/parent/dashboard',
            'permissions' => [
                'students:manage',
                'enrollments:manage',
                'payments:create',
                'payments:read',
                'routes:read'
            ],
        ],
        'school' => [
            'name' => 'Escuela',
            'description' => 'Gestión de estudiantes y proveedores',
            'dashboard_route' => 'school.dashboard',
            'api_endpoint' => '/api/v1/school/dashboard',
            'permissions' => [
                'students:read',
                'providers:read',
                'routes:read'
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Actualizaciones en Tiempo Real
    |--------------------------------------------------------------------------
    |
    | Configuración para las actualizaciones automáticas de los dashboards.
    |
    */
    'real_time' => [
        'enabled' => env('DASHBOARD_REAL_TIME', true),
        'update_interval' => env('DASHBOARD_UPDATE_INTERVAL', 60000), // 1 minuto
        'max_retries' => env('DASHBOARD_MAX_RETRIES', 3),
        'timeout' => env('DASHBOARD_TIMEOUT', 10000), // 10 segundos
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Caché
    |--------------------------------------------------------------------------
    |
    | Configuración para el caché de datos de los dashboards.
    |
    */
    'cache' => [
        'enabled' => env('DASHBOARD_CACHE', true),
        'ttl' => env('DASHBOARD_CACHE_TTL', 300), // 5 minutos
        'prefix' => env('DASHBOARD_CACHE_PREFIX', 'dashboard'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Exportación
    |--------------------------------------------------------------------------
    |
    | Configuración para la exportación de datos de los dashboards.
    |
    */
    'export' => [
        'pdf' => [
            'enabled' => true,
            'orientation' => 'landscape',
            'format' => 'A4',
        ],
        'excel' => [
            'enabled' => true,
            'format' => 'xlsx',
        ],
        'csv' => [
            'enabled' => true,
            'delimiter' => ',',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Gráficos
    |--------------------------------------------------------------------------
    |
    | Configuración para los gráficos y visualizaciones de datos.
    |
    */
    'charts' => [
        'default_colors' => [
            '#3B82F6', // Blue
            '#10B981', // Green
            '#F59E0B', // Yellow
            '#EF4444', // Red
            '#8B5CF6', // Purple
            '#06B6D4', // Cyan
            '#F97316', // Orange
            '#EC4899', // Pink
        ],
        'animation' => [
            'enabled' => true,
            'duration' => 1000,
        ],
        'responsive' => true,
        'maintain_aspect_ratio' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Notificaciones
    |--------------------------------------------------------------------------
    |
    | Configuración para las notificaciones del sistema.
    |
    */
    'notifications' => [
        'position' => 'top-right',
        'duration' => 3000,
        'types' => [
            'success' => [
                'icon' => '✓',
                'color' => 'green',
            ],
            'error' => [
                'icon' => '✗',
                'color' => 'red',
            ],
            'warning' => [
                'icon' => '⚠',
                'color' => 'yellow',
            ],
            'info' => [
                'icon' => 'ℹ',
                'color' => 'blue',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Estadísticas
    |--------------------------------------------------------------------------
    |
    | Configuración para las estadísticas mostradas en los dashboards.
    |
    */
    'statistics' => [
        'admin' => [
            'total_accounts' => [
                'label' => 'Total de Cuentas',
                'icon' => 'users',
                'color' => 'blue',
                'format' => 'number',
            ],
            'total_providers' => [
                'label' => 'Proveedores',
                'icon' => 'truck',
                'color' => 'green',
                'format' => 'number',
            ],
            'active_routes' => [
                'label' => 'Rutas Activas',
                'icon' => 'route',
                'color' => 'purple',
                'format' => 'number',
            ],
            'total_revenue' => [
                'label' => 'Ingresos Totales',
                'icon' => 'currency-dollar',
                'color' => 'yellow',
                'format' => 'currency',
            ],
        ],
        'provider' => [
            'active_routes' => [
                'label' => 'Rutas Activas',
                'icon' => 'route',
                'color' => 'blue',
                'format' => 'number',
            ],
            'total_students' => [
                'label' => 'Estudiantes',
                'icon' => 'users',
                'color' => 'green',
                'format' => 'number',
            ],
            'active_vehicles' => [
                'label' => 'Vehículos',
                'icon' => 'truck',
                'color' => 'purple',
                'format' => 'number',
            ],
            'monthly_revenue' => [
                'label' => 'Ingresos del Mes',
                'icon' => 'currency-dollar',
                'color' => 'yellow',
                'format' => 'currency',
            ],
        ],
        'parent' => [
            'total_students' => [
                'label' => 'Hijos',
                'icon' => 'users',
                'color' => 'blue',
                'format' => 'number',
            ],
            'active_enrollments' => [
                'label' => 'Inscripciones Activas',
                'icon' => 'check-circle',
                'color' => 'green',
                'format' => 'number',
            ],
            'pending_payments' => [
                'label' => 'Pagos Pendientes',
                'icon' => 'currency-dollar',
                'color' => 'yellow',
                'format' => 'number',
            ],
            'active_routes' => [
                'label' => 'Rutas Activas',
                'icon' => 'route',
                'color' => 'purple',
                'format' => 'number',
            ],
        ],
        'school' => [
            'total_students' => [
                'label' => 'Estudiantes',
                'icon' => 'users',
                'color' => 'blue',
                'format' => 'number',
            ],
            'active_enrollments' => [
                'label' => 'Inscripciones Activas',
                'icon' => 'check-circle',
                'color' => 'green',
                'format' => 'number',
            ],
            'total_providers' => [
                'label' => 'Proveedores',
                'icon' => 'truck',
                'color' => 'purple',
                'format' => 'number',
            ],
            'active_routes' => [
                'label' => 'Rutas Activas',
                'icon' => 'route',
                'color' => 'yellow',
                'format' => 'number',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Acciones Rápidas
    |--------------------------------------------------------------------------
    |
    | Configuración para las acciones rápidas disponibles en cada dashboard.
    |
    */
    'quick_actions' => [
        'admin' => [
            [
                'label' => 'Nuevo Proveedor',
                'route' => 'admin.providers.create',
                'icon' => 'plus',
                'color' => 'blue',
            ],
            [
                'label' => 'Nueva Escuela',
                'route' => 'admin.schools.create',
                'icon' => 'academic-cap',
                'color' => 'green',
            ],
            [
                'label' => 'Nueva Ruta',
                'route' => 'admin.routes.create',
                'icon' => 'route',
                'color' => 'purple',
            ],
            [
                'label' => 'Ver Pagos',
                'route' => 'admin.payments.index',
                'icon' => 'currency-dollar',
                'color' => 'yellow',
            ],
        ],
        'provider' => [
            [
                'label' => 'Mis Rutas',
                'route' => 'provider.routes',
                'icon' => 'route',
                'color' => 'blue',
            ],
            [
                'label' => 'Conductores',
                'route' => 'provider.drivers',
                'icon' => 'user',
                'color' => 'green',
            ],
            [
                'label' => 'Vehículos',
                'route' => 'provider.vehicles',
                'icon' => 'truck',
                'color' => 'purple',
            ],
            [
                'label' => 'Pagos',
                'route' => 'provider.payments',
                'icon' => 'currency-dollar',
                'color' => 'yellow',
            ],
        ],
        'parent' => [
            [
                'label' => 'Agregar Hijo',
                'route' => 'parent.students.create',
                'icon' => 'user-add',
                'color' => 'blue',
            ],
            [
                'label' => 'Inscripciones',
                'route' => 'parent.enrollments',
                'icon' => 'document-text',
                'color' => 'green',
            ],
            [
                'label' => 'Realizar Pago',
                'route' => 'parent.payments.create',
                'icon' => 'credit-card',
                'color' => 'yellow',
            ],
            [
                'label' => 'Ver Rutas',
                'route' => 'parent.routes',
                'icon' => 'route',
                'color' => 'purple',
            ],
        ],
        'school' => [
            [
                'label' => 'Ver Estudiantes',
                'route' => 'school.students',
                'icon' => 'users',
                'color' => 'blue',
            ],
            [
                'label' => 'Inscripciones',
                'route' => 'school.enrollments',
                'icon' => 'document-text',
                'color' => 'green',
            ],
            [
                'label' => 'Ver Rutas',
                'route' => 'school.routes',
                'icon' => 'route',
                'color' => 'purple',
            ],
            [
                'label' => 'Configuración',
                'route' => 'school.profile',
                'icon' => 'cog',
                'color' => 'yellow',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Monitoreo
    |--------------------------------------------------------------------------
    |
    | Configuración para el monitoreo y logging de los dashboards.
    |
    */
    'monitoring' => [
        'enabled' => env('DASHBOARD_MONITORING', true),
        'log_access' => env('DASHBOARD_LOG_ACCESS', true),
        'log_errors' => env('DASHBOARD_LOG_ERRORS', true),
        'log_performance' => env('DASHBOARD_LOG_PERFORMANCE', true),
        'metrics' => [
            'response_time' => true,
            'memory_usage' => true,
            'database_queries' => true,
            'cache_hits' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Seguridad
    |--------------------------------------------------------------------------
    |
    | Configuración de seguridad para los dashboards.
    |
    */
    'security' => [
        'rate_limiting' => [
            'enabled' => env('DASHBOARD_RATE_LIMITING', true),
            'max_requests' => env('DASHBOARD_MAX_REQUESTS', 60),
            'decay_minutes' => env('DASHBOARD_DECAY_MINUTES', 1),
        ],
        'session_timeout' => env('DASHBOARD_SESSION_TIMEOUT', 120), // 2 horas
        'csrf_protection' => env('DASHBOARD_CSRF_PROTECTION', true),
        'xss_protection' => env('DASHBOARD_XSS_PROTECTION', true),
    ],
];
