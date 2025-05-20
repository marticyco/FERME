<?php
// database/migrations/2024_xx_xx_create_mortalites_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMortalitesTable extends Migration
{
    public function up()
    {
        Schema::create('mortalites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained('lots'); // Référence au lot concerné
            $table->integer('nombre_morts'); // Nombre de morts dans le lot
            $table->date('date_mortalite'); // Date de la mortalité
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mortalites');
    }
}


