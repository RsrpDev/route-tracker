<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Iniciando seeders de la base de datos...');

        // 1. Crear ecosistema completo de cuentas relacionadas
        $this->command->info('🌐 Creando ecosistema completo de cuentas relacionadas...');
        $this->call([
            UnifiedRoleSeeder::class,     // Ecosistema completo con todas las cuentas relacionadas
        ]);

        // 2. Crear datos adicionales de ejemplo (opcional)
        $this->command->info('📊 Creando datos adicionales de ejemplo...');
        $this->command->info('   ⚠️  Saltando seeders adicionales para evitar duplicación...');
        $this->command->info('   📝 Los datos adicionales se crean automáticamente en UnifiedRoleSeeder');

        // 3. Crear logs de rutas de ejemplo
        $this->command->info('📊 Creando logs de rutas de ejemplo...');
        $this->call([
            RouteLogSeeder::class,        // Logs de rutas con datos históricos
        ]);

        $this->command->info('✅ Seeders completados exitosamente!');
        $this->command->newLine();
        $this->command->info('🎯 === CREDENCIALES DEL ECOSISTEMA COMPLETO ===');
        $this->command->newLine();

        // Mostrar credenciales del ecosistema unificado
        $this->command->info('👑 ADMINISTRADOR:');
        $this->command->info('   Email: admin@routetracker.com');
        $this->command->info('   Password: admin123');
        $this->command->newLine();

        $this->command->info('👨‍👩‍👧‍👦 PADRE DE FAMILIA:');
        $this->command->info('   Email: maria.gonzalez@familia.com');
        $this->command->info('   Password: parent123');
        $this->command->newLine();

        $this->command->info('🏫 COLEGIO CON SERVICIO:');
        $this->command->info('   📚 Cuenta Principal: colegio.sanjose@educacion.com');
        $this->command->info('   🚌 Cuenta Prestador: transporte.sanjose@educacion.com');
        $this->command->info('   Password: school123 / schoolprovider123');
        $this->command->newLine();

        $this->command->info('🏫 COLEGIO SIN SERVICIO:');
        $this->command->info('   Email: instituto.losandes@educacion.com');
        $this->command->info('   Password: school123');
        $this->command->newLine();

        $this->command->info('🏢 EMPRESA DE TRANSPORTE:');
        $this->command->info('   Email: empresa.abc@transporte.com');
        $this->command->info('   Password: company123');
        $this->command->newLine();

        $this->command->info('🚗 CONDUCTOR INDEPENDIENTE:');
        $this->command->info('   Email: carlos.rodriguez@conductor.com');
        $this->command->info('   Password: driver123');
        $this->command->newLine();

        $this->command->info('👨‍💼 CONDUCTOR EMPLEADO EMPRESA:');
        $this->command->info('   Email: roberto.hernandez@empresa.com');
        $this->command->info('   Password: driver123');
        $this->command->newLine();

        $this->command->info('👨‍💼 CONDUCTOR EMPLEADO COLEGIO:');
        $this->command->info('   Email: fernando.vargas@colegio.com');
        $this->command->info('   Password: driver123');
        $this->command->newLine();

        $this->command->info('📊 === DATOS CREADOS ===');
        $this->command->info('   👥 9 cuentas de usuario interconectadas');
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
        $this->command->newLine();
        $this->command->info('💡 Para usar seeders individuales, comenta UnifiedRoleSeeder y descomenta los seeders específicos.');
    }
}
