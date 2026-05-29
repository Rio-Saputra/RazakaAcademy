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
        Schema::table('bank_soals', function (Blueprint $table) {
            $table->text('opsi_e')->nullable();
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->text('option_e')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_soals', function (Blueprint $table) {
            $table->dropColumn('opsi_e');
        });
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('option_e');
        });
    }
};
