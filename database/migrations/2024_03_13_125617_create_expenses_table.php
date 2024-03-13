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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned();
            $table->bigInteger('item_id')->unsigned()->nullable();
            $table->decimal('quantity', 8, 2);
            $table->integer('user_id')->unsigned();
            $table->text('observation', 255);
            $table->foreign('product_id')->references('id')
                ->on('products');
            $table->foreign('item_id')->references('id')
                ->on('menuitems');
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense');
    }
};
