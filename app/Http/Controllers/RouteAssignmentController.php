<?php

namespace App\Http\Controllers;

use App\Models\RouteAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RouteAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routeAssignments = RouteAssignment::paginate(15);
        return view('admin.route-assignments.index', compact('routeAssignments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.route-assignments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,route_id',
            'driver_id' => 'required|exists:drivers,driver_id',
            'vehicle_id' => 'required|exists:vehicles,vehicle_id',
            'assignment_date' => 'required|date|after:today',
            'assignment_status' => 'required|in:active,inactive,completed,cancelled'
        ]);

        RouteAssignment::create($validated);

        return redirect()->route('admin.route-assignments.index')->with('success', 'Asignación de ruta creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(RouteAssignment $routeAssignment)
    {
        return view('admin.route-assignments.show', compact('routeAssignment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RouteAssignment $routeAssignment)
    {
        return view('admin.route-assignments.edit', compact('routeAssignment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RouteAssignment $routeAssignment)
    {
        $validated = $request->validate([
            'route_id' => 'required|exists:routes,route_id',
            'driver_id' => 'required|exists:drivers,driver_id',
            'vehicle_id' => 'required|exists:vehicles,vehicle_id',
            'assignment_date' => 'required|date',
            'assignment_status' => 'required|in:active,inactive,completed,cancelled'
        ]);

        $routeAssignment->update($validated);

        return redirect()->route('admin.route-assignments.index')->with('success', 'Asignación de ruta actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RouteAssignment $routeAssignment)
    {
        $routeAssignment->delete();

        return redirect()->route('admin.route-assignments.index')->with('success', 'Asignación de ruta eliminada exitosamente');
    }

    /**
     * Activate route assignment
     */
    public function activate(RouteAssignment $routeAssignment)
    {
        $routeAssignment->update(['assignment_status' => 'active']);

        return redirect()->route('admin.route-assignments.show', $routeAssignment)->with('success', 'Asignación de ruta activada exitosamente');
    }

    /**
     * Suspend route assignment
     */
    public function suspend(RouteAssignment $routeAssignment)
    {
        $routeAssignment->update(['assignment_status' => 'inactive']);

        return redirect()->route('admin.route-assignments.show', $routeAssignment)->with('success', 'Asignación de ruta suspendida exitosamente');
    }

    /**
     * Bulk update route assignments
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'route_assignment_ids' => 'required|array',
            'route_assignment_ids.*' => 'exists:route_assignments,route_assignment_id',
            'assignment_status' => 'required|in:active,inactive,completed,cancelled'
        ]);

        RouteAssignment::whereIn('route_assignment_id', $request->route_assignment_ids)
            ->update(['assignment_status' => $request->assignment_status]);

        return redirect()->route('admin.route-assignments.index')->with('success', 'Asignaciones de ruta actualizadas exitosamente');
    }

    /**
     * Export route assignments
     */
    public function export()
    {
        $routeAssignments = RouteAssignment::with(['route', 'driver', 'vehicle'])->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="route-assignments.csv"',
        ];

        $callback = function() use ($routeAssignments) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID', 'Ruta', 'Conductor', 'Vehículo', 'Fecha Asignación', 'Estado', 'Fecha Creación'
            ]);

            // Data
            foreach ($routeAssignments as $routeAssignment) {
                fputcsv($file, [
                    $routeAssignment->route_assignment_id,
                    $routeAssignment->route->route_name,
                    $routeAssignment->driver->first_name . ' ' . $routeAssignment->driver->last_name,
                    $routeAssignment->vehicle->make . ' ' . $routeAssignment->vehicle->model . ' (' . $routeAssignment->vehicle->license_plate . ')',
                    $routeAssignment->assignment_date->format('Y-m-d'),
                    $routeAssignment->assignment_status,
                    $routeAssignment->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

