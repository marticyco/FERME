<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateBandsTable extends Migration
{
    public function up()
    {
        Schema::create('bands', function (Blueprint $table) {
            $table->id();
            $table->string('code', 191)->unique(); // Limiter la longueur de l'index à 191 caractères
            $table->string('type'); // Type de la bande
            $table->integer('compte_initial'); // Compte initial
            $table->integer('compte_actuel'); // Compte actuel
            $table->date('date_debut'); // Date de début
            $table->date('date_fin'); // Date de fin
            $table->integer('mortalite_totale')->default(0); // Total de mortalité
            $table->timestamps(); // Horodatage créé et mis à jour
        });
    }

    public function down()
    {
        Schema::dropIfExists('bands');
    }
}