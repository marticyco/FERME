<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNombreMalesMortsAndNombreFemellesMortsToMortalitesTable extends Migration
{
    public function up()
    {
        Schema::table('mortalites', function (Blueprint $table) {
            // Ajouter les colonnes pour les morts par sexe
            $table->integer('nombre_males_morts')->default(0); // Nombre de mâles morts
            $table->integer('nombre_femelles_morts')->default(0); // Nombre de femelles mortes
        });
    }

    public function down()
    {
        Schema::table('mortalites', function (Blueprint $table) {
            // Supprimer les colonnes lors de l'annulation de la migration
            $table->dropColumn(['nombre_males_morts', 'nombre_femelles_morts']);
        });
    }
}
