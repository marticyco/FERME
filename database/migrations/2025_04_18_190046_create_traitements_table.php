<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateTraitementsTable extends Migration
{
    public function up()
    {
        Schema::create('traitements', function (Blueprint $table) {
            $table->id(); // Identifiant unique
            $table->foreignId('band_id')->constrained('bands')->onDelete('cascade'); // Lien avec la table bands
            $table->string('nom', 191); // Nom du traitement
            $table->text('description')->nullable(); // Description du traitement
            $table->date('date_planifiee'); // Date prévue pour le traitement
            $table->timestamps(); // Horodatages pour la création et la mise à jour
        });
    }

    public function down()
    {
        Schema::dropIfExists('traitements');
    }
}

