<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('job_token', 32)->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('file_name');
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type', 30);
            $table->integer('total_pages')->default(1);
            $table->integer('copies')->default(1);
            $table->enum('color_mode', ['bw', 'color'])->default('bw');
            $table->boolean('is_duplex')->default(false);
            $table->string('paper_size', 20)->default('A4'); // A4, 4x6_passport
            $table->string('page_range')->nullable(); // e.g. "1-5"
            $table->decimal('total_amount', 8, 2)->default(0.00);
            $table->enum('payment_method', ['bkash', 'nagad', 'cash'])->default('bkash');
            $table->string('payment_trx_id')->nullable();
            $table->string('payment_sender_number')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->enum('print_status', ['queued', 'spooling', 'printed', 'failed', 'deleted'])->default('queued');
            $table->boolean('is_deleted_from_disk')->default(false);
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_jobs');
    }
};
