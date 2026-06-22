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
        Schema::table('results', function (Blueprint $table) {
            $table->foreignId('tryout_attempt_id')->nullable()->constrained()->onDelete('cascade');
        });
        Schema::table('answers', function (Blueprint $table) {
            $table->foreignId('result_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('tryout_attempt_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropForeign(['result_id']);
            $table->dropColumn('result_id');
            $table->dropForeign(['tryout_attempt_id']);
            $table->dropColumn('tryout_attempt_id');
        });
        Schema::table('results', function (Blueprint $table) {
            $table->dropForeign(['tryout_attempt_id']);
            $table->dropColumn('tryout_attempt_id');
        });
    }
};
