<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->foreignId('print_job_id')->nullable()->constrained('print_jobs')->nullOnDelete();
            $table->decimal('amount', 8, 2);
            $table->string('payment_method', 20); // bkash, nagad, cash
            $table->string('trx_id')->nullable();
            $table->string('sender_number')->nullable();
            $table->string('status')->default('completed'); // completed, refunded
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
