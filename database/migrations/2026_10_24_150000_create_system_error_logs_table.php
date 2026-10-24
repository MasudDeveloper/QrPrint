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
        Schema::create('system_error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('level', 20)->default('error'); // emergency, critical, error, warning, info
            $table->string('error_type', 120)->index(); // Exception class, SpoolerCrash, WebhookError, etc.
            $table->text('message');
            $table->string('file')->nullable();
            $table->integer('line')->nullable();
            $table->text('url')->nullable();
            $table->string('method', 10)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('payload')->nullable(); // JSON formatted sanitized request inputs
            $table->longText('stack_trace')->nullable();
            
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('status', 20)->default('unresolved')->index(); // unresolved, resolved, ignored
            $table->string('error_hash', 64)->nullable()->index(); // md5 of error_type+file+line+message
            $table->integer('occurrences_count')->default(1);
            $table->timestamp('last_seen_at')->nullable()->index();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_error_logs');
    }
};
