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
        Schema::create('shop_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('plan'); // monthly_starter, yearly_pro, lifetime, custom_extension
            $table->string('plan_name'); // 'মান্থলি স্টার্টার', 'ইয়ারলি প্রো', 'লাইফটাইম'
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('payment_method')->default('bkash'); // bkash, nagad, manual_admin, bank
            $table->string('transaction_id')->nullable();
            $table->string('sender_number')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_subscriptions');
    }
};
