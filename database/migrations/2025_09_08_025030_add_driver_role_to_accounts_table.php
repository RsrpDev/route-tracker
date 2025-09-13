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
        Schema::table('accounts', function (Blueprint $table) {
            // Modificar el enum para incluir el rol 'driver'
            $table->enum('account_type', ['parent', 'provider', 'school', 'admin', 'driver'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts', function (Blueprint $table) {
            // Revertir el enum a su estado original
            $table->enum('account_type', ['parent', 'provider', 'school', 'admin'])->change();
        });
    }
};
