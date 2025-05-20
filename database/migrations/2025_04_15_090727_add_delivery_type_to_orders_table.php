<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Ajouter les colonnes "delivery_type" et "scheduled_time" à la table orders.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('delivery_type', ['express', 'scheduled'])->default('scheduled')->after('status');
            $table->timestamp('scheduled_time')->nullable()->after('delivery_type');
        });
    }

    /**
     * Supprimer les colonnes ajoutées.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_type', 'scheduled_time']);
        });
    }
};
