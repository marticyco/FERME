<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialise le cache de permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Liste complète des permissions
        $permissions = [
            // Incubation
            'view incubations',
            'create incubation',
            'edit incubation',
            'delete incubation',

            // Utilisateurs / rôles
            'manage users',
            'assign roles',

            // Lots
            'view lot',
            'create lot',
            'update lot',
            'delete lot',
            'view performance',
            'view overview',

            // Mortalité
            'create mortalite',
            'view mortalite stats',
            'view global mortalite stats',

            // Pontes
            'create ponte',
            'view ponte',
        ];

        // Crée les permissions si elles n'existent pas
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Crée les rôles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $technicien = Role::firstOrCreate(['name' => 'technicien']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Attribution des permissions aux rôles
        $admin->syncPermissions(Permission::all());

        $manager->syncPermissions([
            'view incubations',
            'create incubation',
            'edit incubation',

            'view lot',
            'create lot',
            'update lot',
            'delete lot',
            'view performance',
            'view overview',

            'create mortalite',
            'view mortalite stats',
            'view global mortalite stats',

            'create ponte',
            'view ponte',
        ]);

        $technicien->syncPermissions([
            'view incubations',
            'create incubation',
            'edit incubation',

            'view lot',
            'create lot',
            'update lot',
            'view performance',
            'view overview',

            'create mortalite',
            'view mortalite stats',

            'create ponte',
            'view ponte',
        ]);

        $user->syncPermissions([
            'view incubations',

            'view lot',
            'view performance',
            'view overview',

            'view mortalite stats',
            'view global mortalite stats',

            'view ponte',
        ]);

        // Crée un utilisateur admin
        $adminUser = User::firstOrCreate([
            'email' => 'cyr@lnb.bj',
        ], [
            'name' => 'Admin Principal',
            'password' => Hash::make('Jsnl02071985@CTN'), // Change en production
        ]);
        $adminUser->assignRole('admin');

        // Crée un utilisateur manager
        $managerUser = User::firstOrCreate([
            'email' => 'manager@example.com',
        ], [
            'name' => 'Manager Exemple',
            'password' => Hash::make('password'),
        ]);
        $managerUser->assignRole('manager');

        // Crée un technicien
        $technicianUser = User::firstOrCreate([
            'email' => 'benie@lnb.bj',
        ], [
            'name' => 'Technicien Exemple',
            'password' => Hash::make('benie@123'),
        ]);
        $technicianUser->assignRole('technicien');

        // Crée un utilisateur lambda
        $regularUser = User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'name' => 'Utilisateur Simple',
            'password' => Hash::make('password'),
        ]);
        $regularUser->assignRole('user');
    }
}