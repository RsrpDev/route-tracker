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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id('subscription_id');
            $table->unsignedBigInteger('contract_id');
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'semiannual', 'annual'])->default('monthly');
            $table->enum('payment_plan_type', ['monthly', 'quarterly', 'annual', 'postpaid'])->default('monthly');
            $table->string('payment_plan_name', 100)->nullable();
            $table->text('payment_plan_description')->nullable();
            $table->decimal('discount_rate', 5, 2)->default(0.00);
            $table->boolean('auto_renewal')->default(true);
            $table->date('plan_start_date')->nullable();
            $table->date('plan_end_date')->nullable();
            $table->decimal('price_snapshot', 10, 2);
            $table->decimal('platform_fee_rate', 5, 2)->default(5.00);
            $table->date('next_billing_date');
            $table->enum('subscription_status', ['active', 'paused', 'cancelled', 'expired'])->default('active');
            $table->string('stripe_subscription_id')->nullable();
            $table->string('stripe_customer_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->enum('payment_method', ['stripe', 'pse', 'bank_transfer', 'cash'])->default('pse');
            $table->json('payment_metadata')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['payment_plan_type', 'is_active']);
            $table->index('stripe_subscription_id');
            $table->index('stripe_customer_id');
            $table->index('next_billing_date');
            $table->foreign('contract_id')->references('contract_id')->on('student_transport_contracts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
