<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de perfiles de padres de familia
     *
     * Esta migración crea la tabla 'parents' que contiene los perfiles específicos
     * de los padres de familia. Extiende la información básica de la cuenta con
     * datos específicos relacionados con la gestión de sus hijos.
     *
     * Relaciones:
     * - account_id: Relación con la tabla accounts (uno a uno)
     * - students: Relación con la tabla students (uno a muchos)
     */
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $table) {
            // Identificador único del perfil de padre
            $table->id('parent_id');

            // Relación con la cuenta de usuario
            $table->unsignedBigInteger('account_id');

            // Dirección del padre (información adicional)
            $table->string('address', 255)->nullable();

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
        Schema::dropIfExists('parents');
    }
};
