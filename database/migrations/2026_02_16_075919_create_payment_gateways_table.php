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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // e.g. "Stripe", "Google Pay", "JazzCash"
            $table->string('slug')->unique();    // e.g. "stripe", "google_pay"
            $table->string('icon')->nullable();  // FontAwesome icon class
            $table->string('icon_color')->default('#000000');
            $table->text('description')->nullable();
            $table->json('credentials')->nullable(); // Encrypted JSON: api_key, secret_key, merchant_id, etc.
            $table->boolean('is_active')->default(false);
            $table->boolean('is_sandbox')->default(true); // Test/Live mode
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
