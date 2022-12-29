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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('code')->unsigned();
            $table->string('name');
            $table->string('slug');
            $table->decimal('sale_price', 19, 7)->default(0);
            $table->foreignId('unit_id')->references('id')->on('units');
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
        Schema::dropIfExists('products');
    }
};
