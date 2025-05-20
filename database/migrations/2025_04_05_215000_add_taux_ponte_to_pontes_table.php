<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTauxPonteToPontesTable extends Migration
{
    public function up()
    {
        Schema::table('pontes', function (Blueprint $table) {
            // Ajout de la colonne taux_ponte
            $table->decimal('taux_ponte', 5, 2)->nullable()->after('date_ponte'); // 5,2 permet un format tel que 100.00, nullable si tu veux autoriser les valeurs vides
        });
    }

    public function down()
    {
        // Si tu veux annuler cette migration, tu supprimes la colonne
        Schema::table('pontes', function (Blueprint $table) {
            $table->dropColumn('taux_ponte');
        });
    }
}
