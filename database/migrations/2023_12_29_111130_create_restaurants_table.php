<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rest_name');
            $table->string('rest_email');
            $table->string('rest_cnpj');
            $table->string('res_city');
            $table->string('res_neighborhood');
            $table->string('rest_cep')->nullable();
            $table->string('rest_streetName');
            $table->string('rest_StreetNumber');
            $table->string('res_logo');
            $table->time('res_open');
            $table->time('res_close');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
