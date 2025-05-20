<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateSortieToSortiesTable extends Migration
{
    public function up()
    {
        Schema::table('sorties', function (Blueprint $table) {
            $table->date('date_sortie')->nullable(); // Ajoute la colonne 'date_sortie'
        });
    }

    public function down()
    {
        Schema::table('sorties', function (Blueprint $table) {
            $table->dropColumn('date_sortie'); // Supprime la colonne 'date_sortie'
        });
    }
}
