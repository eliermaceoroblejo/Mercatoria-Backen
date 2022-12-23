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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movement_type_id')->references('id')->on('movement_types');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('store_id')->references('id')->on('stores');
            $table->foreignId('client_id')->references('id')->on('clients')->nullable();
            $table->foreignId('bussiness_id')->references('id')->on('bussinesses');
            $table->double('discount')->default(0);
            $table->double('overcharge')->default(0);
            $table->double('subtotal')->default(0);
            $table->double('importing_company')->default(0);
            $table->double('financial_expenses')->default(0);
            $table->double('transportation')->default(0);
            $table->double('manipulation')->default(0);
            $table->double('total')->default(0);
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
        Schema::dropIfExists('movements');
    }
};
