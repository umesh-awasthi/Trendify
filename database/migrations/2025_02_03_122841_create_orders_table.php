<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();  // Make the customer_id nullable for guest orders
            $table->decimal('total', 10, 2);
            $table->string('status')->default('pending');
            $table->timestamps();
    
            // Adding foreign key constraint for customer_id
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');  // Set to null if customer is deleted
        });
    }
    
  

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
