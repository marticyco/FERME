<?php

// database/migrations/xxxx_xx_xx_create_sale_categories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('sale_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name' ,191)->unique();  // Le nom de la catégorie de vente
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sale_categories');
    }
}
