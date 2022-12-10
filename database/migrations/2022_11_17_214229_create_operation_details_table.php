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
        Schema::create('operation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operation_id')->references('id')->on('operations');
            $table->foreignId('account_id')->references('id')->on('accounts');
            $table->foreignId('module_id')->references('id')->on('modules');
            $table->string('reference')->nullable();
            $table->string('client')->nullable();
            $table->double('debit')->default(0);
            $table->double('credit')->default(0);
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
        Schema::dropIfExists('operation_details');
    }
};
