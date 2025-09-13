<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::paginate(15);
        return view('admin.vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,provider_id',
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20|unique:vehicles',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'capacity' => 'required|integer|min:1',
            'vehicle_status' => 'required|in:active,inactive,maintenance,retired'
        ]);

        Vehicle::create($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehículo creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        return view('admin.vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,provider_id',
            'vehicle_type' => 'required|string|max:255',
            'license_plate' => 'required|string|max:20|unique:vehicles,license_plate,' . $vehicle->vehicle_id . ',vehicle_id',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'capacity' => 'required|integer|min:1',
            'vehicle_status' => 'required|in:active,inactive,maintenance,retired'
        ]);

        $vehicle->update($validated);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehículo actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehículo eliminado exitosamente');
    }

    /**
     * Assign driver to vehicle
     */
    public function assignDriver(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,driver_id'
        ]);

        $vehicle->update(['driver_id' => $request->driver_id]);

        return redirect()->route('admin.vehicles.show', $vehicle)->with('success', 'Conductor asignado exitosamente');
    }

    /**
     * Unassign driver from vehicle
     */
    public function unassignDriver(Vehicle $vehicle)
    {
        $vehicle->update(['driver_id' => null]);

        return redirect()->route('admin.vehicles.show', $vehicle)->with('success', 'Conductor desasignado exitosamente');
    }

    /**
     * Set vehicle to maintenance
     */
    public function maintenance(Vehicle $vehicle)
    {
        $vehicle->update(['vehicle_status' => 'maintenance']);

        return redirect()->route('admin.vehicles.show', $vehicle)->with('success', 'Vehículo puesto en mantenimiento');
    }

    /**
     * Activate vehicle
     */
    public function activate(Vehicle $vehicle)
    {
        $vehicle->update(['vehicle_status' => 'active']);

        return redirect()->route('admin.vehicles.show', $vehicle)->with('success', 'Vehículo activado exitosamente');
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('vehicle-documents', 'public');

        // Aquí podrías guardar la información del documento en la base de datos

        return redirect()->route('admin.vehicles.show', $vehicle)->with('success', 'Documento subido exitosamente');
    }

    /**
     * Bulk update vehicles
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'vehicle_ids' => 'required|array',
            'vehicle_ids.*' => 'exists:vehicles,vehicle_id',
            'vehicle_status' => 'required|in:active,inactive,maintenance,retired'
        ]);

        Vehicle::whereIn('vehicle_id', $request->vehicle_ids)
            ->update(['vehicle_status' => $request->vehicle_status]);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehículos actualizados exitosamente');
    }

    /**
     * Export vehicles
     */
    public function export()
    {
        $vehicles = Vehicle::with(['provider', 'driver'])->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="vehicles.csv"',
        ];

        $callback = function() use ($vehicles) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID', 'Proveedor', 'Tipo', 'Placa', 'Marca', 'Modelo', 'Año',
                'Capacidad', 'Estado', 'Conductor', 'Fecha Creación'
            ]);

            // Data
            foreach ($vehicles as $vehicle) {
                fputcsv($file, [
                    $vehicle->vehicle_id,
                    $vehicle->provider->display_name ?? 'N/A',
                    $vehicle->vehicle_type,
                    $vehicle->license_plate,
                    $vehicle->make,
                    $vehicle->model,
                    $vehicle->year,
                    $vehicle->capacity,
                    $vehicle->vehicle_status,
                    $vehicle->driver->display_name ?? 'N/A',
                    $vehicle->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

