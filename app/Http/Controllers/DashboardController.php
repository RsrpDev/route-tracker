<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Provider;
use App\Models\Route;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\StudentTransportContract;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador unificado para todos los dashboards del sistema
 *
 * Este controlador maneja todos los dashboards específicos por rol:
 * - admin: Dashboard de administrador con estadísticas generales del sistema
 * - provider: Dashboards específicos por tipo de proveedor (conductor, empresa, colegio)
 * - driver: Dashboard para conductores independientes y empleados
 * - parent: Dashboard para padres de familia
 * - school: Dashboard para colegios
 *
 * Funcionalidades principales:
 * - Estadísticas y métricas específicas por rol
 * - Gestión de datos relevantes para cada tipo de usuario
 * - Redirección automática según el tipo de cuenta
 */
class DashboardController extends Controller
{
    /**
     * Dashboard de administrador
     *
     * Muestra estadísticas generales del sistema, cuentas pendientes de verificación,
     * proveedores pendientes de aprobación y métricas de rendimiento.
     *
     * @return \Illuminate\View\View Vista del dashboard de administrador
     */
    public function admin()
    {
        // Estadísticas generales del sistema
        $totalAccounts = Account::count();
        $totalProviders = Provider::count();
        $activeRoutes = Route::where('active_flag', true)->count();
        $totalStudents = Student::count();
        $activeSubscriptions = Subscription::where('subscription_status', 'active')->count();
        $pendingProviders = Provider::where('provider_status', 'pending')->count();
        $totalRevenue = Subscription::where('subscription_status', 'active')->sum('price_snapshot');

        // Cuentas por tipo
        $accountsByType = Account::select('account_type', DB::raw('count(*) as total'))
            ->groupBy('account_type')
            ->pluck('total', 'account_type')
            ->toArray();

        // Proveedores por estado
        $providersByStatus = Provider::select('provider_status', DB::raw('count(*) as total'))
            ->groupBy('provider_status')
            ->pluck('total', 'provider_status')
            ->toArray();

        // Rutas por estado
        $routesByStatus = Route::select('active_flag', DB::raw('count(*) as total'))
            ->groupBy('active_flag')
            ->pluck('total', 'active_flag')
            ->toArray();

        // Últimas cuentas creadas
        $recentAccounts = Account::with(['provider', 'parentProfile', 'school'])
            ->latest()
            ->take(5)
            ->get();

        // Proveedores pendientes de aprobación
        $pendingProvidersList = Provider::with('account')
            ->where('provider_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Estadísticas de verificación
        $verificationStats = [
            'pending_count' => Account::where('verification_status', 'pending')
                ->where('account_type', '!=', 'admin')
                ->count(),
            'verified_count' => Account::where('verification_status', 'verified')
                ->where('account_type', '!=', 'admin')
                ->count(),
            'rejected_count' => Account::where('verification_status', 'rejected')
                ->where('account_type', '!=', 'admin')
                ->count(),
        ];

        // Cuentas pendientes de verificación
        $pendingVerificationList = Account::with(['provider', 'school', 'parentProfile'])
            ->where('verification_status', 'pending')
            ->where('account_type', '!=', 'admin')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalAccounts',
            'totalProviders',
            'activeRoutes',
            'totalStudents',
            'activeSubscriptions',
            'pendingProviders',
            'totalRevenue',
            'accountsByType',
            'providersByStatus',
            'routesByStatus',
            'recentAccounts',
            'pendingProvidersList',
            'verificationStats',
            'pendingVerificationList'
        ));
    }

    /**
     * Dashboard de proveedor - redirige según el tipo
     */
    public function providerByType()
    {
        $provider = Auth::user()->provider;

        if (!$provider) {
            abort(403, 'Acceso denegado. No tienes un perfil de proveedor.');
        }

        return match($provider->provider_type) {
            'driver' => redirect()->route('provider.driver.dashboard'),
            'company' => redirect()->route('provider.company.dashboard'),
            'school_provider' => redirect()->route('provider.school.dashboard'),
            default => redirect()->route('home')
        };
    }

    /**
     * Dashboard de conductor independiente
     */
    public function providerDriver()
    {
        $provider = Auth::user()->provider;

        if (!$provider || $provider->provider_type !== 'driver') {
            abort(403, 'Acceso denegado. Solo conductores independientes pueden acceder a este dashboard.');
        }

        // Estadísticas específicas para conductores independientes
        $activeRoutes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->count();

        $totalStudents = StudentTransportContract::where('provider_id', $provider->provider_id)
            ->where('contract_status', 'active')
            ->count();

        $myVehicle = Vehicle::where('provider_id', $provider->provider_id)
            ->where('vehicle_status', 'active')
            ->first();

        $monthlyRevenue = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->whereYear('paid_at', now()->year)
        ->whereMonth('paid_at', now()->month)
        ->sum('amount_total');

        // Datos para gráfico de ingresos
        $monthlyRevenueData = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('paid_at', '>=', now()->subMonths(6))
        ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount_total) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Estadísticas adicionales para el conductor
        $totalRevenue = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })->sum('amount_total');

        $pendingPayments = Subscription::whereHas('transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('subscription_status', 'active')
        ->where('next_billing_date', '<=', now())
        ->count();

        $averageMonthlyRevenue = $monthlyRevenue > 0 ? $monthlyRevenue : 0;

        // Mis rutas con relaciones optimizadas
        $myRoutes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->with([
                'transportContracts' => function($query) {
                    $query->where('contract_status', 'active')
                          ->with(['student', 'subscription']);
                },
                'school'
            ])
            ->get();

        // Próximos pagos con relaciones optimizadas
        $upcomingPayments = Subscription::whereHas('transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('subscription_status', 'active')
        ->where('next_billing_date', '<=', now()->addDays(30))
        ->with([
            'transportContract' => function($query) {
                $query->with(['student', 'pickupRoute', 'dropoffRoute', 'provider']);
            }
        ])
        ->orderBy('next_billing_date', 'asc')
        ->get();

        return view('provider.driver.dashboard', compact(
            'provider',
            'activeRoutes',
            'totalStudents',
            'myVehicle',
            'monthlyRevenue',
            'monthlyRevenueData',
            'myRoutes',
            'upcomingPayments',
            'totalRevenue',
            'pendingPayments',
            'averageMonthlyRevenue'
        ));
    }

    /**
     * Dashboard para conductores (rol driver) - independientes y empleados
     */
    public function independentDriver()
    {
        $account = Auth::user();

        if ($account->account_type !== 'driver') {
            abort(403, 'Acceso denegado. Solo conductores pueden acceder a este dashboard.');
        }

        // Determinar si es conductor independiente o empleado
        $independentDriver = $account->independentDriver;
        $employedDriver = $account->employedDriver;

        if ($independentDriver) {
            // Es conductor independiente
            return $this->renderIndependentDriverDashboard($account, $independentDriver);
        } elseif ($employedDriver) {
            // Es conductor empleado
            return $this->renderEmployedDriverDashboard($account, $employedDriver);
        } else {
            abort(404, 'Perfil de conductor no encontrado.');
        }
    }

    /**
     * Renderizar dashboard para conductor independiente
     */
    private function renderIndependentDriverDashboard($account, $independentDriver)
    {
        $provider = $independentDriver->provider;

        // Estadísticas específicas para conductores independientes
        $activeRoutes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->count();

        $totalStudents = StudentTransportContract::where('provider_id', $provider->provider_id)
            ->where('contract_status', 'active')
            ->count();

        $myVehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->where('vehicle_status', 'active')
            ->get();

        $monthlyRevenue = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->whereYear('paid_at', now()->year)
        ->whereMonth('paid_at', now()->month)
        ->sum('amount_total');

        // Datos para gráfico de ingresos
        $monthlyRevenueData = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('paid_at', '>=', now()->subMonths(6))
        ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount_total) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Estadísticas adicionales para el conductor
        $totalRevenue = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })->sum('amount_total');

        $pendingPayments = Subscription::whereHas('transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('subscription_status', 'active')
        ->where('next_billing_date', '<=', now())
        ->count();

        $averageMonthlyRevenue = $monthlyRevenue > 0 ? $monthlyRevenue : 0;

        // Mis rutas con relaciones optimizadas
        $myRoutes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->with([
                'transportContracts' => function($query) {
                    $query->where('contract_status', 'active')
                          ->with(['student', 'subscription']);
                },
                'school'
            ])
            ->get();

        // Próximos pagos con relaciones optimizadas
        $upcomingPayments = Subscription::whereHas('transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('subscription_status', 'active')
        ->where('next_billing_date', '<=', now()->addDays(30))
        ->with([
            'transportContract' => function($query) {
                $query->with(['student', 'pickupRoute', 'dropoffRoute', 'provider']);
            }
        ])
        ->orderBy('next_billing_date', 'asc')
        ->get();

        return view('driver.dashboard', compact(
            'account',
            'independentDriver',
            'provider',
            'activeRoutes',
            'totalStudents',
            'myVehicles',
            'monthlyRevenue',
            'monthlyRevenueData',
            'myRoutes',
            'upcomingPayments',
            'totalRevenue',
            'pendingPayments',
            'averageMonthlyRevenue'
        ));
    }

    /**
     * Renderizar dashboard para conductor empleado
     */
    private function renderEmployedDriverDashboard($account, $employedDriver)
    {
        $provider = $employedDriver->provider;

        // Estadísticas específicas para conductores empleados
        $assignedRoutes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->count();

        $totalStudents = StudentTransportContract::where('provider_id', $provider->provider_id)
            ->where('contract_status', 'active')
            ->count();

        // Obtener vehículos asignados al conductor
        $assignedVehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->where('vehicle_status', 'active')
            ->get();

        // Rutas asignadas al conductor (solo las que tiene asignadas)
        $myRoutes = Route::whereHas('routeAssignments', function($query) use ($employedDriver) {
                $query->where('driver_id', $employedDriver->driver_id)
                      ->where('assignment_status', 'active');
            })
            ->where('active_flag', true)
            ->with([
                'transportContracts' => function($query) {
                    $query->where('contract_status', 'active')
                          ->with(['student', 'subscription']);
                },
                'school',
                'routeAssignments' => function($query) use ($employedDriver) {
                    $query->where('driver_id', $employedDriver->driver_id);
                }
            ])
            ->get();

        // Información del empleador
        $employerType = $provider->provider_type;
        $employerName = $provider->display_name;

        // Datos específicos según el tipo de empleador
        $dashboardData = [
            'account' => $account,
            'employedDriver' => $employedDriver,
            'provider' => $provider,
            'assignedRoutes' => $assignedRoutes,
            'totalStudents' => $totalStudents,
            'assignedVehicles' => $assignedVehicles,
            'myRoutes' => $myRoutes,
            'employerType' => $employerType,
            'employerName' => $employerName,
        ];

        // Renderizar vista específica según el tipo de empleador
        if ($employerType === 'company') {
            return view('driver.company-dashboard', $dashboardData);
        } elseif ($employerType === 'school_provider') {
            return view('driver.school-dashboard', $dashboardData);
        } else {
            return view('driver.employed-dashboard', $dashboardData);
        }
    }

    /**
     * Dashboard de empresa de transporte
     */
    public function providerCompany()
    {
        $provider = Auth::user()->provider;

        if (!$provider || $provider->provider_type !== 'company') {
            abort(403, 'Acceso denegado. Solo empresas de transporte pueden acceder a este dashboard.');
        }

        // Estadísticas específicas para empresas
        $activeRoutes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->count();

        $totalStudents = StudentTransportContract::where('provider_id', $provider->provider_id)
            ->where('contract_status', 'active')
            ->count();

        $activeVehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->where('vehicle_status', 'active')
            ->count();

        $activeDrivers = Driver::where('provider_id', $provider->provider_id)
            ->where('driver_status', 'active')
            ->count();

        $monthlyRevenue = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->whereYear('paid_at', now()->year)
        ->whereMonth('paid_at', now()->month)
        ->sum('amount_total');

        // Datos para gráfico de ingresos
        $monthlyRevenueData = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('paid_at', '>=', now()->subMonths(6))
        ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount_total) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Rutas activas
        $routes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->with(['transportContracts.student'])
            ->get();

        // Vehículos de la empresa
        $vehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->get();

        // Conductores de la empresa
        $drivers = Driver::where('provider_id', $provider->provider_id)
            ->get();

        // Próximos pagos
        $upcomingPayments = Subscription::whereHas('transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('subscription_status', 'active')
        ->where('next_billing_date', '<=', now()->addDays(30))
        ->with(['transportContract.student', 'transportContract.pickupRoute', 'transportContract.dropoffRoute'])
        ->get();

        return view('provider.company.dashboard', compact(
            'provider',
            'activeRoutes',
            'totalStudents',
            'activeVehicles',
            'activeDrivers',
            'monthlyRevenue',
            'monthlyRevenueData',
            'routes',
            'vehicles',
            'drivers',
            'upcomingPayments'
        ));
    }

    /**
     * Dashboard de colegio como prestador
     */
    public function providerSchool()
    {
        $user = Auth::user();

        // Buscar proveedor asociado al usuario
        $provider = $user->provider;

        // Si no encuentra proveedor directo, buscar por linked_school_id si es escuela
        if (!$provider && $user->account_type === 'school') {
            $school = $user->school;
            if ($school) {
                $provider = Provider::where('linked_school_id', $school->school_id)
                    ->where('provider_type', 'school_provider')
                    ->first();
            }
        }

        if (!$provider || $provider->provider_type !== 'school_provider') {
            abort(403, 'Acceso denegado. Solo colegios prestadores pueden acceder a este dashboard.');
        }

        // Estadísticas específicas para colegios
        $activeRoutes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->count();

        $schoolStudents = Student::where('school_id', $provider->linked_school_id)
            ->count();

        // Estudiantes con contratos en rutas del proveedor de la escuela
        $enrolledInProviderRoutes = StudentTransportContract::whereHas('student', function($query) use ($provider) {
            $query->where('school_id', $provider->linked_school_id);
        })
        ->where('provider_id', $provider->provider_id)
        ->where('contract_status', 'active')
        ->count();

        // Total de estudiantes con contratos de transporte de la escuela
        $enrolledStudents = StudentTransportContract::whereHas('student', function($query) use ($provider) {
            $query->where('school_id', $provider->linked_school_id);
        })
        ->where('contract_status', 'active')
        ->count();

        // Total de contratos de transporte de estudiantes de la escuela
        $totalEnrollments = StudentTransportContract::whereHas('student', function($q) use ($provider) {
            $q->where('school_id', $provider->linked_school_id);
        })->count();

        // Contratos en rutas del proveedor de la escuela
        $enrollmentsInProviderRoutes = StudentTransportContract::whereHas('student', function($q) use ($provider) {
            $q->where('school_id', $provider->linked_school_id);
        })
        ->where('provider_id', $provider->provider_id)
        ->count();

        $activeVehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->where('vehicle_status', 'active')
            ->count();

        $activeDrivers = Driver::where('provider_id', $provider->provider_id)
            ->where('driver_status', 'active')
            ->count();

        $monthlyRevenue = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->whereYear('paid_at', now()->year)
        ->whereMonth('paid_at', now()->month)
        ->sum('amount_total');

        // Datos para gráfico de ingresos
        $monthlyRevenueData = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('paid_at', '>=', now()->subMonths(6))
        ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount_total) as total')
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Rutas escolares
        $routes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->with(['transportContracts.student'])
            ->get();

        // Vehículos escolares
        $vehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->get();

        // Conductores escolares
        $drivers = Driver::where('provider_id', $provider->provider_id)
            ->get();

        // Próximos pagos
        $upcomingPayments = Subscription::whereHas('transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('subscription_status', 'active')
        ->where('next_billing_date', '<=', now()->addDays(30))
        ->with(['transportContract.student', 'transportContract.pickupRoute', 'transportContract.dropoffRoute'])
        ->get();

        return view('provider.school.dashboard', compact(
            'provider',
            'activeRoutes',
            'schoolStudents',
            'enrolledStudents',
            'enrolledInProviderRoutes',
            'totalEnrollments',
            'enrollmentsInProviderRoutes',
            'activeVehicles',
            'activeDrivers',
            'monthlyRevenue',
            'monthlyRevenueData',
            'routes',
            'vehicles',
            'drivers',
            'upcomingPayments'
        ));
    }

    /**
     * Dashboard de proveedor (método original - mantenido para compatibilidad)
     */
    public function provider()
    {
        $provider = Auth::user()->provider;

        if (!$provider) {
            abort(403, 'Acceso denegado. No tienes un perfil de proveedor.');
        }

        // Estadísticas del proveedor
        $activeRoutes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->count();

        $totalStudents = StudentTransportContract::where('provider_id', $provider->provider_id)
            ->where('contract_status', 'active')
            ->count();

        $activeVehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->where('vehicle_status', 'active')
            ->count();

        $activeDrivers = Driver::where('provider_id', $provider->provider_id)
            ->where('driver_status', 'active')
            ->count();

        // Ingresos del mes actual
        $monthlyRevenue = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->whereYear('paid_at', now()->year)
        ->whereMonth('paid_at', now()->month)
        ->sum('amount_total');

        // Ingresos de los últimos 6 meses
        $monthlyRevenueData = Payment::whereHas('subscription.transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->select(
            DB::raw('DATE_FORMAT(paid_at, "%Y-%m") as month'),
            DB::raw('SUM(amount_total) as total')
        )
        ->where('paid_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month')
        ->toArray();

        // Rutas activas con detalles
        $routes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->with(['transportContracts.student'])
            ->get();

        // Vehículos del proveedor
        $vehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->get();

        // Conductores del proveedor
        $drivers = Driver::where('provider_id', $provider->provider_id)
            ->get();

        // Próximos pagos
        $upcomingPayments = Subscription::whereHas('transportContract', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('subscription_status', 'active')
        ->where('next_billing_date', '<=', now()->addDays(30))
        ->with(['transportContract.student', 'transportContract.pickupRoute', 'transportContract.dropoffRoute'])
        ->get();

        return view('provider.dashboard', compact(
            'provider',
            'activeRoutes',
            'totalStudents',
            'activeVehicles',
            'activeDrivers',
            'monthlyRevenue',
            'monthlyRevenueData',
            'routes',
            'vehicles',
            'drivers',
            'upcomingPayments'
        ));
    }

    /**
     * Dashboard de padre
     */
    public function parent()
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            abort(403, 'Acceso denegado. No tienes un perfil de padre.');
        }

        // Estudiantes del padre
        $students = Student::where('parent_id', $parent->parent_id)
            ->with(['transportContract.provider', 'transportContract.subscription'])
            ->get();

        $totalStudents = $students->count();

        // Contratos activos
        $activeEnrollments = StudentTransportContract::whereHas('student', function($query) use ($parent) {
            $query->where('parent_id', $parent->parent_id);
        })
        ->where('contract_status', 'active')
        ->with(['student', 'provider', 'pickupRoute', 'dropoffRoute', 'subscription'])
        ->get();

        $activeEnrollmentsCount = $activeEnrollments->count();

        // Próximas facturas
        $upcomingPayments = Subscription::whereHas('transportContract.student', function($query) use ($parent) {
            $query->where('parent_id', $parent->parent_id);
        })
        ->where('subscription_status', 'active')
        ->where('next_billing_date', '<=', now()->addDays(30))
        ->with(['transportContract.student', 'transportContract.provider'])
        ->get();

        $upcomingPaymentsCount = $upcomingPayments->count();

        // Historial de pagos
        $recentPayments = Payment::whereHas('subscription.transportContract.student', function($query) use ($parent) {
            $query->where('parent_id', $parent->parent_id);
        })
        ->with(['subscription.transportContract.student', 'subscription.transportContract.provider'])
        ->latest('paid_at')
        ->take(5)
        ->get();

        // Rutas de los hijos
        $routes = $activeEnrollments->map(function($contract) {
            return $contract->pickupRoute ?? $contract->dropoffRoute;
        })->filter()->unique('route_id');

        // Proveedores de los hijos
        $providers = $activeEnrollments->map(function($contract) {
            return $contract->provider;
        })->unique('provider_id');

        // Estado de las suscripciones
        $subscriptions = $activeEnrollments->map(function($enrollment) {
            return $enrollment->subscription;
        })->filter();

        $activeSubscriptions = $subscriptions->where('subscription_status', 'active')->count();
        $pendingSubscriptions = $subscriptions->where('subscription_status', 'pending')->count();
        $overdueSubscriptions = $subscriptions->where('subscription_status', 'expired')->count();

        // Notificaciones y alertas
        $notifications = collect();

        // Alertas de pagos vencidos
        $overduePayments = Subscription::whereHas('transportContract.student', function($query) use ($parent) {
            $query->where('parent_id', $parent->parent_id);
        })
        ->where('subscription_status', 'active')
        ->where('next_billing_date', '<', now())
        ->count();

        if ($overduePayments > 0) {
            $notifications->push([
                'type' => 'warning',
                'title' => 'Pagos Vencidos',
                'message' => "Tienes {$overduePayments} pago(s) vencido(s). Realiza el pago para evitar suspensiones.",
                'action' => route('payments.index'),
                'action_text' => 'Ver Pagos'
            ]);
        }

        // Alertas de contratos próximos a vencer
        $contractsExpiringSoon = StudentTransportContract::whereHas('student', function($query) use ($parent) {
            $query->where('parent_id', $parent->parent_id);
        })
        ->where('contract_status', 'active')
        ->where('contract_end_date', '<=', now()->addDays(30))
        ->where('contract_end_date', '>', now())
        ->count();

        if ($contractsExpiringSoon > 0) {
            $notifications->push([
                'type' => 'info',
                'title' => 'Contratos Próximos a Vencer',
                'message' => "Tienes {$contractsExpiringSoon} contrato(s) que vence(n) pronto. Considera renovarlos.",
                'action' => route('parent.contracts'),
                'action_text' => 'Ver Contratos'
            ]);
        }

        // Alertas de estudiantes sin contrato
        $studentsWithoutContract = Student::where('parent_id', $parent->parent_id)
            ->whereDoesntHave('transportContract', function($query) {
                $query->where('contract_status', 'active');
            })
            ->count();

        if ($studentsWithoutContract > 0) {
            $notifications->push([
                'type' => 'info',
                'title' => 'Estudiantes Sin Contrato',
                'message' => "Tienes {$studentsWithoutContract} hijo(s) sin contrato de transporte activo.",
                'action' => route('parent.provider-selection.index'),
                'action_text' => 'Buscar Transporte'
            ]);
        }

        return view('parent.dashboard', compact(
            'parent',
            'students',
            'totalStudents',
            'activeEnrollments',
            'activeEnrollmentsCount',
            'upcomingPayments',
            'upcomingPaymentsCount',
            'recentPayments',
            'routes',
            'providers',
            'activeSubscriptions',
            'pendingSubscriptions',
            'overdueSubscriptions',
            'notifications'
        ));
    }

    /**
     * Dashboard de escuela
     */
    public function school()
    {
        $school = Auth::user()->school;

        if (!$school) {
            abort(403, 'Acceso denegado. No tienes un perfil de escuela.');
        }

        // Estadísticas de la escuela
        $totalStudents = Student::where('school_id', $school->school_id)->count();
        $activeEnrollments = StudentTransportContract::whereHas('student', function($query) use ($school) {
            $query->where('school_id', $school->school_id);
        })->where('contract_status', 'active')->count();

        // Obtener todos los proveedores que ofrecen servicios a esta escuela
        $serviceProviders = Provider::whereHas('routes', function($query) use ($school) {
            $query->where('school_id', $school->school_id);
        })
        ->with(['routes' => function($query) use ($school) {
            $query->where('school_id', $school->school_id);
        }])
        ->get();

        $totalProviders = $serviceProviders->count();
        $activeRoutes = Route::where('school_id', $school->school_id)
            ->where('active_flag', true)
            ->count();

        // Estudiantes de la escuela
        $students = Student::where('school_id', $school->school_id)
            ->with(['transportContract.provider', 'transportContract.subscription'])
            ->get();

        // Contratos activos
        $activeEnrollmentsList = StudentTransportContract::whereHas('student', function($query) use ($school) {
            $query->where('school_id', $school->school_id);
        })
        ->where('contract_status', 'active')
        ->with(['student', 'provider', 'pickupRoute', 'dropoffRoute', 'subscription'])
            ->get();

        // Rutas activas
        $routes = Route::where('school_id', $school->school_id)
        ->where('active_flag', true)
        ->with(['provider'])
        ->get();

        // Estadísticas de proveedores por tipo
        $providersByType = [
            'driver' => $serviceProviders->where('provider_type', 'driver')->count(),
            'company' => $serviceProviders->where('provider_type', 'company')->count(),
            'school_provider' => $serviceProviders->where('provider_type', 'school_provider')->count(),
        ];

        // Estudiantes por grado
        $studentsByGrade = Student::where('school_id', $school->school_id)
            ->select('grade', DB::raw('count(*) as total'))
            ->groupBy('grade')
            ->pluck('total', 'grade')
            ->toArray();

        // Ingresos mensuales (si la escuela es prestadora)
        $monthlyRevenue = 0;
        $linkedProvider = Provider::where('linked_school_id', $school->school_id)
            ->where('provider_type', 'school_provider')
            ->first();

        if ($linkedProvider) {
            $monthlyRevenue = Payment::whereHas('subscription.transportContract', function($query) use ($linkedProvider) {
                $query->where('provider_id', $linkedProvider->provider_id);
            })
            ->whereYear('paid_at', now()->year)
            ->whereMonth('paid_at', now()->month)
            ->sum('amount_total');
        }

        return view('school.dashboard', compact(
            'school',
            'totalStudents',
            'activeEnrollments',
            'totalProviders',
            'activeRoutes',
            'students',
            'activeEnrollmentsList',
            'serviceProviders',
            'providersByType',
            'routes',
            'studentsByGrade',
            'monthlyRevenue',
            'linkedProvider'
        ));
    }


}
