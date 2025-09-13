<?php

namespace Database\Seeders;

use App\Models\RouteLog;
use App\Models\Route;
use App\Models\Driver;
use App\Models\IndependentDriver;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RouteLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📊 Creando logs de rutas de ejemplo...');

        // Obtener rutas activas
        $routes = Route::where('active_flag', true)->get();

        if ($routes->isEmpty()) {
            $this->command->warn('No hay rutas activas disponibles. Ejecuta otros seeders primero.');
            return;
        }

        // Solo usar conductores empleados para los logs (tienen driver_id válido)
        $employedDrivers = Driver::with('account')->get();

        if ($employedDrivers->isEmpty()) {
            $this->command->warn('No hay conductores empleados disponibles. Ejecuta otros seeders primero.');
            return;
        }

        // Obtener vehículos
        $vehicles = Vehicle::all();

        $logCount = 0;

        // Crear logs para los últimos 7 días
        for ($day = 7; $day >= 1; $day--) {
            $date = Carbon::now()->subDays($day);

            foreach ($routes->take(3) as $route) {
                $driver = $employedDrivers->random();
                $vehicle = $vehicles->random();

                // Crear log de inicio de ruta
                $startTime = $date->copy()->setTimeFromTimeString($route->pickup_time ?? '07:00');
                $startLog = RouteLog::create([
                    'route_id' => $route->route_id,
                    'driver_id' => $driver->driver_id,
                    'vehicle_id' => $vehicle->vehicle_id,
                    'activity_type' => 'start',
                    'activity_description' => 'Inicio de ruta',
                    'scheduled_time' => $startTime,
                    'actual_time' => $startTime->copy()->addMinutes(rand(-10, 15)),
                    'status' => 'on_time',
                    'observations' => $this->getRandomObservations(),
                    'fuel_level' => rand(60, 100),
                    'weather_conditions' => $this->getRandomWeather(),
                    'traffic_conditions' => $this->getRandomTraffic(),
                    'gps_enabled' => false,
                ]);

                $startLog->calculateDelay();
                $startLog->save();
                $logCount++;

                // Crear log de recogida de estudiantes
                $pickupTime = $startTime->copy()->addMinutes(rand(15, 45));
                $pickupLog = RouteLog::create([
                    'route_id' => $route->route_id,
                    'driver_id' => $driver->driver_id,
                    'vehicle_id' => $vehicle->vehicle_id,
                    'activity_type' => 'pickup',
                    'activity_description' => 'Recogida de estudiantes',
                    'scheduled_time' => $pickupTime,
                    'actual_time' => $pickupTime->copy()->addMinutes(rand(-5, 10)),
                    'status' => 'on_time',
                    'observations' => $this->getRandomObservations(),
                    'students_picked_up' => rand(5, 15),
                    'address' => $this->getRandomAddress(),
                    'city' => $this->getRandomCity(),
                    'department' => $this->getRandomDepartment(),
                    'weather_conditions' => $this->getRandomWeather(),
                    'traffic_conditions' => $this->getRandomTraffic(),
                    'gps_enabled' => false,
                ]);

                $pickupLog->calculateDelay();
                $pickupLog->save();
                $logCount++;

                // Crear log de entrega de estudiantes
                $dropoffTime = $date->copy()->setTimeFromTimeString($route->dropoff_time ?? '15:00');
                $dropoffLog = RouteLog::create([
                    'route_id' => $route->route_id,
                    'driver_id' => $driver->driver_id,
                    'vehicle_id' => $vehicle->vehicle_id,
                    'activity_type' => 'dropoff',
                    'activity_description' => 'Entrega de estudiantes',
                    'scheduled_time' => $dropoffTime,
                    'actual_time' => $dropoffTime->copy()->addMinutes(rand(-5, 15)),
                    'status' => 'on_time',
                    'observations' => $this->getRandomObservations(),
                    'students_dropped_off' => rand(5, 15),
                    'address' => $route->school->address ?? $this->getRandomAddress(),
                    'city' => $this->getRandomCity(),
                    'department' => $this->getRandomDepartment(),
                    'weather_conditions' => $this->getRandomWeather(),
                    'traffic_conditions' => $this->getRandomTraffic(),
                    'gps_enabled' => false,
                ]);

                $dropoffLog->calculateDelay();
                $dropoffLog->save();
                $logCount++;

                // Crear log de fin de ruta
                $endTime = $dropoffTime->copy()->addMinutes(rand(10, 30));
                $endLog = RouteLog::create([
                    'route_id' => $route->route_id,
                    'driver_id' => $driver->driver_id,
                    'vehicle_id' => $vehicle->vehicle_id,
                    'activity_type' => 'end',
                    'activity_description' => 'Fin de ruta',
                    'scheduled_time' => $endTime,
                    'actual_time' => $endTime->copy()->addMinutes(rand(-5, 10)),
                    'status' => 'on_time',
                    'observations' => $this->getRandomObservations(),
                    'fuel_level' => rand(40, 80),
                    'odometer_reading' => rand(50000, 150000),
                    'weather_conditions' => $this->getRandomWeather(),
                    'traffic_conditions' => $this->getRandomTraffic(),
                    'gps_enabled' => false,
                ]);

                $endLog->calculateDelay();
                $endLog->save();
                $logCount++;

                // Ocasionalmente crear un incidente
                if (rand(1, 10) <= 2) { // 20% de probabilidad
                    $incidentTime = $pickupTime->copy()->addMinutes(rand(30, 120));
                    $incidentLog = RouteLog::create([
                        'route_id' => $route->route_id,
                        'driver_id' => $driver->driver_id,
                        'vehicle_id' => $vehicle->vehicle_id,
                        'activity_type' => 'incident',
                        'activity_description' => $this->getRandomIncidentType(),
                        'actual_time' => $incidentTime,
                        'status' => 'delayed',
                        'observations' => $this->getRandomObservations(),
                        'incident_details' => $this->getRandomIncidentDetails(),
                        'address' => $this->getRandomAddress(),
                        'city' => $this->getRandomCity(),
                        'department' => $this->getRandomDepartment(),
                        'weather_conditions' => $this->getRandomWeather(),
                        'traffic_conditions' => $this->getRandomTraffic(),
                        'gps_enabled' => false,
                    ]);

                    $incidentLog->save();
                    $logCount++;
                }
            }
        }

        $this->command->info("✅ RouteLogSeeder completado: {$logCount} logs de rutas creados");
    }

    private function getRandomObservations(): string
    {
        $observations = [
            'Ruta normal, sin novedades',
            'Tráfico moderado en el centro',
            'Clima favorable para la conducción',
            'Estudiantes puntuales en la recogida',
            'Vehículo en buen estado',
            'Ruta fluida sin contratiempos',
            'Estudiantes tranquilos durante el viaje',
            'Condiciones de tráfico normales',
            'Sin problemas mecánicos',
            'Ruta completada exitosamente'
        ];

        return $observations[array_rand($observations)];
    }

    private function getRandomWeather(): string
    {
        $weather = ['sunny', 'cloudy', 'rainy', 'stormy'];
        return $weather[array_rand($weather)];
    }

    private function getRandomTraffic(): string
    {
        $traffic = ['light', 'moderate', 'heavy', 'congested'];
        return $traffic[array_rand($traffic)];
    }

    private function getRandomAddress(): string
    {
        $addresses = [
            'Calle 80 #12-34',
            'Carrera 15 #45-67',
            'Avenida 68 #23-45',
            'Calle 127 #89-12',
            'Carrera 7 #34-56',
            'Avenida Boyacá #78-90'
        ];

        return $addresses[array_rand($addresses)];
    }

    private function getRandomCity(): string
    {
        $cities = ['Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena'];
        return $cities[array_rand($cities)];
    }

    private function getRandomDepartment(): string
    {
        $departments = ['Cundinamarca', 'Antioquia', 'Valle del Cauca', 'Atlántico', 'Bolívar'];
        return $departments[array_rand($departments)];
    }

    private function getRandomIncidentType(): string
    {
        $incidents = [
            'Retraso por tráfico',
            'Problema mecánico menor',
            'Estudiante enfermo',
            'Accidente menor',
            'Desvío por obras',
            'Problema de combustible'
        ];

        return $incidents[array_rand($incidents)];
    }

    private function getRandomIncidentDetails(): string
    {
        $details = [
            'Se resolvió rápidamente sin afectar el servicio',
            'Requiere revisión técnica del vehículo',
            'Estudiante fue atendido por personal médico',
            'Daños menores, sin heridos',
            'Ruta alternativa tomada exitosamente',
            'Combustible insuficiente, se repostó en ruta'
        ];

        return $details[array_rand($details)];
    }
}
