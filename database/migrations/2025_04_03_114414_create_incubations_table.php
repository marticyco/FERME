<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncubationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incubations', function (Blueprint $table) {
            $table->id();
            $table->date('date_debut'); // Date de début de l'incubation
            $table->integer('nombre_oeufs_incubes'); // Nombre d'œufs incubés
            $table->decimal('temperature', 5, 2); // Température de l'incubateur
            $table->decimal('humidite', 5, 2); // Humidité de l'incubateur
            $table->date('date_mirage')->nullable(); // Date de mirage (calculée)
            $table->date('date_fin_eclosion')->nullable(); // Date de fin d'éclosion
            $table->integer('nombre_oeufs_fertiles')->nullable(); // Nombre d'œufs fertiles
            $table->integer('nombre_oeufs_fertiles_eclos')->nullable(); // Nombre d'œufs éclos
            $table->decimal('taux_fertilite', 5, 2)->nullable(); // Taux de fertilité (calculé)
            $table->decimal('taux_eclosion', 5, 2)->nullable(); // Taux d'éclosion (calculé)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incubations');
    }
} 