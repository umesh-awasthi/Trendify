<?php



// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration {
//     public function up() {
//         Schema::create('carts', function (Blueprint $table) {
//             $table->id();
//             $table->foreignId('customer_id')->constrained()->onDelete('cascade');
//             $table->foreignId('product_id')->constrained()->onDelete('cascade');
//             $table->integer('quantity')->default(1);
//             $table->decimal('total_price', 10, 2);
//             $table->timestamps();
//         });
//     }

//     public function down() {
//         Schema::dropIfExists('carts');
//     }
// };
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
{
    Schema::create('carts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('customer_id')->nullable();  // Nullable customer_id for guests
        $table->string('gcart_id')->nullable();  // Add a field for guest carts
        $table->unsignedBigInteger('product_id');
        $table->integer('quantity');
        $table->decimal('total_price', 10, 2);
        $table->timestamps();

        $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        $table->foreign('product_id')->references('id')->on('products');
    });
}


    public function down() {
        Schema::dropIfExists('carts');
    }
};
