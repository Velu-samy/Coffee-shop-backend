<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Receiver Info
            $table->string('receiver_name');
            $table->string('mobile', 15)->nullable(); // Optional mobile
            $table->text('address');
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('landmark')->nullable();
            $table->text('delivery_instructions')->nullable();

            // Product Info
            $table->string('product_name');
            $table->unsignedInteger('quantity');
            $table->decimal('price', 8, 2);
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};