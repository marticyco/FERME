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
        Schema::table('orders', function (Blueprint $table) {
            // On vérifie que la colonne existe avant de tenter de la supprimer
            if (Schema::hasColumn('orders', 'chicken_type_id')) {
                $table->dropForeign(['chicken_type_id']);
                $table->dropColumn('chicken_type_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('chicken_type_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
