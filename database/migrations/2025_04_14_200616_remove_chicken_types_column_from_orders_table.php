<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'chicken_types')) {
                $table->dropColumn('chicken_types');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tu peux la remettre, mais normalement on n'en a pas besoin
            $table->json('chicken_types')->nullable();
        });
    }
};
