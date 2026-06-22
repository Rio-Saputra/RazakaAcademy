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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('jenis_soal')->default('TWK');
            $table->json('option_points')->nullable();
        });

        Schema::table('bank_soals', function (Blueprint $table) {
            $table->string('jenis_soal')->default('TWK');
            $table->json('option_points')->nullable();
        });

        Schema::table('tryout_answers', function (Blueprint $table) {
            $table->integer('score')->default(0);
        });

        Schema::table('results', function (Blueprint $table) {
            $table->integer('score_twk')->default(0);
            $table->integer('score_tiu')->default(0);
            $table->integer('score_tkp')->default(0);
            $table->boolean('passed_twk')->default(false);
            $table->boolean('passed_tiu')->default(false);
            $table->boolean('passed_tkp')->default(false);
            $table->boolean('is_passed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['jenis_soal', 'option_points']);
        });

        Schema::table('bank_soals', function (Blueprint $table) {
            $table->dropColumn(['jenis_soal', 'option_points']);
        });

        Schema::table('tryout_answers', function (Blueprint $table) {
            $table->dropColumn('score');
        });

        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn([
                'score_twk', 'score_tiu', 'score_tkp',
                'passed_twk', 'passed_tiu', 'passed_tkp',
                'is_passed'
            ]);
        });
    }
};
