# 🚌 Route Tracker

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![Status](https://img.shields.io/badge/Status-Active-brightgreen.svg)]()

**Sistema integral de gestión de transporte escolar** que conecta colegios, proveedores de transporte, conductores, padres de familia y estudiantes en una plataforma unificada.

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Tecnologías](#-tecnologías)
- [Requisitos del Sistema](#-requisitos-del-sistema)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Uso](#-uso)
- [API](#-api)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Roles y Permisos](#-roles-y-permisos)
- [Base de Datos](#-base-de-datos)
- [Testing](#-testing)
- [Despliegue](#-despliegue)
- [Contribución](#-contribución)
- [Licencia](#-licencia)

## ✨ Características

### 🎯 Funcionalidades Principales

- **Gestión de Usuarios**: Sistema completo de roles (Admin, Proveedor, Conductor, Padre, Colegio)
- **Gestión de Rutas**: Creación, asignación y seguimiento de rutas de transporte
- **Gestión de Vehículos**: Control de flota vehicular con documentación
- **Gestión de Conductores**: Perfiles completos con licencias y certificaciones
- **Contratos de Transporte**: Sistema de contratos entre padres y proveedores
- **Sistema de Pagos**: Integración con pasarelas de pago (Stripe, PSE)
- **Seguimiento en Tiempo Real**: Logs de actividad y ubicación GPS
- **Dashboard Personalizado**: Interfaces específicas por rol de usuario
- **API REST**: API completa para integración con aplicaciones móviles

### 🔐 Seguridad

- Autenticación con Laravel Sanctum
- Autorización basada en roles
- Validación de datos robusta
- Protección CSRF
- Encriptación de contraseñas
- Verificación de cuentas

### 📱 Interfaz de Usuario

- Diseño responsivo con Tailwind CSS
- Componentes reutilizables
- Experiencia de usuario optimizada
- Accesibilidad web
- Soporte multiidioma (Español)

## 🛠 Tecnologías

### Backend
- **Laravel 10.x** - Framework PHP
- **PHP 8.1+** - Lenguaje de programación
- **MySQL 8.0+** - Base de datos
- **Laravel Sanctum** - Autenticación API
- **Laravel Excel** - Importación/Exportación de datos

### Frontend
- **Blade Templates** - Motor de plantillas
- **Tailwind CSS** - Framework CSS
- **Alpine.js** - JavaScript reactivo
- **Font Awesome** - Iconografía

### Herramientas de Desarrollo
- **Composer** - Gestión de dependencias
- **NPM** - Gestión de paquetes frontend
- **Git** - Control de versiones
- **Docker** - Containerización (opcional)

## 📋 Requisitos del Sistema

### Servidor
- **PHP**: 8.1 o superior
- **MySQL**: 8.0 o superior
- **Composer**: 2.0 o superior
- **Node.js**: 16.0 o superior (para assets)
- **NPM**: 8.0 o superior

### Extensiones PHP Requeridas
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML

## 🚀 Instalación

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/route-tracker.git
cd route-tracker
```

### 2. Instalar Dependencias

```bash
# Dependencias PHP
composer install

# Dependencias Node.js
npm install
```

### 3. Configurar Variables de Entorno

```bash
cp .env.example .env
```

Editar el archivo `.env` con tu configuración:

```env
APP_NAME="Route Tracker"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=route_tracker
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Configuración de Stripe (opcional)
STRIPE_KEY=tu_stripe_public_key
STRIPE_SECRET=tu_stripe_secret_key
```

### 4. Generar Clave de Aplicación

```bash
php artisan key:generate
```

### 5. Ejecutar Migraciones y Seeders

```bash
# Ejecutar migraciones
php artisan migrate

# Poblar base de datos con datos de prueba
php artisan db:seed
```

### 6. Compilar Assets

```bash
npm run build
```

### 7. Configurar Permisos (Linux/Mac)

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## ⚙️ Configuración

### Configuración de Base de Datos

El sistema utiliza las siguientes tablas principales:

- `accounts` - Cuentas de usuario
- `parents` - Perfiles de padres
- `schools` - Colegios
- `providers` - Proveedores de transporte
- `drivers` - Conductores
- `vehicles` - Vehículos
- `routes` - Rutas de transporte
- `students` - Estudiantes
- `student_transport_contracts` - Contratos de transporte
- `subscriptions` - Suscripciones de pago
- `payments` - Pagos

### Configuración de Pagos

Para habilitar pagos con Stripe:

1. Crear cuenta en [Stripe](https://stripe.com)
2. Obtener claves API
3. Configurar webhooks
4. Actualizar variables de entorno

### Configuración de Email

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu_email@gmail.com
MAIL_PASSWORD=tu_contraseña
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=tu_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🎮 Uso

### Acceso al Sistema

1. **Navegar a**: `http://localhost:8000`
2. **Registrarse** o usar las credenciales de prueba
3. **Iniciar sesión** con tu cuenta

### Credenciales de Prueba

Después de ejecutar los seeders, puedes usar estas credenciales:

```
👑 ADMINISTRADOR:
   Email: admin@routetracker.com
   Password: admin123

👨‍👩‍👧‍👦 PADRE DE FAMILIA:
   Email: maria.gonzalez@familia.com
   Password: parent123

🏫 COLEGIO CON SERVICIO:
   Email: colegio.sanjose@educacion.com
   Password: school123

🏢 EMPRESA DE TRANSPORTE:
   Email: empresa.abc@transporte.com
   Password: company123

🚗 CONDUCTOR INDEPENDIENTE:
   Email: carlos.rodriguez@conductor.com
   Password: driver123
```

### Flujo de Trabajo Principal

1. **Registro**: Los usuarios se registran según su rol
2. **Verificación**: Los administradores verifican las cuentas
3. **Configuración**: Los usuarios configuran sus perfiles
4. **Gestión**: Cada rol gestiona sus recursos específicos
5. **Contratos**: Los padres contratan servicios de transporte
6. **Seguimiento**: Monitoreo en tiempo real de las rutas

## 🔌 API

### Autenticación

```bash
# Registro
POST /api/v1/auth/register

# Login
POST /api/v1/auth/login

# Obtener perfil
GET /api/v1/auth/me
```

### Endpoints Principales

```bash
# Cuentas
GET /api/v1/accounts
POST /api/v1/accounts
GET /api/v1/accounts/{id}
PUT /api/v1/accounts/{id}
DELETE /api/v1/accounts/{id}

# Rutas
GET /api/v1/routes
POST /api/v1/routes
GET /api/v1/routes/{id}
PUT /api/v1/routes/{id}
DELETE /api/v1/routes/{id}

# Estudiantes
GET /api/v1/students
POST /api/v1/students
GET /api/v1/students/{id}
PUT /api/v1/students/{id}
DELETE /api/v1/students/{id}
```

### Documentación API

La documentación completa de la API está disponible en:
- **Swagger**: `/api/documentation` (si está habilitado)
- **Postman**: Importar la colección desde `/docs/postman`

## 📁 Estructura del Proyecto

```
route-tracker/
├── app/
│   ├── Http/
│   │   ├── Controllers/          # Controladores
│   │   ├── Middleware/           # Middleware personalizado
│   │   ├── Requests/             # Validaciones de formularios
│   │   └── Resources/            # Transformadores de API
│   ├── Models/                   # Modelos Eloquent
│   ├── Observers/                # Observadores de modelos
│   └── Policies/                 # Políticas de autorización
├── database/
│   ├── migrations/               # Migraciones de BD
│   ├── seeders/                  # Seeders de datos
│   └── factories/                # Factories para testing
├── resources/
│   ├── views/                    # Vistas Blade
│   ├── css/                      # Estilos CSS
│   └── js/                       # JavaScript
├── routes/
│   ├── web.php                   # Rutas web
│   ├── api.php                   # Rutas API
│   └── console.php               # Comandos Artisan
├── storage/
│   ├── app/                      # Archivos de aplicación
│   ├── logs/                     # Logs del sistema
│   └── framework/                # Cache y sesiones
└── tests/                        # Tests automatizados
```

## 👥 Roles y Permisos

### 🔑 Roles del Sistema

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **Admin** | Administrador del sistema | Acceso completo, verificación de cuentas |
| **Provider** | Proveedor de transporte | Gestión de rutas, vehículos, conductores |
| **Driver** | Conductor | Gestión de perfil, logs de ruta |
| **Parent** | Padre de familia | Gestión de hijos, contratos, pagos |
| **School** | Colegio | Gestión de estudiantes, proveedores |

### 🛡️ Sistema de Autorización

- **Middleware de roles**: Control de acceso por rol
- **Políticas**: Autorización granular por recurso
- **Gates**: Permisos específicos del sistema

## 🗄️ Base de Datos

### Diagrama de Relaciones

```
accounts (1:1) parents
accounts (1:1) schools
accounts (1:1) providers
providers (1:n) drivers
providers (1:n) vehicles
providers (1:n) routes
parents (1:n) students
schools (1:n) students
students (1:n) student_transport_contracts
student_transport_contracts (1:1) subscriptions
subscriptions (1:n) payments
```

### Migraciones

```bash
# Crear nueva migración
php artisan make:migration create_nueva_tabla

# Ejecutar migraciones
php artisan migrate

# Revertir migración
php artisan migrate:rollback
```

### Seeders

```bash
# Ejecutar seeders
php artisan db:seed

# Ejecutar seeder específico
php artisan db:seed --class=UnifiedRoleSeeder
```

## 🧪 Testing

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter=AuthTest

# Tests con cobertura
php artisan test --coverage
```

### Tipos de Tests

- **Unit Tests**: Tests de unidades individuales
- **Feature Tests**: Tests de funcionalidades completas
- **Integration Tests**: Tests de integración con APIs

## 🚀 Despliegue

### Producción

1. **Configurar servidor**:
   ```bash
   # Instalar dependencias de producción
   composer install --optimize-autoloader --no-dev
   
   # Compilar assets
   npm run production
   
   # Optimizar aplicación
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Configurar variables de entorno**:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

3. **Configurar servidor web** (Apache/Nginx)

4. **Configurar SSL** para HTTPS

### Docker (Opcional)

```bash
# Construir imagen
docker build -t route-tracker .

# Ejecutar contenedor
docker run -p 8000:8000 route-tracker
```

## 🤝 Contribución

### Cómo Contribuir

1. **Fork** el proyecto
2. **Crear** una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. **Push** a la rama (`git push origin feature/AmazingFeature`)
5. **Abrir** un Pull Request

### Estándares de Código

- Seguir **PSR-12** para PHP
- Usar **convenciones de Laravel**
- Escribir **tests** para nuevas funcionalidades
- Documentar **cambios importantes**

### Reportar Issues

- Usar el **template de issues**
- Proporcionar **pasos para reproducir**
- Incluir **información del sistema**

## 📞 Soporte

- **Email**: soporte@routetracker.com
- **Documentación**: [docs.routetracker.com](https://docs.routetracker.com)
- **Issues**: [GitHub Issues](https://github.com/tu-usuario/route-tracker/issues)

## 🙏 Agradecimientos

- [Laravel](https://laravel.com) - Framework PHP
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS
- [Stripe](https://stripe.com) - Pasarela de pagos
- [Font Awesome](https://fontawesome.com) - Iconografía

---

**Desarrollado con ❤️ para mejorar el transporte escolar**

*Última actualización: Diciembre 2024*
