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
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->integer("productID")->unsigned();
            $table->foreign("productID")->references("id")
                ->on("products")->onDelete("cascade");
            $table->integer('requisition_id')->unsigned();
            $table->foreign('requisition_id')->references('id')
                ->on('purchase_requisitions')->onDelete('cascade');
            $table->integer("quantity");
            $table->decimal("unitCost", 10, 2);
            $table->decimal('totalCost', 10, 2);
            $table->integer("supplierID")->unsigned();
            $table->foreign("supplierID")->references("id")
                ->on("suppliers")->onDelete("cascade");
            $table->boolean("is_delete")->default(false);
            $table->date("emissao");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_entries');
    }
};
