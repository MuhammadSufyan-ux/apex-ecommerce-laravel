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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('city')->nullable()->after('phone');
            $table->text('address')->nullable()->after('city');
            $table->string('country')->nullable()->default('Pakistan')->after('address');
            $table->enum('status', ['active', 'blocked', 'vip'])->default('active')->after('country');
            $table->text('admin_notes')->nullable()->after('status');
            $table->timestamp('last_login_at')->nullable()->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'city', 'address', 'country', 'status', 'admin_notes', 'last_login_at']);
        });
    }
};
