<?php

// database/migrations/xxxx_xx_xx_create_subscriptions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // Lien avec le client
            $table->foreignId('chicken_type_id')->constrained()->onDelete('cascade'); // Lien avec le type de poulet
            $table->integer('quantity')->default(1); // Quantité de poulets pour l'abonnement
            $table->string('description',191)->nullable(); // Description de l'abonnement
            $table->decimal('price', 8, 2); // Prix total de l'abonnement
            $table->timestamp('start_date')->nullable(); // Date de début de l'abonnement
            $table->timestamp('end_date')->nullable(); // Date de fin de l'abonnement
            $table->enum('subscription_type', ['weekly', 'monthly', 'bi-monthly', 'quarterly', 'semi-annual'])->default('monthly'); // Type d'abonnement
            $table->string('delivery_address' ,191); // Lieu de livraison
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
