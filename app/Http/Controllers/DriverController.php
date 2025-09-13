<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = Driver::paginate(15);
        return view('admin.drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.drivers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'provider_id' => 'required|exists:providers,provider_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:50|unique:drivers',
            'license_expiry' => 'required|date|after:today',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'driver_status' => 'required|in:active,inactive,on_leave,suspended'
        ]);

        Driver::create($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Conductor creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        return view('admin.drivers.show', compact('driver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,account_id',
            'provider_id' => 'required|exists:providers,provider_id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:50|unique:drivers,license_number,' . $driver->driver_id . ',driver_id',
            'license_expiry' => 'required|date|after:today',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'driver_status' => 'required|in:active,inactive,on_leave,suspended'
        ]);

        $driver->update($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'Conductor actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        $driver->delete();

        return redirect()->route('admin.drivers.index')->with('success', 'Conductor eliminado exitosamente');
    }

    /**
     * Activate driver
     */
    public function activate(Driver $driver)
    {
        $driver->update(['driver_status' => 'active']);

        return redirect()->route('admin.drivers.show', $driver)->with('success', 'Conductor activado exitosamente');
    }

    /**
     * Suspend driver
     */
    public function suspend(Driver $driver)
    {
        $driver->update(['driver_status' => 'suspended']);

        return redirect()->route('admin.drivers.show', $driver)->with('success', 'Conductor suspendido exitosamente');
    }

    /**
     * Set driver on leave
     */
    public function leave(Driver $driver)
    {
        $driver->update(['driver_status' => 'on_leave']);

        return redirect()->route('admin.drivers.show', $driver)->with('success', 'Conductor puesto en licencia');
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request, Driver $driver)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('driver-documents', 'public');

        // Aquí podrías guardar la información del documento en la base de datos

        return redirect()->route('admin.drivers.show', $driver)->with('success', 'Documento subido exitosamente');
    }

    /**
     * Bulk update drivers
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'driver_ids' => 'required|array',
            'driver_ids.*' => 'exists:drivers,driver_id',
            'driver_status' => 'required|in:active,inactive,on_leave,suspended'
        ]);

        Driver::whereIn('driver_id', $request->driver_ids)
            ->update(['driver_status' => $request->driver_status]);

        return redirect()->route('admin.drivers.index')->with('success', 'Conductores actualizados exitosamente');
    }

    /**
     * Export drivers
     */
    public function export()
    {
        $drivers = Driver::with(['account', 'provider'])->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="drivers.csv"',
        ];

        $callback = function() use ($drivers) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID', 'Nombre', 'Apellido', 'Número Licencia', 'Vencimiento Licencia',
                'Teléfono', 'Email', 'Proveedor', 'Estado', 'Fecha Creación'
            ]);

            // Data
            foreach ($drivers as $driver) {
                fputcsv($file, [
                    $driver->driver_id,
                    $driver->first_name,
                    $driver->last_name,
                    $driver->license_number,
                    $driver->license_expiry->format('Y-m-d'),
                    $driver->phone_number,
                    $driver->email,
                    $driver->provider->display_name ?? 'N/A',
                    $driver->driver_status,
                    $driver->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

