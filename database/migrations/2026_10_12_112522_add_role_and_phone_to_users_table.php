<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('shop_owner')->after('email'); // super_admin, shop_owner, customer
            $table->string('phone')->nullable()->after('role');
            $table->string('status')->default('active')->after('phone'); // active, suspended
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'status']);
        });
    }
};
