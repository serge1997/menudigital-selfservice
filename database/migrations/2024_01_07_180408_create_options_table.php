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
        Schema::create('options', function (Blueprint $table) {
            $table->increments("id");
            $table->string("option_name");
            $table->string("options_desc");
            $table->boolean("option_status");
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
        Schema::dropIfExists('options');
    }
};
