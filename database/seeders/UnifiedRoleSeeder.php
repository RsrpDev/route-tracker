<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Provider;
use App\Models\School;
use App\Models\ParentProfile;
use App\Models\Driver;
use App\Models\IndependentDriver;
use App\Models\Student;
use App\Models\Route;
use App\Models\RouteAssignment;
use App\Models\Vehicle;
use App\Models\StudentTransportContract;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * Seeder unificado para crear cuentas relacionadas de todos los roles del sistema
 *
 * Este seeder crea un ecosistema completo de cuentas interconectadas:
 * - ADMINISTRADOR
 * - CONDUCTOR INDEPENDIENTE
 * - CONDUCTOR EMPLEADO EMPRESA
 * - CONDUCTOR EMPLEADO COLEGIO
 * - EMPRESA DE TRANSPORTE
 * - COLEGIO CON SERVICIO (Cuenta de Colegio + Cuenta de Colegio Prestador)
 * - COLEGIO SIN SERVICIO
 * - PADRE DE FAMILIA
 */
class UnifiedRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌐 Creando ecosistema completo de cuentas relacionadas...');

        // Crear cuentas base
        $accounts = $this->createBaseAccounts();

        // Crear relaciones entre cuentas
        $relationships = $this->createRelationships($accounts);

        // Crear datos adicionales (estudiantes, rutas, contratos)
        $this->createAdditionalData($accounts, $relationships);

        $this->command->info('✅ Ecosistema de cuentas relacionadas creado exitosamente.');
        $this->displayCredentials($accounts);
    }

    /**
     * Crear todas las cuentas base del sistema
     */
    private function createBaseAccounts(): array
    {
        $this->command->info('👥 Creando cuentas base...');

        $accounts = [];

        // 1. ADMINISTRADOR
        $accounts['admin'] = Account::create([
            'account_type' => 'admin',
            'full_name' => 'Super Administrador del Sistema',
            'email' => 'admin@routetracker.com',
            'password_hash' => Hash::make('admin123'),
            'phone_number' => '+573001234567',
            'id_number' => 'ADMIN001',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'account_status' => 'active',
        ]);

        // 2. PADRE DE FAMILIA
        $accounts['parent'] = Account::create([
            'account_type' => 'parent',
            'full_name' => 'María González de Rodríguez',
            'email' => 'maria.gonzalez@familia.com',
            'password_hash' => Hash::make('parent123'),
            'phone_number' => '+573001234568',
            'id_number' => '12345678-9',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'account_status' => 'active',
        ]);

        // 3. COLEGIO CON SERVICIO (Cuenta Principal)
        $accounts['school_with_service'] = Account::create([
            'account_type' => 'school',
            'full_name' => 'Colegio San José - Institución Educativa',
            'email' => 'colegio.sanjose@educacion.com',
            'password_hash' => Hash::make('school123'),
            'phone_number' => '+573001234569',
            'id_number' => '900123456-7',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'account_status' => 'active',
        ]);

        // 4. COLEGIO CON SERVICIO (Cuenta Prestador)
        $accounts['school_provider'] = Account::create([
            'account_type' => 'provider',
            'full_name' => 'Colegio San José - Servicio de Transporte',
            'email' => 'transporte.sanjose@educacion.com',
            'password_hash' => Hash::make('schoolprovider123'),
            'phone_number' => '+573001234570',
            'id_number' => 'SCHPROV001',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'account_status' => 'active',
        ]);

        // 5. COLEGIO SIN SERVICIO
        $accounts['school_without_service'] = Account::create([
            'account_type' => 'school',
            'full_name' => 'Instituto Los Andes - Sin Transporte',
            'email' => 'instituto.losandes@educacion.com',
            'password_hash' => Hash::make('school123'),
            'phone_number' => '+573001234571',
            'id_number' => '900987654-8',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'account_status' => 'active',
        ]);

        // 6. EMPRESA DE TRANSPORTE
        $accounts['transport_company'] = Account::create([
            'account_type' => 'provider',
            'full_name' => 'Transportes Escolares ABC S.A.S.',
            'email' => 'empresa.abc@transporte.com',
            'password_hash' => Hash::make('company123'),
            'phone_number' => '+573001234572',
            'id_number' => '900555666-7',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'account_status' => 'active',
        ]);

        // 7. CONDUCTOR INDEPENDIENTE
        $accounts['independent_driver'] = Account::create([
            'account_type' => 'driver',
            'full_name' => 'Carlos Andrés Rodríguez',
            'email' => 'carlos.rodriguez@conductor.com',
            'password_hash' => Hash::make('driver123'),
            'phone_number' => '+573001234573',
            'id_number' => '12345678-1',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'account_status' => 'active',
        ]);

        // 8. CONDUCTOR EMPLEADO EMPRESA
        $accounts['company_driver'] = Account::create([
            'account_type' => 'driver',
            'full_name' => 'Roberto Hernández Morales',
            'email' => 'roberto.hernandez@empresa.com',
            'password_hash' => Hash::make('driver123'),
            'phone_number' => '+573001234574',
            'id_number' => '12345678-2',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'account_status' => 'active',
        ]);

        // 9. CONDUCTOR EMPLEADO COLEGIO
        $accounts['school_driver'] = Account::create([
            'account_type' => 'driver',
            'full_name' => 'Fernando Vargas Castro',
            'email' => 'fernando.vargas@colegio.com',
            'password_hash' => Hash::make('driver123'),
            'phone_number' => '+573001234575',
            'id_number' => '12345678-3',
            'verification_status' => 'verified',
            'verified_at' => now(),
            'account_status' => 'active',
        ]);

        $this->command->info('✅ Cuentas base creadas exitosamente.');
        return $accounts;
    }

    /**
     * Crear relaciones entre las cuentas
     */
    private function createRelationships(array $accounts): array
    {
        $this->command->info('🔗 Creando relaciones entre cuentas...');

        // Crear perfil de padre
        $parentProfile = ParentProfile::create([
            'account_id' => $accounts['parent']->account_id,
            'address' => 'Calle 123 #45-67, Bogotá',
        ]);

        // Crear colegio con servicio
        $schoolWithService = School::create([
            'account_id' => $accounts['school_with_service']->account_id,
            'legal_name' => 'Fundación Colegio San José',
            'rector_name' => 'Dr. Pedro Martínez',
            'nit' => '900123456-7',
            'phone_number' => '+573001234569',
            'address' => 'Calle 78 #90-12, Bogotá',
        ]);

        // Crear colegio sin servicio
        $schoolWithoutService = School::create([
            'account_id' => $accounts['school_without_service']->account_id,
            'legal_name' => 'Instituto Los Andes',
            'rector_name' => 'Dra. Ana Lucía Mendoza',
            'nit' => '900987654-8',
            'phone_number' => '+573001234571',
            'address' => 'Carrera 45 #78-90, Medellín',
        ]);

        // Crear proveedor de empresa de transporte
        $companyProvider = Provider::create([
            'account_id' => $accounts['transport_company']->account_id,
            'provider_type' => 'company',
            'display_name' => 'Transportes Escolares ABC S.A.S.',
            'contact_email' => 'contacto@transportesabc.com',
            'contact_phone' => '+573001234572',
            'linked_school_id' => null,
            'default_commission_rate' => 5.00,
            'provider_status' => 'active',
        ]);

        // Crear proveedor de colegio con servicio
        $schoolProvider = Provider::create([
            'account_id' => $accounts['school_provider']->account_id,
            'provider_type' => 'school_provider',
            'display_name' => 'Colegio San José - Servicio de Transporte',
            'contact_email' => 'transporte@colegiosanjose.edu.co',
            'contact_phone' => '+573001234570',
            'linked_school_id' => $schoolWithService->school_id,
            'default_commission_rate' => 6.00,
            'provider_status' => 'active',
        ]);

        // Crear proveedor del conductor independiente primero
        $independentProvider = Provider::create([
            'account_id' => $accounts['independent_driver']->account_id,
            'provider_type' => 'driver',
            'driver_type' => 'independent',
            'display_name' => 'Transporte Carlos Rodríguez',
            'contact_email' => 'carlos.rodriguez@conductor.com',
            'contact_phone' => '+573001234573',
            'linked_school_id' => null,
            'default_commission_rate' => 7.00,
            'provider_status' => 'active',
            'driver_license_number' => 'LIC123456',
            'driver_license_category' => 'B1',
            'driver_license_expiration' => Carbon::now()->addYears(3),
            'driver_years_experience' => 8,
            'driver_status' => 'approved',
        ]);

        // Crear conductor independiente con su proveedor
        $independentDriver = IndependentDriver::create([
            'account_id' => $accounts['independent_driver']->account_id,
            'provider_id' => $independentProvider->provider_id,
            'given_name' => 'Carlos Andrés',
            'family_name' => 'Rodríguez',
            'id_number' => $accounts['independent_driver']->id_number,
            'document_type' => 'CC',
            'birth_city' => 'Bogotá',
            'birth_department' => 'Cundinamarca',
            'birth_date' => Carbon::now()->subYears(35)->subDays(120),
            'blood_type' => 'O+',
            'phone_number' => $accounts['independent_driver']->phone_number,
            'address' => 'Calle 45 #67-89, Chapinero, Bogotá',
            'emergency_contact_name' => 'María Rodríguez',
            'emergency_contact_phone' => '+573001234576',
            'emergency_contact_relationship' => 'Esposa',
            'license_number' => 'LIC123456',
            'license_category' => 'B1',
            'license_expiration' => Carbon::now()->addYears(3),
            'license_issuing_authority' => 'Secretaría de Movilidad de Bogotá',
            'license_issuing_city' => 'Bogotá',
            'license_issue_date' => Carbon::now()->subYears(5),
            'has_medical_certificate' => true,
            'medical_certificate_expiration' => Carbon::now()->addMonths(8),
            'has_psychological_certificate' => true,
            'psychological_certificate_expiration' => Carbon::now()->addMonths(6),
            'years_experience' => 8,
            'employment_status' => 'independent',
            'registration_date' => Carbon::now()->subMonths(18),
            'hourly_rate' => 25000,
            'monthly_rate' => 1200000,
            'driver_status' => 'active',
            'verification_notes' => 'Conductor independiente verificado según normativa colombiana',
            'verified_at' => now(),
            'verified_by' => $accounts['admin']->account_id,
        ]);

        // Crear conductor empleado de empresa
        $companyDriver = Driver::create([
            'account_id' => $accounts['company_driver']->account_id,
            'provider_id' => $companyProvider->provider_id,
            'given_name' => 'Roberto',
            'family_name' => 'Hernández Morales',
            'id_number' => $accounts['company_driver']->id_number,
            'document_type' => 'CC',
            'birth_city' => 'Medellín',
            'birth_department' => 'Antioquia',
            'birth_date' => Carbon::now()->subYears(42)->subDays(200),
            'blood_type' => 'A+',
            'phone_number' => $accounts['company_driver']->phone_number,
            'address' => 'Calle 78 #12-34, El Poblado, Medellín',
            'emergency_contact_name' => 'Carmen Morales',
            'emergency_contact_phone' => '+573001234577',
            'emergency_contact_relationship' => 'Esposa',
            'license_number' => 'LIC234567',
            'license_category' => 'B2',
            'license_expiration' => Carbon::now()->addYears(2),
            'license_issuing_authority' => 'Secretaría de Movilidad de Medellín',
            'license_issuing_city' => 'Medellín',
            'license_issue_date' => Carbon::now()->subYears(3),
            'has_medical_certificate' => true,
            'medical_certificate_expiration' => Carbon::now()->addMonths(10),
            'has_psychological_certificate' => true,
            'psychological_certificate_expiration' => Carbon::now()->addMonths(8),
            'years_experience' => 12,
            'employment_status' => 'active',
            'hire_date' => Carbon::now()->subMonths(24),
            'hourly_rate' => 20000,
            'monthly_salary' => 1000000,
            'driver_status' => 'active',
        ]);

        // Crear conductor empleado de colegio
        $schoolDriver = Driver::create([
            'account_id' => $accounts['school_driver']->account_id,
            'provider_id' => $schoolProvider->provider_id,
            'given_name' => 'Fernando',
            'family_name' => 'Vargas Castro',
            'id_number' => $accounts['school_driver']->id_number,
            'document_type' => 'CC',
            'birth_city' => 'Cali',
            'birth_department' => 'Valle del Cauca',
            'birth_date' => Carbon::now()->subYears(38)->subDays(150),
            'blood_type' => 'B+',
            'phone_number' => $accounts['school_driver']->phone_number,
            'address' => 'Calle 23 #45-67, Granada, Cali',
            'emergency_contact_name' => 'Luz Castro',
            'emergency_contact_phone' => '+573001234578',
            'emergency_contact_relationship' => 'Hermana',
            'license_number' => 'LIC345678',
            'license_category' => 'B1',
            'license_expiration' => Carbon::now()->addYears(4),
            'license_issuing_authority' => 'Secretaría de Movilidad de Cali',
            'license_issuing_city' => 'Cali',
            'license_issue_date' => Carbon::now()->subYears(2),
            'has_medical_certificate' => true,
            'medical_certificate_expiration' => Carbon::now()->addMonths(12),
            'has_psychological_certificate' => true,
            'psychological_certificate_expiration' => Carbon::now()->addMonths(9),
            'years_experience' => 6,
            'employment_status' => 'active',
            'hire_date' => Carbon::now()->subMonths(18),
            'hourly_rate' => 18000,
            'monthly_salary' => 900000,
            'driver_status' => 'active',
        ]);

        // Crear vehículos
        $vehicles = [
            // Vehículo del conductor independiente
            [
                'provider_id' => $independentProvider->provider_id,
                'plate' => 'ABC123',
                'brand' => 'Toyota',
                'model_year' => 2022,
                'capacity' => 15,
                'vehicle_status' => 'active',
                'soat_expiration' => Carbon::now()->addMonths(8),
                'insurance_expiration' => Carbon::now()->addMonths(10),
                'technical_inspection_expiration' => Carbon::now()->addMonths(6),
            ],
            // Vehículos de la empresa
            [
                'provider_id' => $companyProvider->provider_id,
                'plate' => 'DEF456',
                'brand' => 'Nissan',
                'model_year' => 2021,
                'capacity' => 20,
                'vehicle_status' => 'active',
                'soat_expiration' => Carbon::now()->addMonths(12),
                'insurance_expiration' => Carbon::now()->addMonths(11),
                'technical_inspection_expiration' => Carbon::now()->addMonths(9),
            ],
            [
                'provider_id' => $companyProvider->provider_id,
                'plate' => 'GHI789',
                'brand' => 'Ford',
                'model_year' => 2023,
                'capacity' => 18,
                'vehicle_status' => 'active',
                'soat_expiration' => Carbon::now()->addMonths(6),
                'insurance_expiration' => Carbon::now()->addMonths(8),
                'technical_inspection_expiration' => Carbon::now()->addMonths(4),
            ],
            // Vehículos del colegio
            [
                'provider_id' => $schoolProvider->provider_id,
                'plate' => 'JKL012',
                'brand' => 'Chevrolet',
                'model_year' => 2020,
                'capacity' => 16,
                'vehicle_status' => 'active',
                'soat_expiration' => Carbon::now()->addMonths(10),
                'insurance_expiration' => Carbon::now()->addMonths(9),
                'technical_inspection_expiration' => Carbon::now()->addMonths(7),
            ],
        ];

        $createdVehicles = [];
        foreach ($vehicles as $vehicleData) {
            $createdVehicles[] = Vehicle::create($vehicleData);
        }

        $this->command->info('✅ Relaciones entre cuentas creadas exitosamente.');

        return [
            'parent' => $parentProfile,
            'schoolWithService' => $schoolWithService,
            'schoolWithoutService' => $schoolWithoutService,
            'companyProvider' => $companyProvider,
            'schoolProvider' => $schoolProvider,
            'independentProvider' => $independentProvider,
            'independentDriver' => $independentDriver,
            'companyDriver' => $companyDriver,
            'schoolDriver' => $schoolDriver,
            'vehicles' => $createdVehicles,
        ];
    }

    /**
     * Crear datos adicionales (estudiantes, rutas, contratos)
     */
    private function createAdditionalData(array $accounts, array $relationships): void
    {
        $this->command->info('📊 Creando datos adicionales...');

        // Usar entidades relacionadas del método anterior
        $parent = $relationships['parent'];
        $schoolWithService = $relationships['schoolWithService'];
        $schoolWithoutService = $relationships['schoolWithoutService'];
        $companyProvider = $relationships['companyProvider'];
        $schoolProvider = $relationships['schoolProvider'];
        $independentProvider = $relationships['independentProvider'];
        $independentDriver = $relationships['independentDriver'];
        $companyDriver = $relationships['companyDriver'];
        $schoolDriver = $relationships['schoolDriver'];
        $createdVehicles = $relationships['vehicles'];

        // Crear estudiantes
        $students = [
            [
                'parent_id' => $parent->parent_id,
                'school_id' => $schoolWithService->school_id,
                'given_name' => 'Ana Sofía',
                'family_name' => 'González Rodríguez',
                'identity_number' => 'STU001',
                'birth_date' => '2010-03-15',
                'grade' => '6to',
                'shift' => 'morning',
                'address' => 'Calle 123 #45-67, Bogotá',
                'phone_number' => '+573001234579',
            ],
            [
                'parent_id' => $parent->parent_id,
                'school_id' => $schoolWithService->school_id,
                'given_name' => 'Carlos Andrés',
                'family_name' => 'González Rodríguez',
                'identity_number' => 'STU002',
                'birth_date' => '2009-07-22',
                'grade' => '7mo',
                'shift' => 'morning',
                'address' => 'Calle 123 #45-67, Bogotá',
                'phone_number' => '+573001234580',
            ],
            [
                'parent_id' => $parent->parent_id,
                'school_id' => $schoolWithoutService->school_id,
                'given_name' => 'María Camila',
                'family_name' => 'González Rodríguez',
                'identity_number' => 'STU003',
                'birth_date' => '2011-11-08',
                'grade' => '5to',
                'shift' => 'afternoon',
                'address' => 'Calle 123 #45-67, Bogotá',
                'phone_number' => '+573001234581',
            ],
        ];

        $createdStudents = [];
        foreach ($students as $studentData) {
            $createdStudents[] = Student::create($studentData);
        }


        // Crear rutas
        $routes = [
            // Rutas del conductor independiente
            [
                'provider_id' => $independentProvider->provider_id,
                'school_id' => $schoolWithService->school_id,
                'route_name' => 'Ruta Norte - Conductor Independiente',
                'origin_address' => 'Centro Comercial Plaza Norte',
                'destination_address' => $schoolWithService->address,
                'capacity' => 15,
                'monthly_price' => 180000.00,
                'active_flag' => true,
            ],
            // Rutas de la empresa
            [
                'provider_id' => $companyProvider->provider_id,
                'school_id' => $schoolWithService->school_id,
                'route_name' => 'Ruta Sur - Empresa ABC',
                'origin_address' => 'Centro Comercial Plaza Sur',
                'destination_address' => $schoolWithService->address,
                'capacity' => 20,
                'monthly_price' => 160000.00,
                'active_flag' => true,
            ],
            [
                'provider_id' => $companyProvider->provider_id,
                'school_id' => $schoolWithService->school_id,
                'route_name' => 'Ruta Este - Empresa ABC',
                'origin_address' => 'Centro Comercial Plaza Este',
                'destination_address' => $schoolWithService->address,
                'capacity' => 18,
                'monthly_price' => 170000.00,
                'active_flag' => true,
            ],
            // Rutas del colegio
            [
                'provider_id' => $schoolProvider->provider_id,
                'school_id' => $schoolWithService->school_id,
                'route_name' => 'Ruta Oeste - Colegio San José',
                'origin_address' => 'Centro Comercial Plaza Oeste',
                'destination_address' => $schoolWithService->address,
                'capacity' => 16,
                'monthly_price' => 150000.00,
                'active_flag' => true,
            ],
            // Rutas para Instituto Los Andes (colegio sin servicio)
            [
                'provider_id' => $independentProvider->provider_id,
                'school_id' => $schoolWithoutService->school_id,
                'route_name' => 'Ruta Los Andes - Norte',
                'origin_address' => 'Calle 100 #15-20, Bogotá',
                'destination_address' => $schoolWithoutService->address,
                'capacity' => 15,
                'monthly_price' => 140000.00,
                'active_flag' => true,
            ],
            [
                'provider_id' => $companyProvider->provider_id,
                'school_id' => $schoolWithoutService->school_id,
                'route_name' => 'Ruta Los Andes - Sur',
                'origin_address' => 'Carrera 30 #3-50, Bogotá',
                'destination_address' => $schoolWithoutService->address,
                'capacity' => 20,
                'monthly_price' => 130000.00,
                'active_flag' => true,
            ],
            [
                'provider_id' => $schoolProvider->provider_id,
                'school_id' => $schoolWithoutService->school_id,
                'route_name' => 'Ruta Los Andes - Este',
                'origin_address' => 'Avenida 7 #150-80, Bogotá',
                'destination_address' => $schoolWithoutService->address,
                'capacity' => 18,
                'monthly_price' => 135000.00,
                'active_flag' => true,
            ],
        ];

        $createdRoutes = [];
        foreach ($routes as $routeData) {
            $createdRoutes[] = Route::create($routeData);
        }

        // Crear asignaciones de rutas para conductores
        $routeAssignments = [
            // Asignaciones para rutas de San José
            [
                'route_id' => $createdRoutes[0]->route_id, // Ruta Norte - Conductor Independiente
                'driver_id' => $independentDriver->independent_driver_id,
                'vehicle_id' => $createdVehicles[0]->vehicle_id, // Vehículo del independiente
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addYears(1),
                'assignment_status' => 'active',
            ],
            [
                'route_id' => $createdRoutes[1]->route_id, // Ruta Sur - Empresa ABC
                'driver_id' => $companyDriver->driver_id,
                'vehicle_id' => $createdVehicles[1]->vehicle_id, // Vehículo de empresa 1
                'start_date' => now()->subMonths(3),
                'end_date' => now()->addYears(1),
                'assignment_status' => 'active',
            ],
            [
                'route_id' => $createdRoutes[2]->route_id, // Ruta Este - Empresa ABC
                'driver_id' => $companyDriver->driver_id,
                'vehicle_id' => $createdVehicles[2]->vehicle_id, // Vehículo de empresa 2
                'start_date' => now()->subMonths(4),
                'end_date' => now()->addYears(1),
                'assignment_status' => 'active',
            ],
            [
                'route_id' => $createdRoutes[3]->route_id, // Ruta Oeste - Colegio San José
                'driver_id' => $schoolDriver->driver_id,
                'vehicle_id' => $createdVehicles[3]->vehicle_id, // Vehículo de colegio
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addYears(1),
                'assignment_status' => 'active',
            ],
            // Asignaciones para rutas de Los Andes
            [
                'route_id' => $createdRoutes[4]->route_id, // Ruta Los Andes - Norte
                'driver_id' => $independentDriver->independent_driver_id,
                'vehicle_id' => $createdVehicles[0]->vehicle_id, // Vehículo del independiente
                'start_date' => now()->subMonths(5),
                'end_date' => now()->addYears(1),
                'assignment_status' => 'active',
            ],
            [
                'route_id' => $createdRoutes[5]->route_id, // Ruta Los Andes - Sur
                'driver_id' => $companyDriver->driver_id,
                'vehicle_id' => $createdVehicles[1]->vehicle_id, // Vehículo de empresa 1
                'start_date' => now()->subMonths(7),
                'end_date' => now()->addYears(1),
                'assignment_status' => 'active',
            ],
            [
                'route_id' => $createdRoutes[6]->route_id, // Ruta Los Andes - Este
                'driver_id' => $schoolDriver->driver_id,
                'vehicle_id' => $createdVehicles[3]->vehicle_id, // Vehículo de colegio
                'start_date' => now()->subMonths(8),
                'end_date' => now()->addYears(1),
                'assignment_status' => 'active',
            ],
        ];

        $createdRouteAssignments = [];
        foreach ($routeAssignments as $assignmentData) {
            $createdRouteAssignments[] = RouteAssignment::create($assignmentData);
        }

        // Crear contratos de transporte
        $contracts = [
            [
                'student_id' => $createdStudents[0]->student_id,
                'provider_id' => $independentProvider->provider_id,
                'pickup_route_id' => $createdRoutes[0]->route_id,
                'dropoff_route_id' => $createdRoutes[0]->route_id,
                'contract_start_date' => now()->subMonths(2),
                'contract_end_date' => now()->addMonths(10),
                'contract_status' => 'active',
                'monthly_fee' => 180000,
                'special_instructions' => 'Recoger en la puerta principal',
            ],
            [
                'student_id' => $createdStudents[1]->student_id,
                'provider_id' => $companyProvider->provider_id,
                'pickup_route_id' => $createdRoutes[1]->route_id,
                'dropoff_route_id' => $createdRoutes[1]->route_id,
                'contract_start_date' => now()->subMonths(1),
                'contract_end_date' => now()->addMonths(11),
                'contract_status' => 'active',
                'monthly_fee' => 160000,
                'special_instructions' => 'Sin instrucciones especiales',
            ],
        ];

        $createdContracts = [];
        foreach ($contracts as $contractData) {
            $createdContracts[] = StudentTransportContract::create($contractData);
        }

        // Crear suscripciones y pagos
        foreach ($createdContracts as $contract) {
            $subscription = Subscription::create([
                'contract_id' => $contract->contract_id,
                'billing_cycle' => 'monthly',
                'price_snapshot' => $contract->monthly_fee,
                'platform_fee_rate' => 5.00,
                'next_billing_date' => now()->addMonth(),
                'subscription_status' => 'active',
                'payment_plan_type' => 'monthly',
                'payment_plan_name' => 'Plan Mensual',
                'payment_plan_description' => 'Pago mensual recurrente',
                'discount_rate' => 0,
                'auto_renewal' => true,
                'plan_start_date' => now()->subMonths(2),
                'plan_end_date' => now()->addMonths(10),
                'payment_method' => 'pse',
                'is_active' => true,
            ]);

            // Crear pagos históricos
            for ($i = 1; $i <= 3; $i++) {
                $amount = $contract->monthly_fee;
                $platformFee = $amount * 0.05;
                $providerAmount = $amount - $platformFee;

                Payment::create([
                    'subscription_id' => $subscription->subscription_id,
                    'period_start' => now()->subMonths($i)->startOfMonth(),
                    'period_end' => now()->subMonths($i)->endOfMonth(),
                    'amount_total' => $amount,
                    'platform_fee' => $platformFee,
                    'platform_fee_rate' => 5.00,
                    'provider_amount' => $providerAmount,
                    'payment_method' => 'pse',
                    'payment_status' => 'paid',
                    'paid_at' => now()->subMonths($i),
                ]);
            }
        }

        $this->command->info('✅ Datos adicionales creados exitosamente.');
    }

    /**
     * Mostrar todas las credenciales del sistema
     */
    private function displayCredentials(array $accounts): void
    {
        $this->command->newLine();
        $this->command->info('🔑 === CREDENCIALES DEL ECOSISTEMA COMPLETO ===');
        $this->command->newLine();

        $this->command->info('👑 ADMINISTRADOR:');
        $this->command->info('   Email: ' . $accounts['admin']->email);
        $this->command->info('   Password: admin123');
        $this->command->newLine();

        $this->command->info('👨‍👩‍👧‍👦 PADRE DE FAMILIA:');
        $this->command->info('   Email: ' . $accounts['parent']->email);
        $this->command->info('   Password: parent123');
        $this->command->newLine();

        $this->command->info('🏫 COLEGIO CON SERVICIO:');
        $this->command->info('   📚 Cuenta Principal: ' . $accounts['school_with_service']->email);
        $this->command->info('   🚌 Cuenta Prestador: ' . $accounts['school_provider']->email);
        $this->command->info('   Password: school123 / schoolprovider123');
        $this->command->newLine();

        $this->command->info('🏫 COLEGIO SIN SERVICIO:');
        $this->command->info('   Email: ' . $accounts['school_without_service']->email);
        $this->command->info('   Password: school123');
        $this->command->newLine();

        $this->command->info('🏢 EMPRESA DE TRANSPORTE:');
        $this->command->info('   Email: ' . $accounts['transport_company']->email);
        $this->command->info('   Password: company123');
        $this->command->newLine();

        $this->command->info('🚗 CONDUCTOR INDEPENDIENTE:');
        $this->command->info('   Email: ' . $accounts['independent_driver']->email);
        $this->command->info('   Password: driver123');
        $this->command->newLine();

        $this->command->info('👨‍💼 CONDUCTOR EMPLEADO EMPRESA:');
        $this->command->info('   Email: ' . $accounts['company_driver']->email);
        $this->command->info('   Password: driver123');
        $this->command->newLine();

        $this->command->info('👨‍💼 CONDUCTOR EMPLEADO COLEGIO:');
        $this->command->info('   Email: ' . $accounts['school_driver']->email);
        $this->command->info('   Password: driver123');
        $this->command->newLine();

        $this->command->info('📊 === DATOS CREADOS ===');
        $this->command->info('   👥 9 cuentas de usuario');
        $this->command->info('   🏫 2 colegios (1 con servicio, 1 sin servicio)');
        $this->command->info('   🚛 3 proveedores de transporte');
        $this->command->info('   🚗 3 conductores (1 independiente, 2 empleados)');
        $this->command->info('   🚌 4 vehículos');
        $this->command->info('   🛣️ 4 rutas');
        $this->command->info('   👨‍🎓 3 estudiantes');
        $this->command->info('   📚 2 contratos de transporte');
        $this->command->info('   💳 2 suscripciones con pagos históricos');
        $this->command->newLine();

        $this->command->warn('⚠️  IMPORTANTE: Solo usar estas credenciales en desarrollo/testing!');
        $this->command->info('🔗 Todas las cuentas están interconectadas y relacionadas entre sí.');
    }
}
