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
            $table->text('care_instructions')->nullable();
            $table->string('duppata_image')->nullable();
            $table->string('shalwar_image')->nullable();
            $table->string('bazoo_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['care_instructions', 'duppata_image', 'shalwar_image', 'bazoo_image']);
        });
    }
};
