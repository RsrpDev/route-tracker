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
        Schema::create('student_transport_contracts', function (Blueprint $table) {
            $table->id('contract_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('provider_id');
            $table->date('contract_start_date');
            $table->date('contract_end_date')->nullable();
            $table->enum('contract_status', ['active', 'suspended', 'cancelled', 'completed'])->default('active');
            $table->unsignedBigInteger('pickup_route_id')->nullable(); // Ruta de recogida (casa → colegio)
            $table->unsignedBigInteger('dropoff_route_id')->nullable(); // Ruta de entrega (colegio → casa)
            $table->decimal('monthly_fee', 10, 2);
            $table->text('special_instructions')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('student_id')->references('student_id')->on('students')->onDelete('cascade');
            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
            $table->foreign('pickup_route_id')->references('route_id')->on('routes')->onDelete('set null');
            $table->foreign('dropoff_route_id')->references('route_id')->on('routes')->onDelete('set null');

            // Unique constraint: un estudiante solo puede tener un contrato activo con un proveedor
            $table->unique(['student_id', 'provider_id'], 'unique_student_provider');

            // Indexes
            $table->index('contract_status');
            $table->index('contract_start_date');
            $table->index('contract_end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_transport_contracts');
    }
};
