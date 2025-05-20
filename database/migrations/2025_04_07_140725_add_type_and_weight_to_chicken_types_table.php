<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeAndWeightToChickenTypesTable extends Migration
{
    /**
     * Exécute la migration.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chicken_types', function (Blueprint $table) {
            // Ajout de la colonne 'type' avec une contrainte enum pour restreindre les valeurs
            $table->enum('type', ['frais', 'roti', 'fume'])->nullable();

            // Ajout de la colonne 'weight' avec un type decimal (3 chiffres au total, 1 après la virgule)
            $table->decimal('weight', 3, 1)->nullable(); // Décimal pour les poids comme 0.8, 1.0, etc.
        });
    }

    /**
     * Annule la migration.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chicken_types', function (Blueprint $table) {
            // Supprimer les colonnes 'type' et 'weight' si la migration est annulée
            $table->dropColumn(['type', 'weight']);
        });
    }
}
