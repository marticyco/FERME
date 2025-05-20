<?php

// database/migrations/2024_xx_xx_create_lots_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLotsTable extends Migration
{
    public function up()
    {
        Schema::create('lots', function (Blueprint $table) {
            $table->id();
            $table->string('nom' ,191); // Nom ou identifiant du lot
            $table->integer('nombre_males'); // Nombre de mâles
            $table->integer('nombre_femelles'); // Nombre de femelles
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lots');
    }
}

