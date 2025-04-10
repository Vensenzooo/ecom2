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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('address_confirmed')->default(false);
            $table->text('refund_reason')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->timestamp('refund_requested_at')->nullable();
        });

        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('street');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['address_confirmed', 'refund_reason', 'refunded_at', 'refund_requested_at']);
        });

        Schema::dropIfExists('shipping_addresses');
    }
};
