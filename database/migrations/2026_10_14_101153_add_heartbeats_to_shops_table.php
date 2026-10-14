<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->timestamp('last_agent_heartbeat_at')->nullable()->after('is_active');
            $table->timestamp('last_app_heartbeat_at')->nullable()->after('last_agent_heartbeat_at');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['last_agent_heartbeat_at', 'last_app_heartbeat_at']);
        });
    }
};
