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
        Schema::create('store_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bussiness_id')->references('id')->on('bussinesses');
            $table->foreignId('store_id')->references('id')->on('stores');
            $table->foreignId('account_id')->references('id')->on('accounts');
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
        Schema::dropIfExists('store_accounts');
    }
};
