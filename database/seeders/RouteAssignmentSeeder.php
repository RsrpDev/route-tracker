<?php

namespace Database\Seeders;

use App\Models\RouteAssignment;
use App\Models\Route;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class RouteAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener rutas, conductores y vehículos
        $routes = Route::all();
        $drivers = Driver::all();
        $vehicles = Vehicle::all();

        foreach ($routes as $index => $route) {
            if ($drivers->count() > 0 && $vehicles->count() > 0) {
                RouteAssignment::create([
                    'route_id' => $route->route_id,
                    'driver_id' => $drivers->random()->driver_id,
                    'vehicle_id' => $vehicles->random()->vehicle_id,
                    'start_date' => now()->subDays(rand(1, 30)),
                    'end_date' => null,
                    'assignment_status' => 'active',
                ]);
            }
        }

        $totalAssignments = RouteAssignment::count();
        $this->command->info("✅ RouteAssignmentSeeder completado: {$totalAssignments} asignaciones creadas");
    }
}
