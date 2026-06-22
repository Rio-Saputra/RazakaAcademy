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
            $table->text('opsi_a')->change();
            $table->text('opsi_b')->change();
            $table->text('opsi_c')->change();
            $table->text('opsi_d')->change();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->text('option_a')->change();
            $table->text('option_b')->change();
            $table->text('option_c')->change();
            $table->text('option_d')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_soals', function (Blueprint $table) {
            $table->string('opsi_a')->change();
            $table->string('opsi_b')->change();
            $table->string('opsi_c')->change();
            $table->string('opsi_d')->change();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->string('option_a')->change();
            $table->string('option_b')->change();
            $table->string('option_c')->change();
            $table->string('option_d')->change();
        });
    }
};
