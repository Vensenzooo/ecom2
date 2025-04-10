<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les rôles standards s'ils n'existent pas déjà
        $roles = [
            'admin',
            'gestionnaire',
            'editeur',
            'client',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nom' => $role]);
        }
    }
}
