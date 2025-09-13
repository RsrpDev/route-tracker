<?php

/**
 * Archivo de rutas API para Route Tracker
 *
 * Este archivo define todas las rutas de la API REST del sistema.
 * Las rutas están organizadas por versiones (v1) y protegidas con
 * autenticación Sanctum para seguridad.
 *
 * Estructura de la API:
 * - Rutas públicas: Autenticación y registro
 * - Rutas protegidas: Recursos principales y dashboards
 * - Rutas por rol: Acceso específico según tipo de usuario
 * - Webhooks: Callbacks de pasarelas de pago
 *
 * Autenticación:
 * - Sanctum: Para tokens de API
 * - Middleware de roles: Para control de acceso
 *
 * Versionado:
 * - v1: Versión actual de la API
 */

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AccountController;
use App\Http\Controllers\Api\V1\ParentProfileController;
use App\Http\Controllers\Api\V1\SchoolController;
use App\Http\Controllers\Api\V1\ProviderController;
use App\Http\Controllers\Api\V1\DriverController;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\RouteController;
use App\Http\Controllers\Api\V1\RouteAssignmentController;
use App\Http\Controllers\Api\V1\StudentController;
use App\Http\Controllers\Api\V1\EnrollmentController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * RUTAS PÚBLICAS
 * Accesibles sin autenticación
 */

// Ruta de prueba para verificar que la API funciona
Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

/**
 * RUTAS DE AUTENTICACIÓN PÚBLICAS
 * Solo para registro e inicio de sesión
 */
Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
});

/**
 * RUTAS PROTEGIDAS
 * Requieren autenticación con Sanctum
 */
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    /**
     * GESTIÓN DE AUTENTICACIÓN
     * Operaciones de sesión y perfil
     */
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/change-password', [AuthController::class, 'changePassword']);
    Route::post('auth/refresh-token', [AuthController::class, 'refreshToken']);

    /**
     * RECURSOS PRINCIPALES
     * CRUD completo para todas las entidades del sistema
     */
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('parents', ParentProfileController::class);
    Route::apiResource('schools', SchoolController::class);
    Route::apiResource('providers', ProviderController::class);
    Route::apiResource('drivers', DriverController::class);
    Route::apiResource('vehicles', VehicleController::class);
    Route::apiResource('routes', RouteController::class);
    Route::apiResource('route-assignments', RouteAssignmentController::class);
    Route::apiResource('students', StudentController::class);
    // Rutas de contratos de transporte (reemplazan enrollments)
    // Route::apiResource('transport-contracts', TransportContractController::class);
    Route::apiResource('subscriptions', SubscriptionController::class);
    Route::apiResource('payments', PaymentController::class);

    /**
     * ACCIONES PERSONALIZADAS
     * Operaciones específicas que no son CRUD estándar
     */
    Route::post('routes/{route}/assign', [RouteController::class, 'assign']);
    Route::post('students/{student}/enroll', [StudentController::class, 'enroll']);
    Route::post('subscriptions/{subscription}/pay', [PaymentController::class, 'processSubscriptionPayment']);

    /**
     * ACCIONES ADICIONALES
     * Operaciones de aprobación y gestión
     */
    Route::patch('drivers/{driver}/approve', [DriverController::class, 'approve']);

    /**
     * DASHBOARDS POR ROL
     * Acceso específico según el tipo de usuario
     */
    Route::middleware('role:admin')->group(function () {
        Route::get('admin/dashboard', [DashboardController::class, 'admin']);
    });

    Route::middleware('role:provider')->group(function () {
        Route::get('provider/dashboard', [DashboardController::class, 'provider']);
    });

    Route::middleware('role:parent')->group(function () {
        Route::get('parent/dashboard', [DashboardController::class, 'parent']);
    });

    Route::middleware('role:school')->group(function () {
        Route::get('school/dashboard', [DashboardController::class, 'school']);
    });

    /**
     * DASHBOARD GENERAL
     * Accesible para todos los roles autenticados
     */
    Route::get('dashboard', [DashboardController::class, 'general']);
});

/**
 * WEBHOOKS
 * Callbacks de servicios externos (sin autenticación)
 */
Route::post('v1/payments/webhook', [PaymentController::class, 'webhook'])->withoutMiddleware('auth:sanctum');

/**
 * RUTAS DE PRUEBA
 * Para verificar funcionalidad de la API
 */
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
