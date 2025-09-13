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
        Schema::create('route_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->unsignedBigInteger('route_id');
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('vehicle_id');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('assignment_status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('route_id')->references('route_id')->on('routes')->onDelete('cascade');
            $table->foreign('driver_id')->references('driver_id')->on('drivers')->onDelete('restrict');
            $table->foreign('vehicle_id')->references('vehicle_id')->on('vehicles')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_assignments');
    }
};
