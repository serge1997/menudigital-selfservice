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
        Schema::table('technicalfiches', function (Blueprint $table) {
            $table->decimal('loss_margin', 8, 2)->nullable();
            $table->decimal('fix_margin', 8, 2)->bullable();
            $table->decimal('variable_margin', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('technicalfiches', function (Blueprint $table) {
            $table->dropColumn('loss_margin');
            $table->dropColumn('fix_margin');
            $table->dropColumn('variable_margin');
        });
    }
};
