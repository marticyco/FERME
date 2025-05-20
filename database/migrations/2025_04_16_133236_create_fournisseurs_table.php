<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_fournisseurs_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFournisseursTable extends Migration
{
    public function up()
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->bigIncrements('id'); // Identifiant unique du fournisseur
            $table->string('nom', 191); // Nom du fournisseur
            $table->string('contact', 191); // Contact du fournisseur (par exemple : téléphone ou email)
            $table->timestamps(); // Timestamps pour created_at et updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('fournisseurs');
    }
}
