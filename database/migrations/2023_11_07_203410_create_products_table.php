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
        Schema::create('products', function (Blueprint $table) {
            $table->increments("id");
            $table->string("prod_name");
            $table->string("prod_desc")->nullable();
            $table->string("prod_unmed")->nullable();
            $table->integer("prod_supplierID")->unsigned();
            $table->foreign("prod_supplierID")->references("id")
                ->on("suppliers")->onDelete("cascade");
            $table->string("prod_contain")->nullable();
            $table->boolean("is_delete")->default(false);
            $table->integer('min_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
