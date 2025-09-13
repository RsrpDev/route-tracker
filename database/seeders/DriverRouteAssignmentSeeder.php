<?php

namespace Database\Seeders;

use App\Models\RouteAssignment;
use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class DriverRouteAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚌 Creando asignaciones de rutas para conductores empleados...');

        // Obtener conductores empleados
        $drivers = Driver::with('provider')->get();

        if ($drivers->isEmpty()) {
            $this->command->warn('No hay conductores empleados disponibles. Ejecuta EmployedDriverSeeder primero.');
            return;
        }

        // Obtener vehículos
        $vehicles = Vehicle::all();

        if ($vehicles->isEmpty()) {
            $this->command->warn('No hay vehículos disponibles. Ejecuta otros seeders primero.');
            return;
        }

        $assignmentCount = 0;

        foreach ($drivers as $driver) {
            // Obtener rutas del provider del conductor
            $providerRoutes = Route::where('provider_id', $driver->provider_id)
                ->where('active_flag', true)
                ->get();

            if ($providerRoutes->isEmpty()) {
                $this->command->warn("No hay rutas activas para el provider {$driver->provider->display_name}");
                continue;
            }

            // Asignar 2-3 rutas por conductor
            $routesToAssign = $providerRoutes->take(rand(2, 3));

            foreach ($routesToAssign as $route) {
                // Seleccionar un vehículo del mismo provider
                $vehicle = $vehicles->where('provider_id', $driver->provider_id)->first();

                if (!$vehicle) {
                    $this->command->warn("No hay vehículos disponibles para el provider {$driver->provider->display_name}");
                    continue;
                }

                // Crear asignación
                RouteAssignment::create([
                    'route_id' => $route->route_id,
                    'driver_id' => $driver->driver_id,
                    'vehicle_id' => $vehicle->vehicle_id,
                    'start_date' => now()->subDays(rand(1, 30))->format('Y-m-d'),
                    'end_date' => null, // Sin fecha de fin (asignación activa)
                    'assignment_status' => 'active',
                ]);

                $assignmentCount++;

                $this->command->info("   ✅ Asignada ruta '{$route->route_name}' al conductor {$driver->given_name} {$driver->family_name}");
            }
        }

        $this->command->info("✅ DriverRouteAssignmentSeeder completado: {$assignmentCount} asignaciones creadas");
    }
}
