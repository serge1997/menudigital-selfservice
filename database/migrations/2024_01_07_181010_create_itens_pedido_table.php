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
        Schema::create('itens_pedido', function (Blueprint $table) {
            $table->date("item_emissao");
            $table->integer("item_pedido")->unsigned();
            $table->bigInteger("item_id")->unsigned();
            $table->integer("item_quantidade");
            $table->decimal("item_price", 10, 2);
            $table->decimal("item_total", 10, 2);
            $table->string("item_option")->nullable();
            $table->string("item_comments")->nullable();
            $table->boolean("item_delete")->default(0);
            $table->foreign("item_id")->references("id")
                ->on("menuitems")->onDelete("cascade");
            $table->foreign("item_pedido")->references("id")
                ->on("pedidos")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itens_pedido');
    }
};
