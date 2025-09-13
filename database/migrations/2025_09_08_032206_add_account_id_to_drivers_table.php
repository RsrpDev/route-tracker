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
        Schema::table('drivers', function (Blueprint $table) {
            // Agregar campo para vincular con la cuenta del conductor empleado
            $table->unsignedBigInteger('account_id')->nullable()->after('driver_id');

            // Agregar índices
            $table->index('account_id');
            $table->unique('account_id');

            // Agregar foreign key
            $table->foreign('account_id')->references('account_id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropIndex(['account_id']);
            $table->dropUnique(['account_id']);
            $table->dropColumn('account_id');
        });
    }
};
