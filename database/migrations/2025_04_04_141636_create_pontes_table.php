<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePontesTable extends Migration
{
    public function up()
    {
        Schema::create('pontes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lot_id')->constrained('lots'); // Lot concerné
           
            $table->integer('nombre_oeufs'); // Nombre d'œufs pondus
            $table->date('date_ponte'); // Date de la ponte
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pontes');
    }
}
