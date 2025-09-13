<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Route;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\ParentProfile;
use App\Models\School;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador unificado para gestión de recursos web
 * Complementa las APIs existentes con interfaces web
 */
class ResourceController extends Controller
{
    /**
     * Listar cuentas (solo admin)
     */
    public function accounts()
    {
        $this->authorize('viewAny', Account::class);

        $accounts = Account::with(['provider', 'parentProfile', 'school'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.accounts.index', compact('accounts'));
    }

    /**
     * Mostrar cuenta específica
     */
    public function showAccount(Account $account)
    {
        $this->authorize('view', $account);

        $account->load(['provider', 'parentProfile', 'school']);

        return view('admin.accounts.show', compact('account'));
    }

    /**
     * Editar cuenta
     */
    public function editAccount(Account $account)
    {
        $this->authorize('update', $account);

        $account->load(['provider', 'parentProfile', 'school']);

        return view('admin.accounts.edit', compact('account'));
    }

    /**
     * Listar rutas
     */
    public function routes()
    {
        $user = Auth::user();
        $query = Route::with(['provider', 'transportContracts.student']);

        // Filtrar por proveedor si no es admin
        if ($user->account_type === 'provider') {
            $query->where('provider_id', $user->provider->provider_id);
        }

        $routes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('routes.index', compact('routes'));
    }

    /**
     * Mostrar ruta específica
     */
    public function showRoute(Route $route)
    {
        $route->load(['provider', 'transportContracts.student', 'assignments.driver', 'assignments.vehicle']);

        return view('routes.show', compact('route'));
    }

    /**
     * Crear nueva ruta
     */
    public function createRoute()
    {
        $this->authorize('create', Route::class);

        $providers = Provider::where('provider_status', 'active')->get();

        return view('routes.create', compact('providers'));
    }

    /**
     * Editar ruta
     */
    public function editRoute(Route $route)
    {
        $this->authorize('update', $route);

        $providers = Provider::where('provider_status', 'active')->get();

        return view('routes.edit', compact('route', 'providers'));
    }

    /**
     * Listar estudiantes
     */
    public function students()
    {
        $user = Auth::user();
        $query = Student::with([
            'parentProfile.account',
            'school',
            'transportContracts.pickupRoute',
            'transportContracts.dropoffRoute',
            'transportContracts.provider'
        ]);

        // Filtrar por padre si no es admin
        if ($user->account_type === 'parent') {
            $query->where('parent_id', $user->parentProfile->parent_id);
        }

        // Aplicar filtros de búsqueda
        if (request('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('given_name', 'like', "%{$search}%")
                  ->orWhere('family_name', 'like', "%{$search}%")
                  ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        if (request('school_id')) {
            $query->where('school_id', request('school_id'));
        }

        if (request('grade')) {
            $query->where('grade', request('grade'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        $students = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.students.index', compact('students'));
    }

    /**
     * Mostrar estudiante específico
     */
    public function showStudent(Student $student)
    {
        $student->load([
            'parentProfile',
            'school',
            'transportContract.provider',
            'transportContract.pickupRoute',
            'transportContract.dropoffRoute',
            'transportContract.subscription'
        ]);

        return view('students.show', compact('student'));
    }

    /**
     * Crear nuevo estudiante
     */
    public function createStudent()
    {
        $this->authorize('create', Student::class);

        $parents = ParentProfile::with('account')->get();
        $schools = School::with('account')->get();

        return view('students.create', compact('parents', 'schools'));
    }

    /**
     * Editar estudiante
     */
    public function editStudent(Student $student)
    {
        $this->authorize('update', $student);

        $parents = ParentProfile::with('account')->get();
        $schools = School::with('account')->get();

        return view('students.edit', compact('student', 'parents', 'schools'));
    }


    /**
     * Listar suscripciones
     */
    public function subscriptions()
    {
        $user = Auth::user();
        $query = Subscription::with(['transportContract.student', 'transportContract.pickupRoute.provider']);

        // Filtrar por rol
        if ($user->account_type === 'parent') {
            $query->whereHas('transportContract.student', function($q) use ($user) {
                $q->where('parent_id', $user->parentProfile->parent_id);
            });
        } elseif ($user->account_type === 'provider') {
            $query->whereHas('transportContract.pickupRoute', function($q) use ($user) {
                $q->where('provider_id', $user->provider->provider_id);
            });
        }

        $subscriptions = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Mostrar suscripción específica
     */
    public function showSubscription(Subscription $subscription)
    {
        $subscription->load(['transportContract.student.parentProfile', 'transportContract.pickupRoute.provider', 'payments']);

        return view('admin.subscriptions.show', compact('subscription'));
    }

    /**
     * Listar pagos
     */
    public function payments()
    {
        $user = Auth::user();
        $query = Payment::with(['subscription.transportContract.student', 'subscription.transportContract.provider']);

        // Filtrar por rol
        if ($user->account_type === 'parent') {
            $query->whereHas('subscription.transportContract.student', function($q) use ($user) {
                $q->where('parent_id', $user->parentProfile->parent_id);
            });
        } elseif ($user->account_type === 'provider') {
            $query->whereHas('subscription.transportContract.provider', function($q) use ($user) {
                $q->where('provider_id', $user->provider->provider_id);
            });
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('payments.index', compact('payments'));
    }

    /**
     * Mostrar pago específico
     */
    public function showPayment(Payment $payment)
    {
        $payment->load(['subscription.transportContract.student.parentProfile', 'subscription.transportContract.provider']);

        return view('payments.show', compact('payment'));
    }

    /**
     * Listar conductores
     */
    public function drivers()
    {
        $user = Auth::user();
        $query = Driver::with(['provider', 'routes']);

        // Filtrar por proveedor si no es admin
        if ($user->account_type === 'provider') {
            $query->where('provider_id', $user->provider->provider_id);
        }

        $drivers = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('drivers.index', compact('drivers'));
    }

    /**
     * Mostrar conductor específico
     */
    public function showDriver(Driver $driver)
    {
        $driver->load(['provider', 'routes', 'vehicle']);

        return view('drivers.show', compact('driver'));
    }

    /**
     * Listar vehículos
     */
    public function vehicles()
    {
        $user = Auth::user();
        $query = Vehicle::with(['provider', 'driver']);

        // Filtrar por proveedor si no es admin
        if ($user->account_type === 'provider') {
            $query->where('provider_id', $user->provider->provider_id);
        }

        $vehicles = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Mostrar vehículo específico
     */
    public function showVehicle(Vehicle $vehicle)
    {
        $vehicle->load(['provider', 'driver', 'routes']);

        return view('vehicles.show', compact('vehicle'));
    }
}
