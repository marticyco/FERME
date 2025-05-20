<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appelle le seeder des rôles et permissions
        $this->call([
            RolePermissionSeeder::class,
        ]);
    }
}
