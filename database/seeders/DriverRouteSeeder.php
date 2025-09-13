<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Provider;
use App\Models\Driver;
use App\Models\Route;
use App\Models\RouteAssignment;
use App\Models\Vehicle;
use App\Models\School;

class DriverRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener escuelas existentes
        $schools = School::all();
        if ($schools->isEmpty()) {
            $this->command->warn('No hay escuelas disponibles. Ejecuta SchoolSeeder primero.');
            return;
        }

        // Obtener proveedores existentes
        $providers = Provider::all();
        if ($providers->isEmpty()) {
            $this->command->warn('No hay proveedores disponibles. Ejecuta ProviderSeeder primero.');
            return;
        }

        // NOTA: Los conductores se crean en EmployedDriverSeeder
        // Este seeder solo crea rutas y asignaciones para conductores existentes
        $this->command->info('⚠️  DriverRouteSeeder: Los conductores se crean en EmployedDriverSeeder');
        $this->command->info('⚠️  Este seeder solo debe ejecutarse después de crear los conductores');
    }

    private function createDriversForProvider(Provider $provider, $schools)
    {
        // Determinar cuántos conductores crear según el tipo de proveedor
        $driverCount = match($provider->provider_type) {
            'driver' => 1, // El proveedor es el conductor
            'company' => rand(3, 8), // Empresa con múltiples conductores
            'school_provider' => rand(2, 5), // Colegio con algunos conductores
            default => rand(1, 3)
        };

        for ($i = 0; $i < $driverCount; $i++) {
            $driver = Driver::create([
                'provider_id' => $provider->provider_id,
                'given_name' => fake()->firstName(),
                'family_name' => fake()->lastName(),
                'id_number' => fake()->unique()->numerify('##########'),
                'phone_number' => fake()->phoneNumber(),
                'address' => fake()->address(),
                'license_number' => fake()->unique()->regexify('[A-Z]{2}[0-9]{6}'),
                'license_category' => fake()->randomElement(['A1', 'A2', 'B1', 'B2', 'C1', 'C2']),
                'license_expiration' => fake()->dateTimeBetween('+1 year', '+5 years'),
                'years_experience' => fake()->numberBetween(1, 20),
                'driver_status' => fake()->randomElement(['active', 'active', 'active', 'inactive']), // Más probabilidad de activos
            ]);

            // Crear vehículo para el conductor
            $vehicle = Vehicle::create([
                'provider_id' => $provider->provider_id,
                'plate' => fake()->unique()->regexify('[A-Z]{3}[0-9]{3}'),
                'brand' => fake()->randomElement(['Toyota', 'Nissan', 'Chevrolet', 'Ford', 'Hyundai', 'Kia']),
                'model_year' => fake()->numberBetween(2015, 2024),
                'capacity' => fake()->numberBetween(8, 20),
                'soat_expiration' => fake()->dateTimeBetween('+6 months', '+2 years'),
                'insurance_expiration' => fake()->dateTimeBetween('+6 months', '+2 years'),
                'technical_inspection_expiration' => fake()->dateTimeBetween('+3 months', '+1 year'),
                'vehicle_status' => fake()->randomElement(['active', 'active', 'inactive']),
            ]);

            // Crear rutas para este conductor (1-3 rutas por conductor)
            $routeCount = fake()->numberBetween(1, 3);
            for ($j = 0; $j < $routeCount; $j++) {
                $school = $schools->random();

                $route = Route::create([
                    'provider_id' => $provider->provider_id,
                    'school_id' => $school->school_id,
                    'route_name' => fake()->randomElement([
                        'Ruta Principal',
                        'Ruta Norte',
                        'Ruta Sur',
                        'Ruta Centro',
                        'Ruta Residencial',
                        'Ruta Comercial'
                    ]) . ' - ' . $school->legal_name,
                    'origin_address' => fake()->address(),
                    'destination_address' => $school->address ?? fake()->address(),
                    'capacity' => fake()->numberBetween(8, 20),
                    'monthly_price' => fake()->numberBetween(80000, 200000),
                    'pickup_time' => fake()->time('06:00', '08:00'),
                    'dropoff_time' => fake()->time('14:00', '16:00'),
                    'schedule_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                    'route_description' => fake()->sentence(),
                    'estimated_duration_minutes' => fake()->numberBetween(15, 60),
                    'active_flag' => true,
                ]);

                // Asignar conductor y vehículo a la ruta
                RouteAssignment::create([
                    'route_id' => $route->route_id,
                    'driver_id' => $driver->driver_id,
                    'vehicle_id' => $vehicle->vehicle_id,
                    'start_date' => fake()->dateTimeBetween('-6 months', 'now'),
                    'end_date' => null, // Asignación activa
                    'assignment_status' => 'active',
                ]);
            }
        }
    }
}
