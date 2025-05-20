<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_aliments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlimentsTable extends Migration
{
    public function up()
    {
        Schema::create('aliments', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->string('nom' ,191); // Nom de l'aliment
            $table->string('type' ,191); // Type de l'aliment (par exemple: Miette Chaire Démarrage)
            $table->decimal('prix_unitaire', 8, 2); // Prix unitaire de l'aliment
            $table->timestamps(); // Timestamps pour created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('aliments');
    }
}
