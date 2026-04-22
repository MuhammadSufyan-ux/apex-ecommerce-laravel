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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_coming_soon')->default(false)->after('is_new');
            $table->boolean('is_on_sale')->default(false)->after('is_coming_soon');
            $table->string('discount_badge')->nullable()->after('is_on_sale'); // e.g. "-25%", "SALE"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_coming_soon', 'is_on_sale', 'discount_badge']);
        });
    }
};
