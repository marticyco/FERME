<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_entrees_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntreesTable extends Migration
{
    public function up()
    {
        Schema::create('entrees', function (Blueprint $table) {
            $table->id(); // Identifiant unique de l'entrée
            $table->foreignId('aliment_id')->constrained('aliments'); // Clé étrangère vers la table aliments
            $table->foreignId('fournisseur_id')->constrained('fournisseurs'); // Clé étrangère vers la table fournisseurs
            $table->decimal('quantite', 8, 2); // Quantité d'aliment ajoutée au stock
            $table->timestamps(); // Timestamps pour created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('entrees');
    }
}
