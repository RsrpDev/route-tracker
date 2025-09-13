<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Provider;
use App\Models\Account;
use App\Models\Vehicle;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SchoolDriverController extends Controller
{
    /**
     * Listar conductores del servicio de transporte de la escuela
     */
    public function index()
    {
        $user = Auth::user();

        // Obtener el provider de la escuela
        $provider = $this->getSchoolProvider($user);
        if (!$provider) {
            abort(403, 'No tienes un servicio de transporte registrado.');
        }

        $drivers = Driver::where('provider_id', $provider->provider_id)
            ->with(['account', 'vehicles'])
            ->orderBy('driver_status', 'asc')
            ->orderBy('given_name', 'asc')
            ->paginate(15);

        return view('provider.school.drivers.index', compact('drivers', 'provider'));
    }

    /**
     * Mostrar formulario para crear nuevo conductor
     */
    public function create()
    {
        $user = Auth::user();
        $provider = $this->getSchoolProvider($user);

        if (!$provider) {
            abort(403, 'No tienes un servicio de transporte registrado.');
        }

        // Obtener vehículos disponibles para asignar
        $availableVehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->where('vehicle_status', 'active')
            ->whereDoesntHave('drivers')
            ->get();

        return view('provider.school.drivers.create', compact('provider', 'availableVehicles'));
    }

    /**
     * Crear nuevo conductor
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $provider = $this->getSchoolProvider($user);

        if (!$provider) {
            abort(403, 'No tienes un servicio de transporte registrado.');
        }

        $request->validate([
            'email' => 'required|email|unique:accounts,email',
            'password' => 'required|min:8|confirmed',
            'given_name' => 'required|string|max:100',
            'family_name' => 'required|string|max:100',
            'id_number' => 'required|string|max:20|unique:drivers,id_number',
            'phone_number' => 'required|string|max:20',
            'license_number' => 'required|string|max:50',
            'license_category' => 'required|string|max:10',
            'license_expiration' => 'required|date|after:today',
            'years_experience' => 'required|integer|min:0|max:50',
            'vehicle_id' => 'nullable|exists:vehicles,vehicle_id',
        ]);

        DB::beginTransaction();

        try {
            // Crear cuenta de usuario
            $account = Account::create([
                'email' => $request->email,
                'password_hash' => Hash::make($request->password),
                'account_type' => 'driver',
                'account_status' => 'active',
                'verification_status' => 'verified',
                'phone_number' => $request->phone_number,
                'full_name' => $request->given_name . ' ' . $request->family_name,
                'id_number' => $request->id_number,
            ]);

            // Crear conductor
            $driver = Driver::create([
                'account_id' => $account->account_id,
                'provider_id' => $provider->provider_id,
                'given_name' => $request->given_name,
                'family_name' => $request->family_name,
                'id_number' => $request->id_number,
                'document_type' => 'CC',
                'phone_number' => $request->phone_number,
                'license_number' => $request->license_number,
                'license_category' => $request->license_category,
                'license_expiration' => $request->license_expiration,
                'license_issuing_authority' => 'Secretaría de Movilidad',
                'license_issuing_city' => 'Bogotá',
                'license_issue_date' => now()->subYears(rand(1, 5)),
                'years_experience' => $request->years_experience,
                'employment_status' => 'active',
                'hire_date' => now(),
                'driver_status' => 'active',
                'monthly_salary' => rand(800000, 1200000),
            ]);

            // Asignar vehículo si se especificó
            if ($request->vehicle_id) {
                Vehicle::where('vehicle_id', $request->vehicle_id)
                    ->update(['assigned_driver_id' => $driver->driver_id]);
            }

            DB::commit();

            return redirect()->route('provider.school.drivers.index')
                ->with('success', 'Conductor registrado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al registrar el conductor: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar detalles de un conductor
     */
    public function show(Driver $driver)
    {
        $user = Auth::user();
        $provider = $this->getSchoolProvider($user);

        if (!$provider || $driver->provider_id !== $provider->provider_id) {
            abort(403, 'No tienes permisos para ver este conductor.');
        }

        $driver->load(['account', 'vehicles', 'routeAssignments.route']);

        return view('provider.school.drivers.show', compact('driver', 'provider'));
    }

    /**
     * Mostrar formulario para editar conductor
     */
    public function edit(Driver $driver)
    {
        $user = Auth::user();
        $provider = $this->getSchoolProvider($user);

        if (!$provider || $driver->provider_id !== $provider->provider_id) {
            abort(403, 'No tienes permisos para editar este conductor.');
        }

        $driver->load(['account', 'vehicles']);

        $availableVehicles = Vehicle::where('provider_id', $provider->provider_id)
            ->where('vehicle_status', 'active')
            ->where(function($query) use ($driver) {
                $query->whereDoesntHave('drivers')
                      ->orWhereHas('drivers', function($q) use ($driver) {
                          $q->where('drivers.driver_id', $driver->driver_id);
                      });
            })
            ->get();

        return view('provider.school.drivers.edit', compact('driver', 'provider', 'availableVehicles'));
    }

    /**
     * Actualizar conductor
     */
    public function update(Request $request, Driver $driver)
    {
        $user = Auth::user();
        $provider = $this->getSchoolProvider($user);

        if (!$provider || $driver->provider_id !== $provider->provider_id) {
            abort(403, 'No tienes permisos para editar este conductor.');
        }

        $request->validate([
            'given_name' => 'required|string|max:100',
            'family_name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
            'license_number' => 'required|string|max:50',
            'license_category' => 'required|string|max:10',
            'license_expiration' => 'required|date',
            'years_experience' => 'required|integer|min:0|max:50',
            'driver_status' => 'required|in:active,inactive,suspended',
            'vehicle_id' => 'nullable|exists:vehicles,vehicle_id',
        ]);

        DB::beginTransaction();

        try {
            // Actualizar conductor
            $driver->update([
                'given_name' => $request->given_name,
                'family_name' => $request->family_name,
                'phone_number' => $request->phone_number,
                'license_number' => $request->license_number,
                'license_category' => $request->license_category,
                'license_expiration' => $request->license_expiration,
                'years_experience' => $request->years_experience,
                'driver_status' => $request->driver_status,
            ]);

            // Actualizar cuenta
            $driver->account->update([
                'phone_number' => $request->phone_number,
                'full_name' => $request->given_name . ' ' . $request->family_name,
            ]);

            // Actualizar asignación de vehículo
            if ($request->vehicle_id) {
                // Desasignar vehículo actual
                Vehicle::where('assigned_driver_id', $driver->driver_id)
                    ->update(['assigned_driver_id' => null]);

                // Asignar nuevo vehículo
                Vehicle::where('vehicle_id', $request->vehicle_id)
                    ->update(['assigned_driver_id' => $driver->driver_id]);
            }

            DB::commit();

            return redirect()->route('provider.school.drivers.show', $driver)
                ->with('success', 'Conductor actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Error al actualizar el conductor: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado del conductor
     */
    public function updateStatus(Request $request, Driver $driver)
    {
        $user = Auth::user();
        $provider = $this->getSchoolProvider($user);

        if (!$provider || $driver->provider_id !== $provider->provider_id) {
            abort(403, 'No tienes permisos para modificar este conductor.');
        }

        $request->validate([
            'driver_status' => 'required|in:active,inactive,suspended',
        ]);

        $driver->update(['driver_status' => $request->driver_status]);

        return back()->with('success', 'Estado del conductor actualizado exitosamente.');
    }

    /**
     * Obtener estadísticas de conductores
     */
    public function statistics()
    {
        $user = Auth::user();
        $provider = $this->getSchoolProvider($user);

        if (!$provider) {
            abort(403, 'No tienes un servicio de transporte registrado.');
        }

        $totalDrivers = Driver::where('provider_id', $provider->provider_id)->count();
        $activeDrivers = Driver::where('provider_id', $provider->provider_id)
            ->where('driver_status', 'active')->count();
        $inactiveDrivers = Driver::where('provider_id', $provider->provider_id)
            ->where('driver_status', 'inactive')->count();
        $suspendedDrivers = Driver::where('provider_id', $provider->provider_id)
            ->where('driver_status', 'suspended')->count();

        // Conductores con licencias próximas a vencer
        $expiringLicenses = Driver::where('provider_id', $provider->provider_id)
            ->where('license_expiration', '<=', now()->addDays(30))
            ->where('license_expiration', '>', now())
            ->count();

        // Conductores con licencias vencidas
        $expiredLicenses = Driver::where('provider_id', $provider->provider_id)
            ->where('license_expiration', '<', now())
            ->count();

        return view('provider.school.drivers.statistics', compact(
            'provider', 'totalDrivers', 'activeDrivers', 'inactiveDrivers',
            'suspendedDrivers', 'expiringLicenses', 'expiredLicenses'
        ));
    }

    /**
     * Obtener el provider de la escuela
     */
    private function getSchoolProvider($user)
    {
        // Si el usuario es un provider de escuela
        if ($user->account_type === 'provider' && $user->provider) {
            $provider = $user->provider;
            if ($provider->provider_type === 'school_provider') {
                return $provider;
            }
        }

        // Si el usuario es una escuela, buscar su provider
        if ($user->account_type === 'school' && $user->school) {
            return Provider::where('linked_school_id', $user->school->school_id)
                ->where('provider_type', 'school_provider')
                ->first();
        }

        return null;
    }
}
