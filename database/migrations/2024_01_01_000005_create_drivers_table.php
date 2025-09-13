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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id('driver_id');
            $table->unsignedBigInteger('provider_id');
            $table->string('given_name', 80);
            $table->string('family_name', 80);
            $table->string('id_number', 50);
            $table->string('document_type', 10)->default('CC');
            $table->string('birth_city', 100)->nullable();
            $table->string('birth_department', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->string('phone_number', 30)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('emergency_contact_name', 150)->nullable();
            $table->string('emergency_contact_phone', 30)->nullable();
            $table->string('emergency_contact_relationship', 50)->nullable();
            $table->string('license_number', 50);
            $table->string('license_category', 10)->nullable();
            $table->date('license_expiration');
            $table->string('license_issuing_authority', 100)->nullable();
            $table->string('license_issuing_city', 100)->nullable();
            $table->date('license_issue_date')->nullable();
            $table->boolean('has_medical_certificate')->default(false);
            $table->date('medical_certificate_expiration')->nullable();
            $table->boolean('has_psychological_certificate')->default(false);
            $table->date('psychological_certificate_expiration')->nullable();
            $table->integer('years_experience')->default(0);
            $table->string('employment_status', 30)->default('active');
            $table->date('hire_date')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('monthly_salary', 10, 2)->nullable();
            $table->enum('driver_status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();

            $table->unique('id_number');
            $table->index(['document_type', 'id_number']);
            $table->index(['license_expiration', 'medical_certificate_expiration']);
            $table->index(['employment_status', 'driver_status']);
            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
