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
            $table->boolean('has_nid_print')->default(true)->after('has_passport_photo');
            $table->decimal('rate_nid_bw', 8, 2)->default(10.00)->after('has_nid_print');
            $table->decimal('rate_nid_color', 8, 2)->default(20.00)->after('rate_nid_bw');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['has_nid_print', 'rate_nid_bw', 'rate_nid_color']);
        });
    }
};
