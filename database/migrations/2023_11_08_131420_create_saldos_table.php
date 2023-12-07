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
        Schema::create('saldos', function (Blueprint $table) {
            $table->integer("productID")->unsigned();
            $table->foreign("productID")->references("id")
                ->on("products")->onDelete("cascade");
            $table->integer("saldoInicial");
            $table->integer("saldoFinal");
            $table->date("emissao");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saldos');
    }
};
