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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id('vehicle_id');
            $table->unsignedBigInteger('provider_id');
            $table->string('plate', 20);
            $table->string('brand', 50)->nullable();
            $table->integer('model_year')->nullable();
            $table->string('serial_number', 50)->nullable();
            $table->string('engine_number', 50)->nullable();
            $table->string('chassis_number', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->string('fuel_type', 20)->default('gasoline');
            $table->integer('cylinder_capacity')->nullable();
            $table->string('vehicle_class', 30)->nullable();
            $table->string('service_type', 30)->default('private');
            $table->integer('capacity');
            $table->date('soat_expiration');
            $table->string('soat_number', 50)->nullable();
            $table->date('insurance_expiration');
            $table->string('insurance_company', 100)->nullable();
            $table->string('insurance_policy_number', 50)->nullable();
            $table->date('technical_inspection_expiration');
            $table->date('revision_expiration')->nullable();
            $table->integer('odometer_reading')->nullable();
            $table->date('last_maintenance_date')->nullable();
            $table->date('next_maintenance_date')->nullable();
            $table->enum('vehicle_status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique('plate');
            $table->index(['plate', 'serial_number']);
            $table->index(['vehicle_class', 'service_type']);
            $table->index(['soat_expiration', 'technical_inspection_expiration']);
            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
