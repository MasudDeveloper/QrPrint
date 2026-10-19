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
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->string('cancel_reason')->nullable()->after('print_status');
            $table->timestamp('cancelled_at')->nullable()->after('cancel_reason');
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE print_jobs MODIFY COLUMN print_status ENUM('queued', 'spooling', 'printed', 'failed', 'deleted', 'cancelled') NOT NULL DEFAULT 'queued'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->dropColumn(['cancel_reason', 'cancelled_at']);
        });

        \Illuminate\Support\Facades\DB::statement("ALTER TABLE print_jobs MODIFY COLUMN print_status ENUM('queued', 'spooling', 'printed', 'failed', 'deleted') NOT NULL DEFAULT 'queued'");
    }
};
