<?php

namespace Database\Seeders;

use App\Models\Provider;
use App\Models\Vehicle;
use App\Models\IndependentDriver;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class IndependentDriverVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚐 Creando vehículos para conductores independientes...');

        // Obtener todos los conductores independientes
        $independentDrivers = IndependentDriver::with('provider')->get();

        // Datos específicos de Colombia para vehículos
        $colombianVehicleData = [
            'brands' => ['Chevrolet', 'Ford', 'Nissan', 'Toyota', 'Hyundai', 'Kia', 'Mazda', 'Renault'],
            'models' => [
                'Chevrolet' => ['Spark', 'Aveo', 'Optra', 'Cruze', 'Captiva'],
                'Ford' => ['Fiesta', 'Focus', 'EcoSport', 'Explorer', 'Ranger'],
                'Nissan' => ['March', 'Versa', 'Sentra', 'X-Trail', 'Navara'],
                'Toyota' => ['Yaris', 'Corolla', 'Camry', 'RAV4', 'Hilux'],
                'Hyundai' => ['i10', 'Accent', 'Elantra', 'Tucson', 'Santa Fe'],
                'Kia' => ['Picanto', 'Rio', 'Cerato', 'Sportage', 'Sorento'],
                'Mazda' => ['2', '3', '6', 'CX-3', 'CX-5'],
                'Renault' => ['Logan', 'Sandero', 'Fluence', 'Duster', 'Koleos']
            ],
            'colors' => ['Blanco', 'Negro', 'Gris', 'Plateado', 'Azul', 'Rojo', 'Verde', 'Amarillo'],
            'fuel_types' => ['gasolina', 'diésel', 'gas', 'híbrido'],
            'vehicle_classes' => ['particular', 'público', 'comercial'],
            'service_types' => ['particular', 'público', 'escolar', 'turismo'],
            'insurance_companies' => ['Sura', 'Colpatria', 'Bolívar', 'Liberty', 'Mapfre', 'Allianz'],
            'cities' => ['Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena']
        ];

        $data = $colombianVehicleData;

        foreach ($independentDrivers as $index => $driver) {
            $provider = $driver->provider;
            $brand = $data['brands'][array_rand($data['brands'])];
            $model = $data['models'][$brand][array_rand($data['models'][$brand])];
            $color = $data['colors'][array_rand($data['colors'])];
            $fuelType = $data['fuel_types'][array_rand($data['fuel_types'])];
            $vehicleClass = $data['vehicle_classes'][array_rand($data['vehicle_classes'])];
            $serviceType = $data['service_types'][array_rand($data['service_types'])];
            $insuranceCompany = $data['insurance_companies'][array_rand($data['insurance_companies'])];
            $city = $data['cities'][array_rand($data['cities'])];

            // Generar placa colombiana válida
            $plate = $this->generateColombianPlate();

            // Crear vehículo para el conductor independiente
            Vehicle::create([
                'provider_id' => $provider->provider_id,
                'plate' => $plate,
                'brand' => $brand,
                'model_year' => rand(2015, 2024),
                'serial_number' => $this->generateSerialNumber(),
                'engine_number' => $this->generateEngineNumber(),
                'chassis_number' => $this->generateChassisNumber(),
                'color' => $color,
                'fuel_type' => $fuelType,
                'cylinder_capacity' => rand(1000, 3000),
                'vehicle_class' => $vehicleClass,
                'service_type' => $serviceType,
                'capacity' => rand(8, 25), // Capacidad para transporte escolar
                'soat_expiration' => Carbon::now()->addMonths(rand(1, 12)),
                'soat_number' => 'SOAT' . rand(100000, 999999),
                'insurance_expiration' => Carbon::now()->addMonths(rand(1, 12)),
                'insurance_company' => $insuranceCompany,
                'insurance_policy_number' => 'POL' . rand(100000, 999999),
                'technical_inspection_expiration' => Carbon::now()->addMonths(rand(1, 12)),
                'revision_expiration' => Carbon::now()->addMonths(rand(1, 12)),
                'odometer_reading' => rand(10000, 200000),
                'last_maintenance_date' => Carbon::now()->subDays(rand(1, 90)),
                'next_maintenance_date' => Carbon::now()->addDays(rand(30, 180)),
                'vehicle_status' => $index < 6 ? 'active' : 'inactive', // 6 activos, 2 inactivos
            ]);

            $this->command->info("   ✅ Vehículo " . ($index + 1) . ": {$brand} {$model} - {$plate} ({$driver->given_name} {$driver->family_name})");
        }

        $this->command->info('✅ IndependentDriverVehicleSeeder completado: ' . $independentDrivers->count() . ' vehículos creados');
    }

    /**
     * Generar placa colombiana válida
     */
    private function generateColombianPlate(): string
    {
        $letters = ['ABC', 'DEF', 'GHI', 'JKL', 'MNO', 'PQR', 'STU', 'VWX', 'YZ'];
        $letterGroup = $letters[array_rand($letters)];
        $numbers = str_pad(rand(100, 999), 3, '0', STR_PAD_LEFT);
        return $letterGroup . $numbers;
    }

    /**
     * Generar número de serie
     */
    private function generateSerialNumber(): string
    {
        return 'SER' . rand(100000, 999999);
    }

    /**
     * Generar número de motor
     */
    private function generateEngineNumber(): string
    {
        return 'ENG' . rand(100000, 999999);
    }

    /**
     * Generar número de chasis
     */
    private function generateChassisNumber(): string
    {
        return 'CHS' . rand(100000, 999999);
    }
}
