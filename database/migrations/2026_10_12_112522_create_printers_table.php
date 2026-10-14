<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('system_printer_name')->nullable();
            $table->enum('type', ['all', 'bw', 'color', 'photo'])->default('all');
            $table->boolean('is_default')->default(false);
            $table->string('status')->default('online'); // online, offline, busy
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('printers');
    }
};
