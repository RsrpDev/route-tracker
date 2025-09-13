<?php

namespace App\Http\Controllers;

use App\Models\ParentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parents = ParentProfile::paginate(15);
        return view('admin.parents.index', compact('parents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.parents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'parent_status' => 'required|in:active,inactive,suspended'
        ]);

        ParentProfile::create($validated);

        return redirect()->route('admin.parents.index')->with('success', 'Padre creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParentProfile $parent)
    {
        return view('admin.parents.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ParentProfile $parent)
    {
        return view('admin.parents.edit', compact('parent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ParentProfile $parent)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20',
            'parent_status' => 'required|in:active,inactive,suspended'
        ]);

        $parent->update($validated);

        return redirect()->route('admin.parents.index')->with('success', 'Padre actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ParentProfile $parent)
    {
        $parent->delete();

        return redirect()->route('admin.parents.index')->with('success', 'Padre eliminado exitosamente');
    }

    /**
     * Activate parent
     */
    public function activate(ParentProfile $parent)
    {
        $parent->update(['parent_status' => 'active']);

        return redirect()->route('admin.parents.show', $parent)->with('success', 'Padre activado exitosamente');
    }

    /**
     * Suspend parent
     */
    public function suspend(ParentProfile $parent)
    {
        $parent->update(['parent_status' => 'suspended']);

        return redirect()->route('admin.parents.show', $parent)->with('success', 'Padre suspendido exitosamente');
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request, ParentProfile $parent)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('parent-documents', 'public');

        // Aquí podrías guardar la información del documento en la base de datos

        return redirect()->route('admin.parents.show', $parent)->with('success', 'Documento subido exitosamente');
    }

    /**
     * Bulk update parents
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'parent_ids' => 'required|array',
            'parent_ids.*' => 'exists:parents,parent_id',
            'parent_status' => 'required|in:active,inactive,suspended'
        ]);

        ParentProfile::whereIn('parent_id', $request->parent_ids)
            ->update(['parent_status' => $request->parent_status]);

        return redirect()->route('admin.parents.index')->with('success', 'Padres actualizados exitosamente');
    }

    /**
     * Export parents
     */
    public function export()
    {
        $parents = ParentProfile::with('account')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="parents.csv"',
        ];

        $callback = function() use ($parents) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID', 'Nombre', 'Apellido', 'Teléfono', 'Email', 'Dirección', 'Ciudad', 'Estado',
                'Código Postal', 'Contacto Emergencia', 'Teléfono Emergencia', 'Estado', 'Fecha Creación'
            ]);

            // Data
            foreach ($parents as $parent) {
                fputcsv($file, [
                    $parent->parent_id,
                    $parent->first_name,
                    $parent->last_name,
                    $parent->phone_number,
                    $parent->email,
                    $parent->address,
                    $parent->city,
                    $parent->state,
                    $parent->zip_code,
                    $parent->emergency_contact,
                    $parent->emergency_phone,
                    $parent->parent_status,
                    $parent->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show parent profile
     */
    public function profile()
    {
        $parent = ParentProfile::where('account_id', auth()->id())->firstOrFail();
        return view('parent.profile', compact('parent'));
    }

    /**
     * Update parent profile
     */
    public function updateProfile(Request $request)
    {
        $parent = ParentProfile::where('account_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'emergency_contact' => 'required|string|max:255',
            'emergency_phone' => 'required|string|max:20'
        ]);

        $parent->update($validated);

        return redirect()->route('parent.profile')->with('success', 'Perfil actualizado exitosamente');
    }


    /**
     * Show parent contracts (formerly subscriptions)
     */
    public function contracts()
    {
        $parent = ParentProfile::where('account_id', auth()->id())->firstOrFail();
        $contracts = $parent->subscriptions()
            ->with([
                'transportContract.student.school',
                'transportContract.provider'
            ])
            ->paginate(15);
        return view('parent.subscriptions.index', compact('contracts'));
    }

    /**
     * Show parent subscriptions (deprecated - use contracts)
     * @deprecated Use contracts() instead
     */
    public function subscriptions()
    {
        return $this->contracts();
    }

    /**
     * Show specific contract (formerly subscription)
     */
    public function showContract($contractId)
    {
        $parent = ParentProfile::where('account_id', auth()->id())->firstOrFail();
        $contract = $parent->subscriptions()
            ->with([
                'transportContract.student.school',
                'transportContract.pickupRoute',
                'transportContract.dropoffRoute',
                'transportContract.provider'
            ])
            ->findOrFail($contractId);
        return view('parent.subscriptions.show', compact('contract'));
    }

    /**
     * Show specific subscription (deprecated - use showContract)
     * @deprecated Use showContract() instead
     */
    public function showSubscription($subscriptionId)
    {
        return $this->showContract($subscriptionId);
    }

    /**
     * Show payments for a specific contract (formerly subscription)
     */
    public function contractPayments($contractId)
    {
        $parent = ParentProfile::where('account_id', auth()->id())->firstOrFail();
        $contract = $parent->subscriptions()
            ->with([
                'transportContract.student.school',
                'transportContract.provider'
            ])
            ->findOrFail($contractId);

        $payments = $contract->payments()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Debug: verificar datos
        \Log::info('Contract Payments Debug', [
            'contract_id' => $contract->subscription_id,
            'payments_count' => $payments->count(),
            'student_name' => $contract->transportContract->student->first_name ?? 'N/A',
            'school_name' => $contract->transportContract->student->school->school_name ?? 'N/A',
            'provider_name' => $contract->transportContract->provider->business_name ?? 'N/A'
        ]);

        return view('parent.subscriptions.payments', compact('contract', 'payments'));
    }

    /**
     * Show payments for a specific subscription (deprecated - use contractPayments)
     * @deprecated Use contractPayments() instead
     */
    public function subscriptionPayments($subscriptionId)
    {
        return $this->contractPayments($subscriptionId);
    }

    /**
     * Show parent routes
     */
    public function routes()
    {
        $parent = ParentProfile::where('account_id', auth()->id())->firstOrFail();
        $routes = $parent->routes()->paginate(15);
        return view('parent.routes.index', compact('routes'));
    }

    /**
     * Show specific route
     */
    public function showRoute($routeId)
    {
        $parent = ParentProfile::where('account_id', auth()->id())->firstOrFail();
        $route = $parent->routes()->findOrFail($routeId);
        return view('parent.routes.show', compact('route'));
    }
}

