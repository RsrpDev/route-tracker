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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
            $table->unsignedBigInteger('subscription_id');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('amount_total', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->decimal('platform_fee_rate', 5, 2)->nullable();
            $table->decimal('provider_amount', 10, 2);
            $table->enum('payment_method', ['card', 'pse', 'nequi', 'daviplata']);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('payment_status');
            $table->foreign('subscription_id')->references('subscription_id')->on('subscriptions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
