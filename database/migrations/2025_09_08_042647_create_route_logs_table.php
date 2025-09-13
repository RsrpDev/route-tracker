<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('route_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('vehicle_id');

            // Información de la actividad
            $table->enum('activity_type', ['start', 'pickup', 'dropoff', 'end', 'break', 'incident']);
            $table->string('activity_description', 500)->nullable();

            // Ubicación (preparado para GPS futuro)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('department', 100)->nullable();

            // Timestamps de la actividad
            $table->timestamp('scheduled_time')->nullable(); // Hora programada
            $table->timestamp('actual_time')->nullable(); // Hora real de la actividad

            // Estado y observaciones
            $table->enum('status', ['on_time', 'early', 'late', 'delayed', 'cancelled'])->default('on_time');
            $table->integer('delay_minutes')->default(0);
            $table->text('observations')->nullable();
            $table->text('incident_details')->nullable();

            // Información adicional
            $table->integer('students_picked_up')->default(0);
            $table->integer('students_dropped_off')->default(0);
            $table->decimal('fuel_level', 5, 2)->nullable(); // Nivel de combustible
            $table->integer('odometer_reading')->nullable(); // Lectura del odómetro

            // Metadatos
            $table->string('weather_conditions', 100)->nullable();
            $table->string('traffic_conditions', 100)->nullable();
            $table->boolean('gps_enabled')->default(false); // Para futuras versiones con GPS

            $table->timestamps();

            // Índices y foreign keys
            $table->index(['route_id', 'actual_time']);
            $table->index(['driver_id', 'actual_time']);
            $table->index(['vehicle_id', 'actual_time']);
            $table->index(['activity_type', 'actual_time']);
            $table->index('scheduled_time');

            $table->foreign('route_id')->references('route_id')->on('routes')->onDelete('cascade');
            $table->foreign('driver_id')->references('driver_id')->on('drivers')->onDelete('cascade');
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_logs');
    }
};
