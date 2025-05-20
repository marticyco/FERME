<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropChickenTypeIdFromSubscriptionsTable extends Migration
{
    /**
     * Exécuter la migration.
     *
     * @return void
     */
    public function up()
    {
        // Suppression de la colonne 'chicken_type_id' de la table 'subscriptions'
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('chicken_type_id');
        });
    }

    /**
     * Annuler la migration.
     *
     * @return void
     */
    public function down()
    {
        // Si vous souhaitez pouvoir revenir en arrière, vous pouvez recréer la colonne 'chicken_type_id'
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('chicken_type_id');
        });
    }
}
