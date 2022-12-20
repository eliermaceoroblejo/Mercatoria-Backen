<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_products', function (Blueprint $table) {
            // $table->id();a
            $table->foreignId('store_id')->references('id')->on('stores');
            $table->foreignId('product_id')->references('id')->on('products');
            $table->double('amount');
            $table->decimal('price', 19, 7);
            $table->decimal('sale_price', 19, 7);
            $table->double('total');
            $table->foreignId('bussiness_id')->references('id')->on('bussinesses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_products');
    }
};
