<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('band_id')->constrained()->onDelete('cascade'); // Retiré
            $table->decimal('montant', 10, 2); // Montant de la vente
            $table->text('description')->nullable(); // Description de la vente
            $table->date('date'); // Date de la vente
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
