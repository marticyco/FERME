<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChickenTypeSubscriptionTable extends Migration
{
    public function up()
    {
        Schema::create('chicken_type_subscription', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('chicken_type_id')->constrained()->onDelete('cascade');
            $table->integer('quantity'); // Quantité de poulet de ce type
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chicken_type_subscription');
    }
}
