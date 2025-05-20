<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_sorties_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSortiesTable extends Migration
{
    public function up()
    {
        Schema::create('sorties', function (Blueprint $table) {
            $table->id(); // Identifiant unique de la sortie
            $table->foreignId('aliment_id')->constrained('aliments'); // Clé étrangère vers la table aliments
            $table->decimal('quantite', 8, 2); // Quantité d'aliment sortie du stock
            $table->timestamps(); // Timestamps pour created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('sorties');
    }
}
