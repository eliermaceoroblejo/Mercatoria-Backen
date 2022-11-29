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
        Schema::create('module_bussinesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->references('id')->on('modules');
            $table->foreignId('bussiness_id')->references('id')->on('bussinesses');
            $table->string('month', 2);
            $table->string('year', 4);
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
        Schema::dropIfExists('module_bussinesses');
    }
};
