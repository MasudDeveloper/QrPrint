<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('bkash_number')->nullable();
            $table->string('nagad_number')->nullable();
            $table->decimal('rate_bw_single', 8, 2)->default(3.00);
            $table->decimal('rate_bw_double', 8, 2)->default(5.00);
            $table->decimal('rate_color_single', 8, 2)->default(10.00);
            $table->decimal('rate_color_double', 8, 2)->default(18.00);
            $table->decimal('rate_photo_passport', 8, 2)->default(30.00);
            $table->string('api_token', 64)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
