<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyMortalitiesTable extends Migration
{
    public function up()
    {
        Schema::create('daily_mortalities', function (Blueprint $table) {
            $table->id(); // Identifiant unique de l'enregistrement de mortalité
            $table->foreignId('band_id')->constrained()->onDelete('cascade'); // Identifiant de la bande associée
            $table->date('date'); // Date de l'enregistrement de la mortalité
            $table->integer('mortalite_journaliere'); // Mortalités journalières (nombre de poulets morts ce jour-là)
            $table->timestamps(); // Date de création et de mise à jour
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_mortalities'); // Suppression de la table si elle existe
    }
}