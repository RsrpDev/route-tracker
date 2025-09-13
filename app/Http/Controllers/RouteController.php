<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::paginate(15);
        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.routes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,provider_id',
            'school_id' => 'nullable|exists:schools,school_id',
            'route_name' => 'required|string|max:255',
            'origin_address' => 'required|string|max:500',
            'destination_address' => 'required|string|max:500',
            'capacity' => 'required|integer|min:1',
            'monthly_price' => 'required|numeric|min:0',
            'pickup_time' => 'nullable|date_format:H:i',
            'dropoff_time' => 'nullable|date_format:H:i',
            'schedule_days' => 'nullable|array',
            'schedule_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'route_description' => 'nullable|string|max:1000',
            'estimated_duration_minutes' => 'nullable|integer|min:1',
            'active_flag' => 'boolean'
        ]);

        Route::create($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Ruta creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        return view('admin.routes.show', compact('route'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Route $route)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:providers,provider_id',
            'school_id' => 'required|exists:schools,school_id',
            'route_name' => 'required|string|max:255',
            'route_type' => 'required|in:morning,afternoon,full_day',
            'start_location' => 'required|string|max:500',
            'end_location' => 'required|string|max:500',
            'estimated_duration' => 'required|integer|min:1',
            'route_status' => 'required|in:active,inactive,maintenance'
        ]);

        $route->update($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Ruta actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete();

        return redirect()->route('admin.routes.index')->with('success', 'Ruta eliminada exitosamente');
    }

    /**
     * Activate route
     */
    public function activate(Route $route)
    {
        $route->update(['route_status' => 'active']);

        return redirect()->route('admin.routes.show', $route)->with('success', 'Ruta activada exitosamente');
    }

    /**
     * Suspend route
     */
    public function suspend(Route $route)
    {
        $route->update(['route_status' => 'inactive']);

        return redirect()->route('admin.routes.show', $route)->with('success', 'Ruta suspendida exitosamente');
    }

    /**
     * Upload documents
     */
    public function uploadDocuments(Request $request, Route $route)
    {
        $request->validate([
            'document_type' => 'required|string|max:255',
            'document_file' => 'required|file|max:10240'
        ]);

        $file = $request->file('document_file');
        $path = $file->store('route-documents', 'public');

        // Aquí podrías guardar la información del documento en la base de datos

        return redirect()->route('admin.routes.show', $route)->with('success', 'Documento subido exitosamente');
    }

    /**
     * Bulk update routes
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'route_ids' => 'required|array',
            'route_ids.*' => 'exists:routes,route_id',
            'route_status' => 'required|in:active,inactive,maintenance'
        ]);

        Route::whereIn('route_id', $request->route_ids)
            ->update(['route_status' => $request->route_status]);

        return redirect()->route('admin.routes.index')->with('success', 'Rutas actualizadas exitosamente');
    }

    /**
     * Export routes
     */
    public function export()
    {
        $routes = Route::with(['provider', 'school'])->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="routes.csv"',
        ];

        $callback = function() use ($routes) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, [
                'ID', 'Nombre', 'Tipo', 'Proveedor', 'Escuela', 'Ubicación Inicio', 'Ubicación Fin',
                'Duración Estimada', 'Estado', 'Fecha Creación'
            ]);

            // Data
            foreach ($routes as $route) {
                fputcsv($file, [
                    $route->route_id,
                    $route->route_name,
                    $route->route_type,
                    $route->provider->display_name ?? 'N/A',
                    $route->school->school_name ?? 'N/A',
                    $route->start_location,
                    $route->end_location,
                    $route->estimated_duration . ' min',
                    $route->route_status,
                    $route->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

