<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyFeedConsumptionsTable extends Migration
{
    public function up()
    {
        Schema::create('daily_feed_consumptions', function (Blueprint $table) {
            $table->id(); // Identifiant unique de l'enregistrement de consommation
            $table->foreignId('band_id')->constrained()->onDelete('cascade'); // Identifiant de la bande associée
            $table->date('date'); // Date de l'enregistrement de la consommation
            $table->float('quantite_aliment'); // Quantité d'aliments consommée (en kilogrammes ou autres unités)
            $table->timestamps(); // Date de création et de mise à jour
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_feed_consumptions'); // Suppression de la table si elle existe
    }
}

