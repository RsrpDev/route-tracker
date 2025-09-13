<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de colegios
     *
     * Esta migración crea la tabla 'schools' que contiene todos los colegios
     * registrados en el sistema. Los colegios pueden ofrecer o no servicio de
     * transporte y pueden tener proveedores vinculados para gestionar sus rutas.
     *
     * Tipos de colegio:
     * - Con servicio de transporte: has_transport_service = true
     * - Sin servicio de transporte: has_transport_service = false
     *
     * Relaciones:
     * - account_id: Relación con la tabla accounts (uno a uno)
     * - students: Relación con la tabla students (uno a muchos)
     * - routes: Relación con la tabla routes (uno a muchos)
     * - linkedProvider: Relación con la tabla providers (uno a uno)
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            // Identificador único del colegio
            $table->id('school_id');

            // Relación con la cuenta de usuario
            $table->unsignedBigInteger('account_id');

            // Información legal del colegio
            $table->string('legal_name', 150);
            $table->string('rector_name', 120)->nullable();
            $table->string('nit', 50)->nullable();

            // Información de contacto
            $table->string('phone_number', 30)->nullable();
            $table->string('address', 255)->nullable();

            // Indicador de servicio de transporte
            $table->boolean('has_transport_service')->default(false);

            // Timestamps de creación y actualización
            $table->timestamps();

            // Restricciones y claves foráneas
            $table->unique('account_id');
            $table->foreign('account_id')->references('account_id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
