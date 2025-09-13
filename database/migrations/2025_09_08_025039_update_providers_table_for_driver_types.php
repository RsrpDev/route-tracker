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
        Schema::table('providers', function (Blueprint $table) {
            // Agregar campo para distinguir si es conductor independiente o afiliado
            $table->enum('driver_type', ['independent', 'affiliated'])->nullable()->after('provider_type');

            // Agregar campo para referenciar al conductor afiliado (si aplica)
            $table->unsignedBigInteger('affiliated_driver_id')->nullable()->after('driver_type');

            // Agregar índices
            $table->index(['provider_type', 'driver_type']);
            $table->index('affiliated_driver_id');

            // Agregar foreign key para conductor afiliado
            $table->foreign('affiliated_driver_id')->references('driver_id')->on('drivers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropForeign(['affiliated_driver_id']);
            $table->dropIndex(['provider_type', 'driver_type']);
            $table->dropIndex(['affiliated_driver_id']);
            $table->dropColumn(['driver_type', 'affiliated_driver_id']);
        });
    }
};
