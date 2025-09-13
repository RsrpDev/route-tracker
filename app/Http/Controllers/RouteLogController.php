<?php

namespace App\Http\Controllers;

use App\Models\RouteLog;
use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RouteLogController extends Controller
{
    /**
     * Iniciar una ruta
     */
    public function startRoute(Request $request, Route $route)
    {
        $account = Auth::user();

        // Verificar que el conductor tenga acceso a esta ruta
        if ($account->account_type !== 'driver') {
            abort(403, 'Solo conductores pueden iniciar rutas.');
        }

        // Obtener el conductor empleado o independiente
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

        // Verificar que no haya una ruta activa ya iniciada hoy
        $existingLog = RouteLog::where('route_id', $route->route_id)
            ->where('driver_id', $driver->driver_id ?? $driver->independent_driver_id)
            ->whereDate('actual_time', today())
            ->where('activity_type', 'start')
            ->where('status', '!=', 'cancelled')
            ->first();

        if ($existingLog) {
            return response()->json([
                'message' => 'Ya tienes una ruta iniciada para hoy.',
                'existing_log' => $existingLog
            ], 400);
        }

        // Crear el log de inicio de ruta
        $log = RouteLog::create([
            'route_id' => $route->route_id,
            'driver_id' => $driver->driver_id ?? $driver->independent_driver_id,
            'vehicle_id' => $routeAssignment->vehicle_id,
            'activity_type' => 'start',
            'activity_description' => 'Inicio de ruta',
            'scheduled_time' => $route->pickup_time ? today()->setTimeFromTimeString($route->pickup_time) : null,
            'actual_time' => now(),
            'status' => 'on_time',
            'observations' => $request->input('observations'),
            'fuel_level' => $request->input('fuel_level'),
            'weather_conditions' => $request->input('weather_conditions'),
            'traffic_conditions' => $request->input('traffic_conditions'),
            'gps_enabled' => false, // Para futuras versiones
        ]);

        // Calcular retraso si hay hora programada
        $log->calculateDelay();
        $log->save();

        return response()->json([
            'message' => 'Ruta iniciada exitosamente',
            'log' => $log,
            'status_text' => $log->status_text,
            'delay_text' => $log->delay_text
        ]);
    }

    /**
     * Finalizar una ruta
     */
    public function endRoute(Request $request, Route $route)
    {
        $account = Auth::user();

        if ($account->account_type !== 'driver') {
            abort(403, 'Solo conductores pueden finalizar rutas.');
        }

        $driver = $account->employedDriver ?? $account->independentDriver;
        if (!$driver) {
            abort(404, 'Perfil de conductor no encontrado.');
        }

        // Buscar el log de inicio de la ruta de hoy
        $startLog = RouteLog::where('route_id', $route->route_id)
            ->where('driver_id', $driver->driver_id ?? $driver->independent_driver_id)
            ->whereDate('actual_time', today())
            ->where('activity_type', 'start')
            ->where('status', '!=', 'cancelled')
            ->first();

        if (!$startLog) {
            return response()->json([
                'message' => 'No hay una ruta iniciada para finalizar.'
            ], 400);
        }

        // Crear el log de fin de ruta
        $log = RouteLog::create([
            'route_id' => $route->route_id,
            'driver_id' => $driver->driver_id ?? $driver->independent_driver_id,
            'vehicle_id' => $startLog->vehicle_id,
            'activity_type' => 'end',
            'activity_description' => 'Fin de ruta',
            'scheduled_time' => $route->dropoff_time ? today()->setTimeFromTimeString($route->dropoff_time) : null,
            'actual_time' => now(),
            'status' => 'on_time',
            'observations' => $request->input('observations'),
            'students_dropped_off' => $request->input('students_dropped_off', 0),
            'fuel_level' => $request->input('fuel_level'),
            'odometer_reading' => $request->input('odometer_reading'),
            'weather_conditions' => $request->input('weather_conditions'),
            'traffic_conditions' => $request->input('traffic_conditions'),
            'gps_enabled' => false,
        ]);

        $log->calculateDelay();
        $log->save();

        return response()->json([
            'message' => 'Ruta finalizada exitosamente',
            'log' => $log,
            'status_text' => $log->status_text,
            'delay_text' => $log->delay_text
        ]);
    }

    /**
     * Registrar recogida de estudiantes
     */
    public function pickupStudents(Request $request, Route $route)
    {
        $account = Auth::user();

        if ($account->account_type !== 'driver') {
            abort(403, 'Solo conductores pueden registrar recogidas.');
        }

        $driver = $account->employedDriver ?? $account->independentDriver;
        if (!$driver) {
            abort(404, 'Perfil de conductor no encontrado.');
        }

        $log = RouteLog::create([
            'route_id' => $route->route_id,
            'driver_id' => $driver->driver_id ?? $driver->independent_driver_id,
            'vehicle_id' => $request->input('vehicle_id'),
            'activity_type' => 'pickup',
            'activity_description' => 'Recogida de estudiantes',
            'scheduled_time' => $route->pickup_time ? today()->setTimeFromTimeString($route->pickup_time) : null,
            'actual_time' => now(),
            'status' => 'on_time',
            'observations' => $request->input('observations'),
            'students_picked_up' => $request->input('students_picked_up', 0),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'department' => $request->input('department'),
            'weather_conditions' => $request->input('weather_conditions'),
            'traffic_conditions' => $request->input('traffic_conditions'),
            'gps_enabled' => false,
        ]);

        $log->calculateDelay();
        $log->save();

        return response()->json([
            'message' => 'Recogida registrada exitosamente',
            'log' => $log,
            'status_text' => $log->status_text,
            'delay_text' => $log->delay_text
        ]);
    }

    /**
     * Registrar entrega de estudiantes
     */
    public function dropoffStudents(Request $request, Route $route)
    {
        $account = Auth::user();

        if ($account->account_type !== 'driver') {
            abort(403, 'Solo conductores pueden registrar entregas.');
        }

        $driver = $account->employedDriver ?? $account->independentDriver;
        if (!$driver) {
            abort(404, 'Perfil de conductor no encontrado.');
        }

        $log = RouteLog::create([
            'route_id' => $route->route_id,
            'driver_id' => $driver->driver_id ?? $driver->independent_driver_id,
            'vehicle_id' => $request->input('vehicle_id'),
            'activity_type' => 'dropoff',
            'activity_description' => 'Entrega de estudiantes',
            'scheduled_time' => $route->dropoff_time ? today()->setTimeFromTimeString($route->dropoff_time) : null,
            'actual_time' => now(),
            'status' => 'on_time',
            'observations' => $request->input('observations'),
            'students_dropped_off' => $request->input('students_dropped_off', 0),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'department' => $request->input('department'),
            'weather_conditions' => $request->input('weather_conditions'),
            'traffic_conditions' => $request->input('traffic_conditions'),
            'gps_enabled' => false,
        ]);

        $log->calculateDelay();
        $log->save();

        return response()->json([
            'message' => 'Entrega registrada exitosamente',
            'log' => $log,
            'status_text' => $log->status_text,
            'delay_text' => $log->delay_text
        ]);
    }

    /**
     * Registrar un incidente
     */
    public function reportIncident(Request $request, Route $route)
    {
        $account = Auth::user();

        if ($account->account_type !== 'driver') {
            abort(403, 'Solo conductores pueden reportar incidentes.');
        }

        $driver = $account->employedDriver ?? $account->independentDriver;
        if (!$driver) {
            abort(404, 'Perfil de conductor no encontrado.');
        }

        $log = RouteLog::create([
            'route_id' => $route->route_id,
            'driver_id' => $driver->driver_id ?? $driver->independent_driver_id,
            'vehicle_id' => $request->input('vehicle_id'),
            'activity_type' => 'incident',
            'activity_description' => $request->input('incident_type', 'Incidente reportado'),
            'actual_time' => now(),
            'status' => 'delayed',
            'observations' => $request->input('observations'),
            'incident_details' => $request->input('incident_details'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'department' => $request->input('department'),
            'weather_conditions' => $request->input('weather_conditions'),
            'traffic_conditions' => $request->input('traffic_conditions'),
            'gps_enabled' => false,
        ]);

        return response()->json([
            'message' => 'Incidente reportado exitosamente',
            'log' => $log
        ]);
    }

    /**
     * Obtener logs de una ruta específica
     */
    public function getRouteLogs(Route $route)
    {
        $account = Auth::user();

        if ($account->account_type !== 'driver') {
            abort(403, 'Solo conductores pueden ver logs de rutas.');
        }

        $driver = $account->employedDriver ?? $account->independentDriver;
        if (!$driver) {
            abort(404, 'Perfil de conductor no encontrado.');
        }

        $logs = RouteLog::where('route_id', $route->route_id)
            ->where('driver_id', $driver->driver_id ?? $driver->independent_driver_id)
            ->orderBy('actual_time', 'desc')
            ->with(['route', 'vehicle'])
            ->get();

        return response()->json([
            'logs' => $logs,
            'route' => $route
        ]);
    }

    /**
     * Obtener logs del conductor para hoy
     */
    public function getTodayLogs()
    {
        $account = Auth::user();

        if ($account->account_type !== 'driver') {
            abort(403, 'Solo conductores pueden ver logs.');
        }

        $driver = $account->employedDriver ?? $account->independentDriver;
        if (!$driver) {
            abort(404, 'Perfil de conductor no encontrado.');
        }

        $logs = RouteLog::where('driver_id', $driver->driver_id ?? $driver->independent_driver_id)
            ->whereDate('actual_time', today())
            ->orderBy('actual_time', 'desc')
            ->with(['route', 'vehicle'])
            ->get();

        return response()->json([
            'logs' => $logs,
            'driver' => $driver
        ]);
    }
}
