<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_xx_xx_create_addresses_table.php

public function up()
{
    Schema::create('addresses', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('customer_id')->nullable(); // nullable for guest users
        $table->string('street');
        $table->string('city');
        $table->string('state');
        $table->string('country');
        $table->string('zipcode');
        $table->enum('type', ['billing', 'shipping']);
        $table->timestamps();

        // Add foreign key constraint
        $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
    });
}


    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
