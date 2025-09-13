<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de cuentas de usuario
     *
     * Esta migración crea la tabla 'accounts' que contiene todos los usuarios del sistema.
     * La tabla maneja diferentes tipos de cuentas y su estado de verificación.
     *
     * Tipos de cuenta:
     * - parent: Padre de familia
     * - provider: Proveedor de transporte
     * - school: Colegio
     * - admin: Administrador del sistema
     *
     * Estados de verificación:
     * - pending: Pendiente de verificación
     * - verified: Verificado
     * - rejected: Rechazado
     */
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            // Identificador único de la cuenta
            $table->id('account_id');

            // Tipo de cuenta del usuario
            $table->enum('account_type', ['parent', 'provider', 'school', 'admin']);

            // Estado de verificación de la cuenta
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();

            // Información personal del usuario
            $table->string('full_name', 150);
            $table->string('email', 191)->unique()->nullable();
            $table->string('password_hash', 255)->nullable();
            $table->string('phone_number', 30)->nullable();
            $table->string('id_number', 50)->unique()->nullable();

            // Estado de la cuenta
            $table->enum('account_status', ['active', 'inactive', 'pending', 'blocked'])->default('active');

            // Timestamps de creación y actualización
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index('account_type');

            // Clave foránea para el administrador que verificó la cuenta
            $table->foreign('verified_by')->references('account_id')->on('accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
