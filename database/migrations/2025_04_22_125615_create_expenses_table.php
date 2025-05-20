<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            // Retiré la colonne band_id et la contrainte étrangère
            $table->decimal('montant', 10, 2); // Montant de la dépense
            $table->text('description')->nullable(); // Description de la dépense
            $table->date('date'); // Date de la dépense
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
