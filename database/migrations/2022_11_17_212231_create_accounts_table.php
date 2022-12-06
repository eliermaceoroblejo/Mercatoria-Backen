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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->unsigned();
            $table->foreignId('account_nature_id')->references('id')->on('account_natures');
            $table->foreignId('currency_id')->references('id')->on('currencies');
            $table->foreignId('account_type')->references('id')->on('account_types');
            $table->foreignId('account_group_id')->references('id')->on('account_groups');
            $table->foreignId('bussiness_id')->references('id')->on('bussinesses');
            $table->boolean('locked')->default(false);
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *account_groups
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
