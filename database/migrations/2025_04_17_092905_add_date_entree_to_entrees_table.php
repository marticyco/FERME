<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateEntreeToEntreesTable extends Migration
{
    public function up()
    {
        Schema::table('entrees', function (Blueprint $table) {
            $table->date('date_entree')->nullable(); // Ajoute une colonne 'date_entree' de type 'date'
        });
    }

    public function down()
    {
        Schema::table('entrees', function (Blueprint $table) {
            $table->dropColumn('date_entree'); // Supprime la colonne 'date_entree'
        });
    }
}
