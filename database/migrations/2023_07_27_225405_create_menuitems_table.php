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
        Schema::create('menuitems', function (Blueprint $table) {
            $table->bigIncrements("id")->startValue(65)->increment(2);
            $table->string("item_name");
            $table->decimal("item_price", 6, 2);
            $table->text("item_image")->nullable();
            $table->boolean("item_status");
            $table->string("item_desc");
            $table->integer("type_id")->unsigned();
            $table->foreign("type_id")->references("id_type")
                ->on("menu_mealtype")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menuitems');
    }
};
