<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrixAndRecuToTraitementsTable extends Migration
{
    public function up()
    {
        Schema::table('traitements', function (Blueprint $table) {
            $table->decimal('prix', 10, 2)->nullable()->after('date_planifiee');
            $table->string('recu_image_path')->nullable()->after('prix');
        });
    }

    public function down()
    {
        Schema::table('traitements', function (Blueprint $table) {
            $table->dropColumn(['prix', 'recu_image_path']);
        });
    }
}
