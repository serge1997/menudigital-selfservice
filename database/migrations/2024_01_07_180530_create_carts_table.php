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
        Schema::create('carts', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("tableNumber")->unsigned();
            $table->bigInteger("item_id")->unsigned();
            $table->integer("quantity")->default(1);
            $table->decimal("unit_price", 6, 2)->nullable();
            $table->decimal("total", 6, 2)->nullable();
            $table->text("options")->nullable();
            $table->text("comments")->nullable();
            $table->foreign("item_id")->references("id")
                ->on("menuitems")->onDelete("cascade");
            $table->foreign("tableNumber")->references("id")
                ->on("tablenumber");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
