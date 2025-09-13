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
        Schema::create('routes', function (Blueprint $table) {
            $table->id('route_id');
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('school_id')->nullable();
            $table->string('route_name', 120);
            $table->string('origin_address', 255);
            $table->string('destination_address', 255);
            $table->integer('capacity');
            $table->decimal('monthly_price', 10, 2);
            $table->time('pickup_time')->nullable();
            $table->time('dropoff_time')->nullable();
            $table->json('schedule_days')->nullable();
            $table->text('route_description')->nullable();
            $table->integer('estimated_duration_minutes')->nullable();
            $table->boolean('active_flag')->default(true);
            $table->timestamps();

            $table->index(['school_id', 'provider_id']);
            $table->index(['school_id', 'active_flag']);
            $table->foreign('provider_id')->references('provider_id')->on('providers')->onDelete('cascade');
            $table->foreign('school_id')->references('school_id')->on('schools')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
