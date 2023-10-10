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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments("id");
            $table->date("ped_emissao");
            $table->integer("ped_tableNumber")->unsigned();
            $table->string("ped_customerName");
            $table->boolean("ped_delete")->default(0);
            $table->foreign("ped_tableNumber")->references("id")
                ->on("tablenumber");
            $table->integer("status_id")->unsigned();
            $table->foreign("status_id")->references("id")
                ->on("status")->default(6);
            $table->integer("user_id")->unsigned();
            $table->foreign("user_id")->references("id")
                ->on("users")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
