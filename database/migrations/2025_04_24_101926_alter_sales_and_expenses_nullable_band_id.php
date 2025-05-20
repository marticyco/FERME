<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // ⚠️ Il faut d'abord supprimer la contrainte pour pouvoir modifier
            $table->dropForeign(['band_id']);
            $table->foreignId('band_id')->nullable()->change();
            $table->foreign('band_id')->references('id')->on('bands')->nullOnDelete();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['band_id']);
            $table->foreignId('band_id')->nullable()->change();
            $table->foreign('band_id')->references('id')->on('bands')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign(['band_id']);
            $table->foreignId('band_id')->nullable(false)->change();
            $table->foreign('band_id')->references('id')->on('bands')->onDelete('cascade');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['band_id']);
            $table->foreignId('band_id')->nullable(false)->change();
            $table->foreign('band_id')->references('id')->on('bands')->onDelete('cascade');
        });
    }
};
