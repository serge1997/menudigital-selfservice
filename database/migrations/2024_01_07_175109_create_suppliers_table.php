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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->increments("id");
            $table->string("sup_name");
            $table->string("sup_email")->nullable();
            $table->string("sup_tel");
            $table->string("sup_city")->nullable();
            $table->string("sup_neighborhood")->nullable();
            $table->string("sup_personID")->nullable();
            $table->boolean("is_delete")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
