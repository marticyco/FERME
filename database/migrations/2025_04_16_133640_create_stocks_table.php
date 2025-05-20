<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id(); // Identifiant unique du stock
            $table->unsignedBigInteger('aliment_id'); // Colonne pour la clé étrangère
            $table->decimal('quantite', 8, 2); // Quantité disponible dans le stock
            $table->timestamps(); // Timestamps pour created_at et updated_at
        });

        // Ajout de la contrainte de clé étrangère après la création de la table
        Schema::table('stocks', function (Blueprint $table) {
            $table->foreign('aliment_id')->references('id')->on('aliments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropForeign(['aliment_id']); // Suppression de la contrainte de clé étrangère
        });

        Schema::dropIfExists('stocks');
    }
}
