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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // email, sms, whatsapp
            $table->string('event'); // order_placed, order_shipped, etc.
            $table->string('recipient_type'); // admin, customer
            $table->string('recipient'); // email or phone
            $table->text('message');
            $table->string('status')->default('sent'); // sent, failed
            $table->text('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
