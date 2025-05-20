<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChickenTypesToSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Ajout d'une colonne JSON pour stocker les types de poulets associés à l'abonnement
            $table->json('chicken_types')->nullable(); // Cette colonne pourra contenir un tableau JSON
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Suppression de la colonne 'chicken_types' si la migration est rollbackée
            $table->dropColumn('chicken_types');
        });
    }
}
