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
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments("id");
            $table->integer("person_quantity");
            $table->string("date_come_in");
            $table->time("hour");
            $table->string("customer_firstName");
            $table->string("customer_lastName");
            $table->string("customer_email")->nullable();
            $table->string("customer_tel");
            $table->string("reser_canal");
            $table->text("observation");
            $table->integer("user_id")->unsigned();
            $table->foreign("user_id")->references("id")
                ->on("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
