<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\Account;
use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Enrollment;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\StudentTransportContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $providers = Provider::with('account')->paginate(15);
        return view('admin.providers.index', compact('providers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Account::where('account_type', 'provider')->get();
        return view('admin.providers.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'provider_type' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'linked_school_id' => 'nullable|exists:schools,school_id',
            'default_commission_rate' => 'nullable|numeric|min:0|max:100',
            'provider_status' => 'required|in:active,suspended,inactive',
            // Campos específicos para conductores independientes
            'driver_license_number' => 'nullable|string|max:50',
            'driver_license_category' => 'nullable|string|max:10',
            'driver_license_expiration' => 'nullable|date',
            'driver_years_experience' => 'nullable|integer|min:0',
            'driver_status' => 'nullable|in:pending,approved,rejected'
        ]);

        Provider::create($validated);

        return redirect()->route('admin.providers.index')->with('success', 'Proveedor creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Provider $provider)
    {
        return view('admin.providers.show', compact('provider'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Provider $provider)
    {
        $accounts = Account::where('account_type', 'provider')->get();
        return view('admin.providers.edit', compact('provider', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'provider_type' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'linked_school_id' => 'nullable|exists:schools,school_id',
            'default_commission_rate' => 'nullable|numeric|min:0|max:100',
            'provider_status' => 'required|in:active,suspended,inactive',
            // Campos específicos para conductores independientes
            'driver_license_number' => 'nullable|string|max:50',
            'driver_license_category' => 'nullable|string|max:10',
            'driver_license_expiration' => 'nullable|date',
            'driver_years_experience' => 'nullable|integer|min:0',
            'driver_status' => 'nullable|in:pending,approved,rejected'
        ]);

        $provider->update($validated);

        return redirect()->route('admin.providers.index')->with('success', 'Proveedor actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Provider $provider)
    {
        $provider->delete();

        return redirect()->route('admin.providers.index')->with('success', 'Proveedor eliminado exitosamente');
    }

    /**
     * Show provider profile
     */
    public function profile()
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();
        return view('provider.profile', compact('provider'));
    }

    /**
     * Update provider profile
     */
    public function updateProfile(Request $request)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'provider_type' => 'required|string|max:255',
            'display_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'linked_school_id' => 'nullable|exists:schools,school_id',
            'default_commission_rate' => 'nullable|numeric|min:0|max:100'
        ]);

        $provider->update($validated);

        return redirect()->route('provider.profile')->with('success', 'Perfil actualizado exitosamente');
    }

    /**
     * List provider routes
     */
    public function routes()
    {
        $account = auth()->user();

        // Verificar que sea conductor o provider
        if (!in_array($account->account_type, ['driver', 'provider', 'school'])) {
            abort(403, 'No tienes permisos para ver rutas.');
        }

        // Si es conductor, mostrar solo rutas asignadas
        if ($account->account_type === 'driver') {
            $driver = $account->employedDriver ?? $account->independentDriver;
            if (!$driver) {
                abort(404, 'Perfil de conductor no encontrado.');
            }

            // Obtener solo las rutas asignadas al conductor
            $routes = Route::whereHas('routeAssignments', function($query) use ($driver) {
                    $query->where('driver_id', $driver->driver_id ?? $driver->independent_driver_id)
                          ->where('assignment_status', 'active');
                })
                ->with([
                    'school',
                    'transportContracts' => function($query) {
                        $query->where('contract_status', 'active')
                              ->with(['student']);
                    }
                ])
                ->orderBy('active_flag', 'desc')
                ->orderBy('route_name', 'asc')
                ->paginate(15);

            return view('driver.routes.index', compact('routes'));
        }

        // Si es provider o school, mostrar todas las rutas del provider
        $provider = $account->provider;

        // Si no encuentra provider directo, buscar por linked_school_id si es escuela
        if (!$provider && $account->account_type === 'school') {
            $school = $account->school;
            if ($school) {
                $provider = Provider::where('linked_school_id', $school->school_id)
                    ->where('provider_type', 'school_provider')
                    ->first();
            }
        }

        if (!$provider) {
            abort(403, 'No tienes un servicio de transporte registrado.');
        }

        // Obtener todas las rutas del provider
        $routes = Route::where('provider_id', $provider->provider_id)
            ->with([
                'school',
                'transportContracts' => function($query) {
                    $query->where('contract_status', 'active')
                          ->with(['student']);
                },
                'routeAssignments.driver'
            ])
            ->orderBy('active_flag', 'desc')
            ->orderBy('route_name', 'asc')
            ->paginate(15);

        return view('provider.routes.index', compact('routes', 'provider'));
    }

    /**
     * Show specific route
     */
    public function showRoute(Route $route)
    {
        $account = auth()->user();

        // Verificar que sea conductor o provider
        if (!in_array($account->account_type, ['driver', 'provider', 'school'])) {
            abort(403, 'No tienes permisos para ver rutas.');
        }

        // Si es conductor, verificar que tenga acceso a esta ruta
        if ($account->account_type === 'driver') {
            $driver = $account->employedDriver ?? $account->independentDriver;
            if (!$driver) {
                abort(404, 'Perfil de conductor no encontrado.');
            }

            // Verificar que la ruta esté asignada al conductor
            $routeAssignment = $route->routeAssignments()
                ->where('driver_id', $driver->driver_id ?? $driver->independent_driver_id)
                ->where('assignment_status', 'active')
                ->first();

            if (!$routeAssignment) {
                abort(403, 'No tienes asignada esta ruta.');
            }

            // Cargar relaciones necesarias
            $route->load([
                'school',
                'transportContracts' => function($query) {
                    $query->with(['student']);
                }
            ]);

            return view('driver.routes.show', compact('route'));
        }

        // Si es provider o school, verificar que la ruta pertenezca al provider
        $provider = $account->provider;

        // Si no encuentra provider directo, buscar por linked_school_id si es escuela
        if (!$provider && $account->account_type === 'school') {
            $school = $account->school;
            if ($school) {
                $provider = Provider::where('linked_school_id', $school->school_id)
                    ->where('provider_type', 'school_provider')
                    ->first();
            }
        }

        if (!$provider) {
            abort(403, 'No tienes un servicio de transporte registrado.');
        }

        // Verificar que la ruta pertenezca al provider
        if ($route->provider_id !== $provider->provider_id) {
            abort(403, 'No tienes permisos para ver esta ruta.');
        }

        // Cargar relaciones necesarias
        $route->load([
            'school',
            'transportContracts' => function($query) {
                $query->with(['student']);
            },
            'routeAssignments.driver'
        ]);

        return view('provider.routes.show', compact('route', 'provider'));
    }

    /**
     * List provider drivers
     */
    public function drivers()
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();
        $drivers = Driver::where('provider_id', $provider->provider_id)->paginate(15);
        return view('provider.drivers.index', compact('drivers'));
    }

    /**
     * Show specific driver
     */
    public function showDriver(Driver $driver)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        if ($driver->provider_id !== $provider->provider_id) {
            abort(403);
        }

        return view('provider.drivers.show', compact('driver'));
    }

    /**
     * List provider vehicles
     */
    public function vehicles()
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        $vehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->with(['provider', 'routeAssignments.route'])
            ->orderBy('vehicle_status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('provider.vehicles.index', compact('vehicles', 'provider'));
    }

    /**
     * Show specific vehicle
     */
    public function showVehicle(Vehicle $vehicle)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        if ($vehicle->provider_id !== $provider->provider_id) {
            abort(403, 'No tienes permisos para ver este vehículo.');
        }

        // Cargar relaciones necesarias
        $vehicle->load([
            'provider',
            'routeAssignments.route' => function($query) {
                $query->with(['school', 'provider']);
            }
        ]);

        return view('provider.vehicles.show', compact('vehicle', 'provider'));
    }

    /**
     * Show form to create a new vehicle
     */
    public function createVehicle()
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        return view('provider.vehicles.create', compact('provider'));
    }

    /**
     * Store a newly created vehicle
     */
    public function storeVehicle(Request $request)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'plate' => 'required|string|max:20|unique:vehicles',
            'brand' => 'required|string|max:255',
            'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'vehicle_class' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:50',
            'serial_number' => 'nullable|string|max:255',
            'engine_number' => 'nullable|string|max:255',
            'chassis_number' => 'nullable|string|max:255',
            'cylinder_capacity' => 'nullable|integer|min:0',
            'soat_number' => 'nullable|string|max:255',
            'soat_expiration' => 'nullable|date|after:today',
            'insurance_company' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:255',
            'insurance_expiration' => 'nullable|date|after:today',
            'technical_inspection_expiration' => 'nullable|date|after:today',
            'revision_expiration' => 'nullable|date|after:today',
            'odometer_reading' => 'nullable|integer|min:0',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date|after:today',
            'vehicle_status' => 'required|in:active,inactive,maintenance,retired'
        ]);

        $validated['provider_id'] = $provider->provider_id;

        Vehicle::create($validated);

        return redirect()->route('driver.vehicles')->with('success', 'Vehículo creado exitosamente.');
    }

    /**
     * Show form to edit a vehicle
     */
    public function editVehicle(Vehicle $vehicle)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        if ($vehicle->provider_id !== $provider->provider_id) {
            abort(403, 'No tienes permisos para editar este vehículo.');
        }

        return view('provider.vehicles.edit', compact('vehicle', 'provider'));
    }

    /**
     * Update a vehicle
     */
    public function updateVehicle(Request $request, Vehicle $vehicle)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        if ($vehicle->provider_id !== $provider->provider_id) {
            abort(403, 'No tienes permisos para editar este vehículo.');
        }

        $validated = $request->validate([
            'plate' => 'required|string|max:20|unique:vehicles,plate,' . $vehicle->vehicle_id . ',vehicle_id',
            'brand' => 'required|string|max:255',
            'model_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:255',
            'fuel_type' => 'required|string|max:255',
            'vehicle_class' => 'required|string|max:255',
            'service_type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:50',
            'serial_number' => 'nullable|string|max:255',
            'engine_number' => 'nullable|string|max:255',
            'chassis_number' => 'nullable|string|max:255',
            'cylinder_capacity' => 'nullable|integer|min:0',
            'soat_number' => 'nullable|string|max:255',
            'soat_expiration' => 'nullable|date',
            'insurance_company' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:255',
            'insurance_expiration' => 'nullable|date',
            'technical_inspection_expiration' => 'nullable|date',
            'revision_expiration' => 'nullable|date',
            'odometer_reading' => 'nullable|integer|min:0',
            'last_maintenance_date' => 'nullable|date',
            'next_maintenance_date' => 'nullable|date',
            'vehicle_status' => 'required|in:active,inactive,maintenance,retired'
        ]);

        $vehicle->update($validated);

        return redirect()->route('driver.vehicles.show', $vehicle)->with('success', 'Vehículo actualizado exitosamente.');
    }

    /**
     * List provider enrollments
     */
    public function enrollments()
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();
        $enrollments = Enrollment::whereHas('route', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })->with(['student', 'route', 'school'])->paginate(15);

        return view('provider.enrollments.index', compact('enrollments'));
    }

    /**
     * Show specific enrollment
     */
    public function showEnrollment(Enrollment $enrollment)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        if ($enrollment->route->provider_id !== $provider->provider_id) {
            abort(403);
        }

        return view('provider.enrollments.show', compact('enrollment'));
    }

    /**
     * List provider subscriptions
     */
    public function subscriptions()
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();
        $subscriptions = Subscription::whereHas('route', function($query) use ($provider) {
            $query->where('provider_id', $provider->provider_id);
        })->with(['student', 'route'])->paginate(15);

        return view('provider.subscriptions.index', compact('subscriptions'));
    }

    /**
     * Show specific subscription
     */
    public function showSubscription(Subscription $subscription)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        if ($subscription->route->provider_id !== $provider->provider_id) {
            abort(403);
        }

        return view('provider.subscriptions.show', compact('subscription'));
    }

    /**
     * List provider payments
     */
    public function payments()
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        $payments = Payment::whereHas('subscription.pickupRoute', function($query) use ($provider) {
            $query->where('routes.provider_id', $provider->provider_id);
        })->with([
            'subscription.pickupRoute',
            'subscription.transportContract.student'
        ])->paginate(15);

        return view('driver.payments.index', compact('payments'));
    }

    /**
     * Show specific payment
     */
    public function showPayment(Payment $payment)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        // Cargar relaciones necesarias
        $payment->load([
            'subscription.pickupRoute',
            'subscription.transportContract.student'
        ]);

        if ($payment->subscription->pickupRoute->provider_id !== $provider->provider_id) {
            abort(403, 'No tienes permisos para ver este pago.');
        }

        return view('driver.payments.show', compact('payment'));
    }

    /**
     * Show transport contracts for provider
     */
    public function transportContracts()
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        $contracts = StudentTransportContract::where('provider_id', $provider->provider_id)
            ->with(['student.parentProfile.account', 'pickupRoute', 'dropoffRoute', 'subscription'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('provider.transport-contracts.index', compact('contracts'));
    }

    /**
     * Show specific transport contract
     */
    public function showTransportContract(StudentTransportContract $contract)
    {
        $provider = Provider::where('account_id', auth()->id())->firstOrFail();

        // Verificar que el contrato pertenece al proveedor
        if ($contract->provider_id !== $provider->provider_id) {
            abort(403);
        }

        $contract->load(['student.parentProfile.account', 'pickupRoute', 'dropoffRoute', 'subscription.payments']);

        return view('provider.transport-contracts.show', compact('contract'));
    }

    /**
     * Activate provider
     */
    public function activate(Provider $provider)
    {
        $provider->update(['provider_status' => 'active']);
        return redirect()->route('admin.providers.show', $provider)->with('success', 'Proveedor activado exitosamente');
    }

    /**
     * Suspend provider
     */
    public function suspend(Provider $provider)
    {
        $provider->update(['provider_status' => 'suspended']);
        return redirect()->route('admin.providers.show', $provider)->with('success', 'Proveedor suspendido exitosamente');
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request, Provider $provider)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('provider-documents', 'public');

        // Aquí podrías guardar la información del documento en la base de datos

        return redirect()->route('admin.providers.show', $provider)->with('success', 'Documento subido exitosamente');
    }

    /**
     * Bulk update providers
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'provider_ids' => 'required|array',
            'provider_ids.*' => 'exists:providers,provider_id',
            'provider_status' => 'required|in:active,suspended,inactive'
        ]);

        Provider::whereIn('provider_id', $request->provider_ids)
                ->update(['provider_status' => $request->provider_status]);

        return redirect()->route('admin.providers.index')->with('success', 'Proveedores actualizados exitosamente');
    }

    /**
     * Export providers
     */
    public function export()
    {
        $providers = Provider::with('account')->get();

        $filename = 'providers.csv';
        $handle = fopen('php://temp', 'r+');

        // Headers
        fputcsv($handle, ['ID', 'Display Name', 'Provider Type', 'Status', 'Email', 'Phone']);

        // Data
        foreach ($providers as $provider) {
            fputcsv($handle, [
                $provider->provider_id,
                $provider->display_name,
                $provider->provider_type,
                $provider->provider_status,
                $provider->contact_email,
                $provider->contact_phone
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
