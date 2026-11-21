<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add photocopier fields to shops table
        Schema::table('shops', function (Blueprint $table) {
            $table->boolean('has_photocopier')->default(false)->after('has_nid_print');
            $table->decimal('rate_photocopy_single', 8, 2)->default(2.00)->after('rate_bw_double');
            $table->decimal('rate_photocopy_double', 8, 2)->default(3.00)->after('rate_photocopy_single');
        });

        // 2. Change color_mode in print_jobs to string to support 'bw', 'color', 'photocopy'
        Schema::table('print_jobs', function (Blueprint $table) {
            $table->string('color_mode', 20)->default('bw')->change();
        });

        // 3. Change type in printers to string to support 'all', 'bw', 'color', 'photo', 'photocopy'
        Schema::table('printers', function (Blueprint $table) {
            $table->string('type', 20)->default('all')->change();
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['has_photocopier', 'rate_photocopy_single', 'rate_photocopy_double']);
        });

        Schema::table('print_jobs', function (Blueprint $table) {
            $table->string('color_mode', 10)->default('bw')->change();
        });

        Schema::table('printers', function (Blueprint $table) {
            $table->string('type', 10)->default('all')->change();
        });
    }
};

