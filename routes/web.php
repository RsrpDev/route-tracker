<?php

/**
 * Archivo de rutas web para Route Tracker
 *
 * Este archivo define todas las rutas web de la aplicación, organizadas por:
 * - Rutas públicas (accesibles sin autenticación)
 * - Rutas de autenticación
 * - Rutas protegidas por rol y autenticación
 *
 * Estructura de roles:
 * - admin: Administrador del sistema
 * - provider: Proveedor de transporte (conductor independiente, empresa, colegio prestador)
 * - driver: Conductor (independiente o empleado)
 * - parent: Padre de familia
 * - school: Colegio (con o sin servicio de transporte)
 */

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\AdminVerificationController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ParentPaymentController;
use App\Http\Controllers\RouteAssignmentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\DriverProfileController;
use App\Http\Controllers\SchoolDriverController;
use App\Http\Controllers\RouteLogController;
use App\Http\Controllers\ParentProviderSelectionController;
use Illuminate\Support\Facades\Route;

/**
 * RUTAS PÚBLICAS
 * Accesibles sin autenticación
 */
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/home', function () {
    return view('home');
})->name('home.page');

/**
 * RUTAS DE AUTENTICACIÓN
 * Solo accesibles para usuarios no autenticados (middleware: guest)
 */
Route::middleware('guest')->group(function () {
    // Formulario de inicio de sesión
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Formulario de registro
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/**
 * RUTAS PROTEGIDAS
 * Requieren autenticación (middleware: auth)
 */
Route::middleware(['auth'])->group(function () {
    /**
     * DASHBOARD PRINCIPAL
     * Redirige automáticamente según el rol del usuario autenticado
     */
    Route::get('dashboard', function () {
        $user = auth()->user();

        return match($user->account_type) {
            'admin' => redirect()->route('admin.dashboard'),
            'provider' => redirect()->route('provider.dashboard.by.type'),
            'driver' => redirect()->route('driver.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            'school' => redirect()->route('school.dashboard'),
            default => redirect()->route('home')
        };
    })->name('dashboard');

    /**
     * DASHBOARDS POR ROL
     * Cada rol tiene su propio dashboard específico
     */

    // Dashboard de administrador - acceso completo al sistema
    Route::get('admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard')
        ->middleware('role:admin');

    // Dashboard de proveedor - redirige según el tipo de proveedor
    Route::get('provider/dashboard', [DashboardController::class, 'providerByType'])
        ->name('provider.dashboard.by.type')
        ->middleware('role:provider');

    // Dashboard específico para conductores independientes
    Route::get('provider/driver/dashboard', [DashboardController::class, 'providerDriver'])
        ->name('provider.driver.dashboard')
        ->middleware('role:provider');

    // Rutas específicas del conductor independiente
    Route::prefix('provider/driver')->name('provider.driver.')->middleware('role:provider')->group(function () {
        Route::get('vehicles', [ProviderController::class, 'vehicles'])->name('vehicles');
        Route::get('vehicles/create', [ProviderController::class, 'createVehicle'])->name('vehicles.create');
        Route::post('vehicles', [ProviderController::class, 'storeVehicle'])->name('vehicles.store');
        Route::get('vehicles/{vehicle}', [ProviderController::class, 'showVehicle'])->name('vehicles.show');
        Route::get('vehicles/{vehicle}/edit', [ProviderController::class, 'editVehicle'])->name('vehicles.edit');
        Route::put('vehicles/{vehicle}', [ProviderController::class, 'updateVehicle'])->name('vehicles.update');
    });

    Route::get('provider/company/dashboard', [DashboardController::class, 'providerCompany'])
        ->name('provider.company.dashboard')
        ->middleware('role:provider');

    Route::get('provider/school/dashboard', [DashboardController::class, 'providerSchool'])
        ->name('provider.school.dashboard')
        ->middleware(['role:provider,school', 'school.transport.service']);

    Route::get('parent/dashboard', [DashboardController::class, 'parent'])
        ->name('parent.dashboard')
        ->middleware('role:parent');

    Route::get('school/dashboard', [DashboardController::class, 'school'])
        ->name('school.dashboard')
        ->middleware('role:school');

    // Dashboard para conductores independientes
    Route::get('driver/dashboard', [DashboardController::class, 'independentDriver'])
        ->name('driver.dashboard')
        ->middleware('role:driver');

    /**
     * RUTAS DE ADMINISTRADOR
     * Acceso completo al sistema - solo para usuarios con rol 'admin'
     */
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        /**
         * GESTIÓN DE CUENTAS
         * Administración de todas las cuentas del sistema
         */
        Route::get('accounts', [ResourceController::class, 'accounts'])->name('admin.accounts.index');
        Route::get('accounts/{account}', [ResourceController::class, 'showAccount'])->name('admin.accounts.show');
        Route::get('accounts/{account}/edit', [ResourceController::class, 'editAccount'])->name('admin.accounts.edit');

        /**
         * SISTEMA DE VERIFICACIÓN DE CUENTAS
         * Aprobación, rechazo y gestión de cuentas pendientes
         */
        Route::prefix('verification')->group(function () {
            Route::get('/', [AdminVerificationController::class, 'index'])->name('admin.verification.index');
            Route::get('/{account}', [AdminVerificationController::class, 'show'])->name('admin.verification.show');
            Route::post('/{account}/approve', [AdminVerificationController::class, 'approve'])->name('admin.verification.approve');
            Route::post('/{account}/reject', [AdminVerificationController::class, 'reject'])->name('admin.verification.reject');
            Route::post('/{account}/revert', [AdminVerificationController::class, 'revert'])->name('admin.verification.revert');
            Route::get('/stats', [AdminVerificationController::class, 'getVerificationStats'])->name('admin.verification.stats');
        });

        // Gestión de proveedores
        Route::resource('providers', ProviderController::class)->names([
            'index' => 'admin.providers.index',
            'create' => 'admin.providers.create',
            'store' => 'admin.providers.store',
            'show' => 'admin.providers.show',
            'edit' => 'admin.providers.edit',
            'update' => 'admin.providers.update',
            'destroy' => 'admin.providers.destroy'
        ]);
        Route::patch('providers/{provider}/activate', [ProviderController::class, 'activate'])->name('admin.providers.activate');
        Route::patch('providers/{provider}/suspend', [ProviderController::class, 'suspend'])->name('admin.providers.suspend');
        Route::post('providers/{provider}/upload-documents', [ProviderController::class, 'uploadDocuments'])->name('admin.providers.upload-documents');
        Route::post('providers/bulk-update', [ProviderController::class, 'bulkUpdate'])->name('admin.providers.bulk-update');
        Route::get('providers/export', [ProviderController::class, 'export'])->name('admin.providers.export');

        // Gestión de vehículos
        Route::resource('vehicles', VehicleController::class);
        Route::patch('vehicles/{vehicle}/assign-driver', [VehicleController::class, 'assignDriver'])->name('vehicles.assign-driver');
        Route::patch('vehicles/{vehicle}/unassign-driver', [VehicleController::class, 'unassignDriver'])->name('vehicles.unassign-driver');
        Route::patch('vehicles/{vehicle}/maintenance', [VehicleController::class, 'maintenance'])->name('vehicles.maintenance');
        Route::patch('vehicles/{vehicle}/activate', [VehicleController::class, 'activate'])->name('vehicles.activate');
        Route::post('vehicles/{vehicle}/upload-documents', [VehicleController::class, 'uploadDocuments'])->name('vehicles.upload-documents');
        Route::post('vehicles/bulk-update', [VehicleController::class, 'bulkUpdate'])->name('vehicles.bulk-update');
        Route::get('vehicles/export', [VehicleController::class, 'export'])->name('vehicles.export');

        // Gestión de escuelas
        Route::resource('schools', SchoolController::class);
        Route::patch('schools/{school}/activate', [SchoolController::class, 'activate'])->name('schools.activate');
        Route::patch('schools/{school}/suspend', [SchoolController::class, 'suspend'])->name('schools.suspend');
        Route::post('schools/{school}/upload-documents', [SchoolController::class, 'uploadDocuments'])->name('schools.upload-documents');
        Route::post('schools/bulk-update', [SchoolController::class, 'bulkUpdate'])->name('schools.bulk-update');
        Route::get('schools/export', [SchoolController::class, 'export'])->name('schools.export');

        // Gestión de estudiantes
        Route::resource('students', StudentController::class)->names([
            'index' => 'admin.students.index',
            'create' => 'admin.students.create',
            'store' => 'admin.students.store',
            'show' => 'admin.students.show',
            'edit' => 'admin.students.edit',
            'update' => 'admin.students.update',
            'destroy' => 'admin.students.destroy'
        ]);
        Route::patch('students/{student}/activate', [StudentController::class, 'activate'])->name('admin.students.activate');
        Route::patch('students/{student}/suspend', [StudentController::class, 'suspend'])->name('admin.students.suspend');
        Route::post('students/{student}/upload-documents', [StudentController::class, 'uploadDocuments'])->name('admin.students.upload-documents');
        Route::post('students/bulk-update', [StudentController::class, 'bulkUpdate'])->name('admin.students.bulk-update');
        Route::get('students/export', [StudentController::class, 'export'])->name('admin.students.export');

        // Gestión de padres
        Route::resource('parents', ParentController::class);
        Route::patch('parents/{parent}/activate', [ParentController::class, 'activate'])->name('parents.activate');
        Route::patch('parents/{parent}/suspend', [ParentController::class, 'suspend'])->name('parents.suspend');
        Route::post('parents/{parent}/upload-documents', [ParentController::class, 'uploadDocuments'])->name('parents.upload-documents');
        Route::post('parents/bulk-update', [ParentController::class, 'bulkUpdate'])->name('parents.bulk-update');
        Route::get('parents/export', [ParentController::class, 'export'])->name('parents.export');

        // Gestión de conductores
        Route::resource('drivers', DriverController::class);
        Route::patch('drivers/{driver}/activate', [DriverController::class, 'activate'])->name('drivers.activate');
        Route::patch('drivers/{driver}/suspend', [DriverController::class, 'suspend'])->name('drivers.suspend');
        Route::patch('drivers/{driver}/leave', [DriverController::class, 'leave'])->name('drivers.leave');
        Route::post('drivers/{driver}/upload-documents', [DriverController::class, 'uploadDocuments'])->name('drivers.upload-documents');
        Route::post('drivers/bulk-update', [DriverController::class, 'bulkUpdate'])->name('drivers.bulk-update');
        Route::get('drivers/export', [DriverController::class, 'export'])->name('drivers.export');

        // Gestión de rutas
        Route::resource('routes', RouteController::class);
        Route::patch('routes/{route}/activate', [RouteController::class, 'activate'])->name('routes.activate');
        Route::patch('routes/{route}/suspend', [RouteController::class, 'suspend'])->name('routes.suspend');
        Route::post('routes/{route}/upload-documents', [RouteController::class, 'uploadDocuments'])->name('routes.upload-documents');
        Route::post('routes/bulk-update', [RouteController::class, 'bulkUpdate'])->name('routes.bulk-update');
        Route::get('routes/export', [RouteController::class, 'export'])->name('routes.export');

        // Gestión de contratos de transporte
        // Route::resource('transport-contracts', TransportContractController::class);
        // Route::patch('transport-contracts/{transportContract}/status', [TransportContractController::class, 'updateStatus'])->name('transport-contracts.update-status');
        // Route::get('transport-contracts/provider-routes', [TransportContractController::class, 'getProviderRoutes'])->name('transport-contracts.provider-routes');

        // Gestión de suscripciones
        Route::resource('subscriptions', SubscriptionController::class);
        Route::patch('subscriptions/{subscription}/activate', [SubscriptionController::class, 'activate'])->name('subscriptions.activate');
        Route::patch('subscriptions/{subscription}/suspend', [SubscriptionController::class, 'suspend'])->name('subscriptions.suspend');
        Route::patch('subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
        Route::patch('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
        Route::post('subscriptions/{subscription}/upload-documents', [SubscriptionController::class, 'uploadDocuments'])->name('subscriptions.upload-documents');
        Route::post('subscriptions/bulk-update', [SubscriptionController::class, 'bulkUpdate'])->name('subscriptions.bulk-update');
        Route::get('subscriptions/export', [SubscriptionController::class, 'export'])->name('subscriptions.export');

        // Gestión de pagos
        Route::resource('payments', PaymentController::class)->names([
            'index' => 'admin.payments.index',
            'create' => 'admin.payments.create',
            'store' => 'admin.payments.store',
            'show' => 'admin.payments.show',
            'edit' => 'admin.payments.edit',
            'update' => 'admin.payments.update',
            'destroy' => 'admin.payments.destroy'
        ]);
        Route::patch('payments/{payment}/process', [PaymentController::class, 'process'])->name('admin.payments.process');
        Route::patch('payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('admin.payments.cancel');
        Route::patch('payments/{payment}/refund', [PaymentController::class, 'refund'])->name('admin.payments.refund');
        Route::post('payments/{payment}/upload-documents', [PaymentController::class, 'uploadDocuments'])->name('admin.payments.upload-documents');
        Route::post('payments/bulk-update', [PaymentController::class, 'bulkUpdate'])->name('admin.payments.bulk-update');
        Route::get('payments/export', [PaymentController::class, 'export'])->name('admin.payments.export');
        Route::get('payments/export-help', function() {
            return view('admin.payments.export-help');
        })->name('admin.payments.export-help');

        // Gestión de asignaciones de rutas
        Route::resource('route-assignments', RouteAssignmentController::class);
        Route::patch('route-assignments/{routeAssignment}/activate', [RouteAssignmentController::class, 'activate'])->name('route-assignments.activate');
        Route::patch('route-assignments/{routeAssignment}/suspend', [RouteAssignmentController::class, 'suspend'])->name('route-assignments.suspend');
        Route::post('route-assignments/bulk-update', [RouteAssignmentController::class, 'bulkUpdate'])->name('route-assignments.bulk-update');
        Route::get('route-assignments/export', [RouteAssignmentController::class, 'export'])->name('route-assignments.export');

        // Acceso rápido del admin a recursos principales
        Route::get('routes', [ResourceController::class, 'routes'])->name('admin.routes');
        Route::get('students', [ResourceController::class, 'students'])->name('admin.students');
        Route::get('subscriptions', [ResourceController::class, 'subscriptions'])->name('admin.subscriptions');
    });

    /**
     * RUTAS DE PROVEEDORES
     * Acceso para usuarios con rol 'provider' (conductores independientes, empresas, colegios prestadores)
     */
    Route::prefix('provider')->middleware('role:provider')->group(function () {
        // Perfil del proveedor
        Route::get('profile', [ProviderController::class, 'profile'])->name('provider.profile');
        Route::put('profile', [ProviderController::class, 'updateProfile'])->name('provider.update-profile');

        // Gestión de rutas
        Route::get('routes', [ProviderController::class, 'routes'])->name('provider.routes');
        Route::get('routes/{route}', [ProviderController::class, 'showRoute'])->name('provider.routes.show');

        // Gestión de conductores (solo para empresas y colegios)
        Route::get('drivers', [ProviderController::class, 'drivers'])->name('provider.drivers');
        Route::get('drivers/{driver}', [ProviderController::class, 'showDriver'])->name('provider.drivers.show');

        // Contratos de transporte
        Route::get('transport-contracts', [ProviderController::class, 'transportContracts'])->name('provider.transport-contracts.index');
        Route::get('transport-contracts/{contract}', [ProviderController::class, 'showTransportContract'])->name('provider.transport-contracts.show');

        // Suscripciones y pagos
        Route::get('subscriptions', [ProviderController::class, 'subscriptions'])->name('provider.subscriptions');
        Route::get('subscriptions/{subscription}', [ProviderController::class, 'showSubscription'])->name('provider.subscriptions.show');
        Route::get('payments', [ProviderController::class, 'payments'])->name('provider.payments');
        Route::get('payments/{payment}', [ProviderController::class, 'showPayment'])->name('provider.payments.show');

        // Rutas específicas para conductores independientes
        Route::get('driver/profile', [DriverProfileController::class, 'show'])->name('provider.driver.profile');
        Route::get('driver/profile/edit', [DriverProfileController::class, 'edit'])->name('provider.driver.profile.edit');
        Route::put('driver/profile', [DriverProfileController::class, 'update'])->name('provider.driver.profile.update');
        Route::get('driver/license-status', [DriverProfileController::class, 'licenseStatus'])->name('provider.driver.license-status');
        Route::get('driver/statistics', [DriverProfileController::class, 'statistics'])->name('provider.driver.statistics');
    });

    // Rutas para proveedores de escuela (requieren servicio de transporte registrado)
    Route::prefix('provider')->middleware(['role:provider,school', 'school.transport.service'])->group(function () {
        Route::get('school/routes', [ProviderController::class, 'routes'])->name('provider.school.routes');
        Route::get('school/routes/{route}', [ProviderController::class, 'showRoute'])->name('provider.school.routes.show');
        Route::get('school/transport-contracts', [ProviderController::class, 'transportContracts'])->name('provider.school.transport-contracts.index');
        Route::get('school/transport-contracts/{contract}', [ProviderController::class, 'showTransportContract'])->name('provider.school.transport-contracts.show');
        Route::get('school/payments', [ProviderController::class, 'payments'])->name('provider.school.payments');
        Route::get('school/payments/{payment}', [ProviderController::class, 'showPayment'])->name('provider.school.payments.show');

        // Gestión de conductores por parte de la escuela
        Route::get('school/drivers', [SchoolDriverController::class, 'index'])->name('provider.school.drivers.index');
        Route::get('school/drivers/create', [SchoolDriverController::class, 'create'])->name('provider.school.drivers.create');
        Route::post('school/drivers', [SchoolDriverController::class, 'store'])->name('provider.school.drivers.store');
        Route::get('school/drivers/{driver}', [SchoolDriverController::class, 'show'])->name('provider.school.drivers.show');
        Route::get('school/drivers/{driver}/edit', [SchoolDriverController::class, 'edit'])->name('provider.school.drivers.edit');
        Route::put('school/drivers/{driver}', [SchoolDriverController::class, 'update'])->name('provider.school.drivers.update');
        Route::patch('school/drivers/{driver}/status', [SchoolDriverController::class, 'updateStatus'])->name('provider.school.drivers.update-status');
        Route::get('school/drivers-statistics', [SchoolDriverController::class, 'statistics'])->name('provider.school.drivers.statistics');
    });

    /**
     * RUTAS DE PADRES
     * Acceso para usuarios con rol 'parent' - gestión de hijos y contratos de transporte
     */
    Route::prefix('parent')->middleware('role:parent')->group(function () {
        // Perfil del padre
        Route::get('profile', [ParentController::class, 'profile'])->name('parent.profile');
        Route::put('profile', [ParentController::class, 'updateProfile'])->name('parent.update-profile');

        // Gestión de estudiantes (hijos)
        Route::resource('students', StudentController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);

        // Contratos de transporte
        Route::get('contracts', [ParentController::class, 'contracts'])->name('parent.contracts');
        Route::get('contracts/{contract}', [ParentController::class, 'showContract'])->name('parent.contracts.show');
        Route::get('contracts/{contract}/payments', [ParentController::class, 'contractPayments'])->name('parent.contracts.payments');

        // Backward compatibility routes (deprecated)
        Route::get('subscriptions', [ParentController::class, 'subscriptions'])->name('parent.subscriptions');
        Route::get('subscriptions/{subscription}', [ParentController::class, 'showSubscription'])->name('parent.subscriptions.show');
        Route::get('subscriptions/{subscription}/payments', [ParentController::class, 'subscriptionPayments'])->name('parent.subscriptions.payments');
        Route::get('routes', [ParentController::class, 'routes'])->name('parent.routes');
        Route::get('routes/{route}', [ParentController::class, 'showRoute'])->name('parent.routes.show');
        Route::resource('payments', ParentPaymentController::class)->only(['index', 'show', 'create', 'store', 'edit', 'update']);

        // Rutas para selección de proveedores
        Route::get('provider-selection', [ParentProviderSelectionController::class, 'index'])->name('parent.provider-selection.index');
        Route::get('provider-selection/{provider}', [ParentProviderSelectionController::class, 'show'])->name('parent.provider-selection.show');
        Route::get('provider-selection/{provider}/create-contract', [ParentProviderSelectionController::class, 'createContract'])->name('parent.provider-selection.create-contract');
        Route::post('provider-selection/{provider}/create-contract', [ParentProviderSelectionController::class, 'storeContract'])->name('parent.provider-selection.store-contract');
    });

    /**
     * RUTAS DE ESCUELAS
     * Acceso para usuarios con rol 'school' - gestión de estudiantes y proveedores
     */
    Route::prefix('school')->middleware('role:school')->group(function () {
        // Perfil de la escuela
        Route::get('profile', [SchoolController::class, 'profile'])->name('school.profile');
        Route::put('profile', [SchoolController::class, 'updateProfile'])->name('school.update-profile');

        // Gestión de estudiantes
        Route::get('students', [SchoolController::class, 'students'])->name('school.students');
        Route::get('students/{student}', [SchoolController::class, 'showStudent'])->name('school.students.show');

        // Gestión de rutas y proveedores
        Route::get('routes', [SchoolController::class, 'routes'])->name('school.routes');
        Route::get('routes/{route}', [SchoolController::class, 'showRoute'])->name('school.routes.show');
        Route::get('providers', [SchoolController::class, 'providers'])->name('school.providers');

        // Registro como proveedor de transporte (para colegios que quieren ofrecer servicio)
        Route::get('register-as-provider', [SchoolController::class, 'registerAsProvider'])->name('school.register-as-provider');
        Route::post('register-as-provider', [SchoolController::class, 'storeAsProvider'])->name('school.store-as-provider');
    });

    /**
     * RUTAS DE CONDUCTORES
     * Acceso para usuarios con rol 'driver' - conductores independientes y empleados
     */
    Route::prefix('driver')->middleware('role:driver')->group(function () {
        // Perfil del conductor
        Route::get('profile', [DriverProfileController::class, 'show'])->name('driver.profile');
        Route::get('profile/edit', [DriverProfileController::class, 'edit'])->name('driver.profile.edit');
        Route::put('profile', [DriverProfileController::class, 'update'])->name('driver.profile.update');
        Route::get('license-status', [DriverProfileController::class, 'licenseStatus'])->name('driver.license-status');
        Route::get('statistics', [DriverProfileController::class, 'statistics'])->name('driver.statistics');

        // Gestión de vehículos
        Route::get('vehicles', [ProviderController::class, 'vehicles'])->name('driver.vehicles');
        Route::get('vehicles/create', [ProviderController::class, 'createVehicle'])->name('driver.vehicles.create');
        Route::post('vehicles', [ProviderController::class, 'storeVehicle'])->name('driver.vehicles.store');
        Route::get('vehicles/{vehicle}', [ProviderController::class, 'showVehicle'])->name('driver.vehicles.show');
        Route::get('vehicles/{vehicle}/edit', [ProviderController::class, 'editVehicle'])->name('driver.vehicles.edit');
        Route::put('vehicles/{vehicle}', [ProviderController::class, 'updateVehicle'])->name('driver.vehicles.update');

        // Gestión de rutas asignadas
        Route::get('routes', [ProviderController::class, 'routes'])->name('driver.routes');
        Route::get('routes/{route}', [ProviderController::class, 'showRoute'])->name('driver.routes.show');

        // Gestión de contratos de transporte
        Route::get('transport-contracts', [ProviderController::class, 'transportContracts'])->name('driver.transport-contracts.index');
        Route::get('transport-contracts/{contract}', [ProviderController::class, 'showTransportContract'])->name('driver.transport-contracts.show');

            // Gestión de suscripciones y pagos
            Route::get('subscriptions', [ProviderController::class, 'subscriptions'])->name('driver.subscriptions');
            Route::get('subscriptions/{subscription}', [ProviderController::class, 'showSubscription'])->name('driver.subscriptions.show');
            Route::get('payments', [ProviderController::class, 'payments'])->name('driver.payments');
            Route::get('payments/{payment}', [ProviderController::class, 'showPayment'])->name('driver.payments.show');

            // Gestión de logs de rutas
            Route::post('routes/{route}/start', [RouteLogController::class, 'startRoute'])->name('driver.routes.start');
            Route::post('routes/{route}/end', [RouteLogController::class, 'endRoute'])->name('driver.routes.end');
            Route::post('routes/{route}/pickup', [RouteLogController::class, 'pickupStudents'])->name('driver.routes.pickup');
            Route::post('routes/{route}/dropoff', [RouteLogController::class, 'dropoffStudents'])->name('driver.routes.dropoff');
            Route::post('routes/{route}/incident', [RouteLogController::class, 'reportIncident'])->name('driver.routes.incident');
            Route::get('routes/{route}/logs', [RouteLogController::class, 'getRouteLogs'])->name('driver.routes.logs');
            Route::get('logs/today', [RouteLogController::class, 'getTodayLogs'])->name('driver.logs.today');
            Route::get('logs', function() { return view('driver.route-logs'); })->name('driver.logs');
    });

    /**
     * CONFIGURACIONES Y PREFERENCIAS
     * Rutas para configuración de perfil y preferencias del usuario
     */
    Route::get('settings/profile', function () {
        return view('settings.profile');
    })->name('settings.profile');

    Route::get('settings/password', function () {
        return view('settings.password');
    })->name('settings.password');

    Route::get('settings/appearance', function () {
        return view('settings.appearance');
    })->name('settings.appearance');

    /**
     * LOGOUT
     * Cierre de sesión del usuario
     */
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

require __DIR__.'/auth.php';
