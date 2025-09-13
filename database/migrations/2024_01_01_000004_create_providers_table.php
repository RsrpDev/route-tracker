<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de proveedores de transporte
     *
     * Esta migración crea la tabla 'providers' que contiene todos los proveedores de transporte del sistema.
     * La tabla maneja diferentes tipos de proveedores y sus características específicas.
     *
     * Tipos de proveedor:
     * - driver: Conductor independiente
     * - company: Empresa de transporte
     * - school_provider: Colegio que ofrece servicio de transporte
     *
     * Estados del proveedor:
     * - active: Activo
     * - inactive: Inactivo
     * - pending: Pendiente de aprobación
     * - blocked: Bloqueado
     */
    public function up(): void
    {
        Schema::create('providers', function (Blueprint $table) {
            // Identificador único del proveedor
            $table->id('provider_id');

            // Relación con la cuenta de usuario
            $table->unsignedBigInteger('account_id');

            // Tipo de proveedor
            $table->enum('provider_type', ['driver', 'company', 'school_provider']);

            // Información de contacto
            $table->string('display_name', 150);
            $table->string('contact_email', 191)->nullable();
            $table->string('contact_phone', 30)->nullable();

            // Relación con escuela (para colegios prestadores)
            $table->unsignedBigInteger('linked_school_id')->nullable();

            // Configuración de comisiones
            $table->decimal('default_commission_rate', 5, 2)->default(5.00);

            // Estado del proveedor
            $table->enum('provider_status', ['active', 'inactive', 'pending', 'blocked'])->default('pending');

            // Campos específicos para conductores independientes
            $table->string('driver_license_number', 50)->nullable();
            $table->string('driver_license_category', 10)->nullable();
            $table->date('driver_license_expiration')->nullable();
            $table->integer('driver_years_experience')->default(0);
            $table->enum('driver_status', ['pending', 'approved', 'rejected'])->default('pending');

            // Timestamps de creación y actualización
            $table->timestamps();

            // Índices y restricciones
            $table->unique('account_id');
            $table->index(['provider_type', 'driver_status']);
            $table->index('driver_license_number');

            // Claves foráneas
            $table->foreign('account_id')->references('account_id')->on('accounts')->onDelete('cascade');
            $table->foreign('linked_school_id')->references('school_id')->on('schools')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
