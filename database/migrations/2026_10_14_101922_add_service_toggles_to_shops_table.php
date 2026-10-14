<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->boolean('has_color_print')->default(true)->after('rate_photo_passport');
            $table->boolean('has_duplex_print')->default(true)->after('has_color_print');
            $table->boolean('has_passport_photo')->default(true)->after('has_duplex_print');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['has_color_print', 'has_duplex_print', 'has_passport_photo']);
        });
    }
};
