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
        Schema::create('technicalfiches', function (Blueprint $table) {
            $table->bigInteger("itemID")->unsigned();
            $table->integer("productID")->unsigned();
            $table->decimal("quantity", 8, 2);
            $table->decimal('cost', 8, 2);
            $table->foreign("itemID")->references("id")
                ->on("menuitems")->onDelete("cascade");
            $table->foreign("productID")->references("id")
                ->on("products")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technicalfiches');
    }
};
