<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schools = School::paginate(15);
        return view('admin.schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'school_name' => 'required|string|max:255',
            'school_type' => 'required|in:public,private,charter',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'principal_name' => 'required|string|max:255',
            'school_status' => 'required|in:active,inactive,pending',
            'has_transport_service' => 'boolean'
        ]);

        School::create($validated);

        return redirect()->route('admin.schools.index')->with('success', 'Escuela creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(School $school)
    {
        return view('admin.schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(School $school)
    {
        return view('admin.schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'school_name' => 'required|string|max:255',
            'school_type' => 'required|in:public,private,charter',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'principal_name' => 'required|string|max:255',
            'school_status' => 'required|in:active,inactive,pending',
            'has_transport_service' => 'boolean'
        ]);

        $school->update($validated);

        return redirect()->route('admin.schools.index')->with('success', 'Escuela actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(School $school)
    {
        $school->delete();

        return redirect()->route('admin.schools.index')->with('success', 'Escuela eliminada exitosamente');
    }

    /**
     * Activate school
     */
    public function activate(School $school)
    {
        $school->update(['school_status' => 'active']);

        return redirect()->route('admin.schools.show', $school)->with('success', 'Escuela activada exitosamente');
    }

    /**
     * Suspend school
     */
    public function suspend(School $school)
    {
        $school->update(['school_status' => 'inactive']);

        return redirect()->route('admin.schools.show', $school)->with('success', 'Escuela suspendida exitosamente');
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request, School $school)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('school-documents', 'public');

        // Aquí podrías guardar la información del documento en la base de datos

        return redirect()->route('admin.schools.show', $school)->with('success', 'Documento subido exitosamente');
    }

    /**
     * Bulk update schools
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'school_ids' => 'required|array',
            'school_ids.*' => 'exists:schools,school_id',
            'school_status' => 'required|in:active,inactive,pending'
        ]);

        School::whereIn('school_id', $request->school_ids)
            ->update(['school_status' => $request->school_status]);

        return redirect()->route('admin.schools.index')->with('success', 'Escuelas actualizadas exitosamente');
    }

    /**
     * Export schools
     */
    public function export()
    {
        $schools = School::with('account')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="schools.csv"',
        ];

        $callback = function() use ($schools) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID', 'Nombre', 'Tipo', 'Dirección', 'Ciudad', 'Estado', 'Código Postal',
                'Teléfono', 'Email', 'Director', 'Estado', 'Fecha Creación'
            ]);

            // Data
            foreach ($schools as $school) {
                fputcsv($file, [
                    $school->school_id,
                    $school->school_name,
                    $school->school_type,
                    $school->address,
                    $school->city,
                    $school->state,
                    $school->zip_code,
                    $school->phone_number,
                    $school->email,
                    $school->principal_name,
                    $school->school_status,
                    $school->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show school profile
     */
    public function profile()
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();
        return view('school.profile', compact('school'));
    }

    /**
     * Update school profile
     */
    public function updateProfile(Request $request)
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'school_name' => 'required|string|max:255',
            'school_type' => 'required|in:public,private,charter',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'principal_name' => 'required|string|max:255'
        ]);

        $school->update($validated);

        return redirect()->route('school.profile')->with('success', 'Perfil actualizado exitosamente');
    }

    /**
     * Show school students
     */
    public function students(Request $request)
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();

        $query = $school->students()->with(['transportContract.provider', 'parent.account']);

        // Apply filters
        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('transport')) {
            $query->where('has_transport', $request->transport);
        }

        $students = $query->paginate(15);

        return view('school.students.index', compact('students'));
    }

    /**
     * Show specific student
     */
    public function showStudent($studentId)
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();
        $student = $school->students()
            ->with(['transportContract.provider', 'transportContract.pickupRoute', 'transportContract.dropoffRoute', 'transportContract.subscription', 'parent.account'])
            ->findOrFail($studentId);
        return view('school.students.show', compact('student'));
    }

    /**
     * Show school transport contracts
     */
    public function transportContracts(Request $request)
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();

        $query = \App\Models\StudentTransportContract::whereHas('student', function($q) use ($school) {
            $q->where('school_id', $school->school_id);
        })->with(['student', 'provider', 'pickupRoute', 'dropoffRoute', 'subscription']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('contract_status', $request->status);
        }

        if ($request->filled('provider')) {
            $query->whereHas('provider', function($q) use ($request) {
                $q->where('display_name', $request->provider);
            });
        }

        if ($request->filled('grade')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('grade', $request->grade);
            });
        }

        $transportContracts = $query->paginate(15);

        return view('school.transport-contracts.index', compact('transportContracts'));
    }

    /**
     * Show specific transport contract
     */
    public function showTransportContract($contractId)
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();
        $transportContract = \App\Models\StudentTransportContract::whereHas('student', function($q) use ($school) {
            $q->where('school_id', $school->school_id);
        })->with(['student.parent.account', 'provider', 'pickupRoute', 'dropoffRoute', 'subscription'])
            ->findOrFail($contractId);
        return view('school.transport-contracts.show', compact('transportContract'));
    }

    /**
     * Show school routes
     */
    public function routes(Request $request)
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();

        $query = $school->routes()
            ->with(['provider'])
            ->withCount(['transportContracts as transport_contracts_count' => function($q) use ($school) {
                $q->whereHas('student', function($studentQuery) use ($school) {
                    $studentQuery->where('school_id', $school->school_id);
                });
            }]);

        // Apply filters
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('active_flag', true);
            } elseif ($request->status === 'inactive') {
                $query->where('active_flag', false);
            }
        }

        if ($request->filled('provider')) {
            $query->whereHas('provider', function($q) use ($request) {
                $q->where('display_name', $request->provider);
            });
        }

        if ($request->filled('provider_type')) {
            $query->whereHas('provider', function($q) use ($request) {
                $q->where('provider_type', $request->provider_type);
            });
        }

        $routes = $query->paginate(15);

        return view('school.routes.index', compact('routes'));
    }

    /**
     * Show school providers
     */
    public function providers(Request $request)
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();

        $query = \App\Models\Provider::whereHas('routes', function($q) use ($school) {
            $q->where('school_id', $school->school_id);
        })->with(['routes' => function($q) use ($school) {
            $q->where('school_id', $school->school_id);
        }]);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('provider_status', $request->status);
        }

        if ($request->filled('provider_type')) {
            $query->where('provider_type', $request->provider_type);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('display_name', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_email', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_phone', 'like', '%' . $request->search . '%');
            });
        }

        $providers = $query->paginate(15);

        // Get statistics
        $totalProviders = $providers->total();
        $activeProviders = \App\Models\Provider::whereHas('routes', function($q) use ($school) {
            $q->where('school_id', $school->school_id);
        })->where('provider_status', 'active')->count();

        $providersByType = \App\Models\Provider::whereHas('routes', function($q) use ($school) {
            $q->where('school_id', $school->school_id);
        })->selectRaw('provider_type, count(*) as count')
          ->groupBy('provider_type')
          ->pluck('count', 'provider_type')
          ->toArray();

        return view('school.providers.index', compact('providers', 'totalProviders', 'activeProviders', 'providersByType'));
    }

    /**
     * Show specific route
     */
    public function showRoute($routeId)
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();
        $route = $school->routes()
            ->with(['provider'])
            ->findOrFail($routeId);

        // Get transport contracts for this route and school
        $transportContracts = \App\Models\StudentTransportContract::where(function($q) use ($route) {
            $q->where('pickup_route_id', $route->route_id)
              ->orWhere('dropoff_route_id', $route->route_id);
        })->whereHas('student', function($q) use ($school) {
            $q->where('school_id', $school->school_id);
        })->with(['student.parent.account', 'provider'])
            ->get();

        return view('school.routes.show', compact('route', 'transportContracts'));
    }

    /**
     * Show form to register school as transport provider
     */
    public function registerAsProvider()
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();

        // Verificar si ya está registrada como proveedor
        $existingProvider = \App\Models\Provider::where('linked_school_id', $school->school_id)
            ->where('provider_type', 'school_provider')
            ->first();

        if ($existingProvider) {
            return redirect()->route('provider.school.dashboard')
                ->with('info', 'Ya estás registrado como proveedor de transporte.');
        }

        return view('school.register-as-provider', compact('school'));
    }

    /**
     * Store school as transport provider
     */
    public function storeAsProvider(Request $request)
    {
        $school = School::where('account_id', auth()->id())->firstOrFail();

        // Verificar si ya está registrada como proveedor
        $existingProvider = \App\Models\Provider::where('linked_school_id', $school->school_id)
            ->where('provider_type', 'school_provider')
            ->first();

        if ($existingProvider) {
            return redirect()->route('provider.school.dashboard')
                ->with('info', 'Ya estás registrado como proveedor de transporte.');
        }

        $request->validate([
            'transport_service_name' => 'required|string|max:255',
            'transport_phone' => 'required|string|max:20',
            'transport_email' => 'required|email|max:255',
        ]);

        // Crear el proveedor de transporte
        $provider = \App\Models\Provider::create([
            'account_id' => auth()->id(),
            'provider_type' => 'school_provider',
            'display_name' => $request->transport_service_name,
            'contact_email' => $request->transport_email,
            'contact_phone' => $request->transport_phone,
            'provider_status' => 'active',
            'linked_school_id' => $school->school_id,
            'default_commission_rate' => 7.00,
        ]);

        return redirect()->route('provider.school.dashboard')
            ->with('success', '¡Te has registrado exitosamente como proveedor de transporte!');
    }
}

