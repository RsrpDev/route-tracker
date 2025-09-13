<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Provider;
use App\Models\Route;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Vehicle;
use App\Models\Driver;
use App\Models\School;
use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Dashboard de administrador
     */
    public function admin()
    {
        // Estadísticas generales del sistema
        $stats = [
            'total_accounts' => Account::count(),
            'total_providers' => Provider::count(),
            'active_routes' => Route::where('active_flag', true)->count(),
            'total_students' => Student::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'pending_providers' => Provider::where('provider_status', 'pending')->count(),
            'total_revenue' => Payment::where('payment_status', 'completed')->sum('amount_total'),
            'monthly_revenue' => Payment::where('payment_status', 'completed')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->sum('amount_total'),
        ];

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

        // Ingresos de los últimos 6 meses
        $monthlyRevenue = Payment::where('payment_status', 'completed')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount_total) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Últimas cuentas creadas
        $recentAccounts = Account::with(['provider', 'parentProfile', 'school'])
            ->latest()
            ->take(5)
            ->get();

        // Proveedores pendientes de aprobación
        $pendingProviders = Provider::with('account')
            ->where('provider_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Últimos pagos
        $recentPayments = Payment::with(['subscription.enrollment.student', 'subscription.enrollment.route.provider'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'accounts_by_type' => $accountsByType,
                'providers_by_status' => $providersByStatus,
                'routes_by_status' => $routesByStatus,
                'monthly_revenue' => $monthlyRevenue,
                'recent_accounts' => $recentAccounts,
                'pending_providers' => $pendingProviders,
                'recent_payments' => $recentPayments,
            ]
        ]);
    }

    /**
     * Dashboard de proveedor
     */
    public function provider()
    {
        $provider = Auth::user()->provider;

        if (!$provider) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un perfil de proveedor'
            ], 403);
        }

        // Estadísticas del proveedor
        $stats = [
            'active_routes' => Route::where('provider_id', $provider->provider_id)
                ->where('active_flag', true)
                ->count(),
            'total_students' => Student::whereHas('enrollments.route', function($query) use ($provider) {
                $query->where('provider_id', $provider->provider_id);
            })->count(),
            'active_vehicles' => Vehicle::where('provider_id', $provider->provider_id)
                ->where('status', 'active')
                ->count(),
            'active_drivers' => Driver::where('provider_id', $provider->provider_id)
                ->where('driver_status', 'active')
                ->count(),
            'monthly_revenue' => Payment::whereHas('subscription.enrollment.route', function($query) use ($provider) {
                $query->where('provider_id', $provider->provider_id);
            })
            ->where('payment_status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('provider_amount'),
        ];

        // Ingresos de los últimos 6 meses
        $monthlyRevenue = Payment::whereHas('subscription.enrollment.route', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('payment_status', 'completed')
        ->select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
            DB::raw('SUM(provider_amount) as total')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Rutas activas con detalles
        $routes = Route::where('provider_id', $provider->provider_id)
            ->where('active_flag', true)
            ->with(['enrollments.student', 'routeAssignments.driver', 'routeAssignments.vehicle'])
            ->get();

        // Vehículos del proveedor
        $vehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->with('driver')
            ->get();

        // Conductores del proveedor
        $drivers = Driver::where('provider_id', $provider->provider_id)
            ->with('routeAssignments.route')
            ->get();

        // Próximos pagos
        $upcomingPayments = Subscription::whereHas('enrollment.route', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('status', 'active')
        ->where('next_payment_date', '<=', now()->addDays(30))
        ->with(['enrollment.student', 'enrollment.route'])
        ->get();

        // Últimos pagos recibidos
        $recentPayments = Payment::whereHas('subscription.enrollment.route', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })
        ->where('payment_status', 'completed')
        ->with(['subscription.enrollment.student', 'subscription.enrollment.route'])
        ->latest()
        ->take(10)
        ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'monthly_revenue' => $monthlyRevenue,
                'routes' => $routes,
                'vehicles' => $vehicles,
                'drivers' => $drivers,
                'upcoming_payments' => $upcomingPayments,
                'recent_payments' => $recentPayments,
            ]
        ]);
    }

    /**
     * Dashboard de padre
     */
    public function parent()
    {
        $parent = Auth::user()->parentProfile;

        if (!$parent) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un perfil de padre'
            ], 403);
        }

        // Estudiantes del padre
        $students = Student::where('parent_id', $parent->parent_id)
            ->with(['enrollments.route.provider', 'enrollments.subscription'])
            ->get();

        $stats = [
            'total_students' => $students->count(),
            'active_enrollments' => Enrollment::whereHas('student', function($query) use ($parent) {
                $query->where('parent_id', $parent->parent_id);
            })->where('status', 'active')->count(),
            'active_subscriptions' => Subscription::whereHas('enrollment.student', function($query) use ($parent) {
                $query->where('parent_id', $parent->parent_id);
            })->where('status', 'active')->count(),
            'pending_payments' => Subscription::whereHas('enrollment.student', function($query) use ($parent) {
                $query->where('parent_id', $parent->parent_id);
            })->where('status', 'active')
            ->where('next_payment_date', '<=', now()->addDays(30))
            ->count(),
        ];

        // Inscripciones activas
        $activeEnrollments = Enrollment::whereHas('student', function($query) use ($parent) {
            $query->where('parent_id', $parent->parent_id);
        })
        ->where('status', 'active')
        ->with(['student', 'route.provider', 'subscription'])
        ->get();

        // Próximas facturas
        $upcomingPayments = Subscription::whereHas('enrollment.student', function($query) use ($parent) {
            $query->where('parent_id', $parent->parent_id);
        })
        ->where('status', 'active')
        ->where('next_payment_date', '<=', now()->addDays(30))
        ->with(['enrollment.student', 'enrollment.route.provider'])
        ->get();

        // Historial de pagos
        $recentPayments = Payment::whereHas('subscription.enrollment.student', function($query) use ($parent) {
            $query->where('parent_id', $parent->parent_id);
        })
        ->with(['subscription.enrollment.student', 'subscription.enrollment.route.provider'])
        ->latest('created_at')
        ->take(10)
        ->get();

        // Rutas de los hijos
        $routes = $activeEnrollments->map(function($enrollment) {
            return $enrollment->route;
        })->unique('route_id');

        // Estado de las suscripciones
        $subscriptions = $activeEnrollments->map(function($enrollment) {
            return $enrollment->subscription;
        })->filter();

        $subscriptionStats = [
            'active' => $subscriptions->where('status', 'active')->count(),
            'pending' => $subscriptions->where('status', 'pending')->count(),
            'overdue' => $subscriptions->where('status', 'overdue')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'students' => $students,
                'active_enrollments' => $activeEnrollments,
                'upcoming_payments' => $upcomingPayments,
                'recent_payments' => $recentPayments,
                'routes' => $routes,
                'subscription_stats' => $subscriptionStats,
            ]
        ]);
    }

    /**
     * Dashboard de escuela
     */
    public function school()
    {
        $school = Auth::user()->school;

        if (!$school) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un perfil de escuela'
            ], 403);
        }

        // Estadísticas de la escuela
        $stats = [
            'total_students' => Student::where('school_id', $school->school_id)->count(),
            'active_enrollments' => Enrollment::whereHas('student', function($query) use ($school) {
                $query->where('school_id', $school->school_id);
            })->where('status', 'active')->count(),
            'total_providers' => Provider::where('linked_school_id', $school->school_id)->count(),
            'active_routes' => Route::whereHas('enrollments.student', function($query) use ($school) {
                $query->where('school_id', $school->school_id);
            })->where('active_flag', true)->count(),
        ];

        // Estudiantes de la escuela
        $students = Student::where('school_id', $school->school_id)
            ->with(['enrollments.route.provider', 'enrollments.subscription'])
            ->get();

        // Inscripciones activas
        $activeEnrollments = Enrollment::whereHas('student', function($query) use ($school) {
            $query->where('school_id', $school->school_id);
        })
        ->where('status', 'active')
        ->with(['student', 'route.provider', 'subscription'])
        ->get();

        // Proveedores vinculados
        $providers = Provider::where('linked_school_id', $school->school_id)
            ->with(['account', 'routes'])
            ->get();

        // Rutas activas
        $routes = Route::whereHas('enrollments.student', function($query) use ($school) {
            $query->where('school_id', $school->school_id);
        })
        ->where('active_flag', true)
        ->with(['provider', 'enrollments.student'])
        ->get();

        // Estudiantes por grado
        $studentsByGrade = Student::where('school_id', $school->school_id)
            ->select('grade', DB::raw('count(*) as total'))
            ->groupBy('grade')
            ->pluck('total', 'grade')
            ->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'students' => $students,
                'active_enrollments' => $activeEnrollments,
                'providers' => $providers,
                'routes' => $routes,
                'students_by_grade' => $studentsByGrade,
            ]
        ]);
    }

    /**
     * Dashboard general (para roles no específicos)
     */
    public function general()
    {
        $user = Auth::user();

        $stats = [
            'account_type' => $user->account_type,
            'account_status' => $user->account_status,
            'created_at' => $user->created_at,
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'stats' => $stats,
            ]
        ]);
    }
}
