<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear tabla de estudiantes
     *
     * Esta migración crea la tabla 'students' que contiene todos los estudiantes
     * registrados en el sistema. Los estudiantes están asociados a un padre y
     * pueden estar matriculados en una escuela y tener contratos de transporte.
     *
     * Estados del estudiante:
     * - active: Activo
     * - inactive: Inactivo
     * - graduated: Graduado
     *
     * Turnos disponibles:
     * - morning: Mañana
     * - afternoon: Tarde
     * - mixed: Mixto
     *
     * Relaciones:
     * - parent_id: Relación con la tabla parents (muchos a uno)
     * - school_id: Relación con la tabla schools (muchos a uno, opcional)
     * - transportContracts: Relación con la tabla student_transport_contracts (uno a muchos)
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            // Identificador único del estudiante
            $table->id('student_id');

            // Relación con el padre
            $table->unsignedBigInteger('parent_id');

            // Información personal del estudiante
            $table->string('given_name', 80);
            $table->string('family_name', 80);
            $table->string('identity_number', 50);
            $table->date('birth_date')->nullable();

            // Información académica
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('grade', 50)->nullable();
            $table->enum('shift', ['morning', 'afternoon', 'mixed'])->default('mixed');

            // Información de contacto
            $table->string('address', 255)->nullable();
            $table->string('phone_number', 30)->nullable();

            // Estado del estudiante
            $table->enum('status', ['active', 'inactive', 'graduated'])->default('active');
            $table->boolean('has_transport')->default(false);

            // Timestamps de creación y actualización
            $table->timestamps();

            // Índices y restricciones
            $table->unique('identity_number');
            $table->index('parent_id');

            // Claves foráneas
            $table->foreign('parent_id')->references('parent_id')->on('parents')->onDelete('cascade');
            $table->foreign('school_id')->references('school_id')->on('schools')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
