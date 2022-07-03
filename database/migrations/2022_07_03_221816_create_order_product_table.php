<?php

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class);
            $table->foreignIdFor(Product::class);
            $table->integer('quantity');
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_product');
    }
};
