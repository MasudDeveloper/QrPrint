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
            $table->boolean('is_office_mode')->default(false)->after('has_nid_print');
        });

        Schema::table('print_jobs', function (Blueprint $table) {
            $table->string('pickup_reference')->nullable()->after('customer_phone');
            $table->boolean('is_office_job')->default(false)->after('is_deleted_from_disk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('is_office_mode');
        });

        Schema::table('print_jobs', function (Blueprint $table) {
            $table->dropColumn(['pickup_reference', 'is_office_job']);
        });
    }
};
