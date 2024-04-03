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
        Schema::create('purchase_requisitions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned();
            $table->integer('department_id')->unsigned();
            $table->date('delivery_date');
            $table->date('response_date')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users');
            $table->foreign('status_id')->references('id')
                ->on('requisitions_status');
            $table->foreign('department_id')->references('id')
                ->on('departments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requisitions');
    }
};
