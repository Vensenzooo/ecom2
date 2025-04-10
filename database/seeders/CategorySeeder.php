<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Cuisine française',
                'description' => 'Livres sur la gastronomie française traditionnelle et contemporaine'
            ],
            [
                'nom' => 'Pâtisserie',
                'description' => 'Recettes de gâteaux, tartes, et autres délices sucrés'
            ],
            [
                'nom' => 'Cuisine du monde',
                'description' => 'Découvrez les saveurs des différentes cuisines internationales'
            ],
            [
                'nom' => 'Végétarien et Vegan',
                'description' => 'Recettes sans viande et alternatives végétales'
            ],
            [
                'nom' => 'Cuisine familiale',
                'description' => 'Recettes faciles et économiques pour toute la famille'
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
