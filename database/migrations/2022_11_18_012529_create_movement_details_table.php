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
        Schema::create('movement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movement_id')->references('id')->on('movements');
            $table->foreignId('movement_type_id')->references('id')->on('movement_types');
            $table->foreignId('product_id')->references('id')->on('products');
            // $table->integer('product_id')->unsigned();
            $table->double('amount');
            $table->decimal('price', 19, 7);
            $table->double('total');
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
        Schema::dropIfExists('movement_details');
    }
};
