<?php

// database/migrations/xxxx_xx_xx_create_chicken_types_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChickenTypesTable extends Migration
{
    public function up()
    {
        Schema::create('chicken_types', function (Blueprint $table) {
            $table->id();
            $table->string('name' ,191); // Nom du type de poulet (par exemple, Poulet de Chair, Poulet Pondeur)
            $table->text('description')->nullable(); // Description du type de poulet
            $table->decimal('price', 8, 2); // Prix de l'abonnement pour ce type de poulet
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chicken_types');
    }
}
