<?php

// database/migrations/xxxx_xx_xx_create_clients_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name' ,191); // Nom du client
            $table->string('email' ,191)->unique(); // Email du client
            $table->string('phone' ,191)->nullable(); // Numéro de téléphone
            $table->string('address' ,191)->nullable(); // Adresse du client
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
