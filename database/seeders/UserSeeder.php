<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un administrateur
        $admin = User::create([
            'name' => 'Admin', // Changé de 'nom' à 'name'
            'email' => 'admin@livresgourmands.net',
            'password' => Hash::make('password'),
        ]);
        $admin->roles()->attach(Role::where('nom', 'admin')->first());

        // Créer un gestionnaire
        $manager = User::create([
            'name' => 'Gestionnaire', // Changé de 'nom' à 'name'
            'email' => 'gestionnaire@livresgourmands.net',
            'password' => Hash::make('password'),
        ]);
        $manager->roles()->attach(Role::where('nom', 'gestionnaire')->first());

        // Créer un éditeur
        $editor = User::create([
            'name' => 'Éditeur', // Changé de 'nom' à 'name'
            'email' => 'editeur@livresgourmands.net',
            'password' => Hash::make('password'),
        ]);
        $editor->roles()->attach(Role::where('nom', 'editeur')->first());

        // Créer 5 utilisateurs supplémentaires avec des rôles aléatoires
        User::factory(5)->create()->each(function ($user) {
            $roleId = Role::inRandomOrder()->first()->id;
            $user->roles()->attach($roleId);
        });
    }
}
