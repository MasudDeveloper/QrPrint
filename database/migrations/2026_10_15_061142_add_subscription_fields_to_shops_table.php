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
        Schema::table('shops', function (Blueprint $table) {
            $table->string('subscription_plan')->default('free_trial')->after('is_active');
            $table->timestamp('subscription_expires_at')->nullable()->after('subscription_plan');
            $table->boolean('is_lifetime')->default(false)->after('subscription_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['subscription_plan', 'subscription_expires_at', 'is_lifetime']);
        });
    }
};
