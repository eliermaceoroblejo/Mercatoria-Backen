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
        Schema::create('entry_accounts_providers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bussiness_id')->nullable()->references('id')->on('bussinesses');
            $table->foreignId('account_id')->nullable()->references('id')->on('accounts');
            $table->string('concept');
            $table->foreignId('client_id')->nullable()->references('id')->on('clients');
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
        Schema::dropIfExists('entry_accounts_providers');
    }
};
