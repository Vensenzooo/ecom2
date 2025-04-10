<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $books = [
            [
                'titre' => 'Les secrets de la cuisine française',
                'description' => 'Un livre complet sur les techniques et recettes traditionnelles de la gastronomie française.',
                'auteur' => 'Jean Dupont',
                'niveau_expertise' => 'Intermédiaire',
                'stock' => 45,
                'prix' => 29.99,
            ],
            [
                'titre' => 'Pâtisserie pour débutants',
                'description' => 'Apprenez à réaliser des desserts simples mais délicieux.',
                'auteur' => 'Marie Martin',
                'niveau_expertise' => 'Débutant',
                'stock' => 78,
                'prix' => 24.50,
            ],
            [
                'titre' => 'Voyage culinaire en Asie',
                'description' => 'Découvrez les saveurs de la cuisine asiatique à travers 100 recettes authentiques.',
                'auteur' => 'Sophie Chen',
                'niveau_expertise' => 'Intermédiaire',
                'stock' => 35,
                'prix' => 32.00,
            ],
            [
                'titre' => 'Le grand livre du véganisme',
                'description' => 'Une approche complète de la cuisine vegan avec plus de 200 recettes.',
                'auteur' => 'Léa Dubois',
                'niveau_expertise' => 'Tous niveaux',
                'stock' => 52,
                'prix' => 28.75,
            ],
            [
                'titre' => 'Cuisiner pour les enfants',
                'description' => 'Des recettes adaptées aux goûts des enfants et faciles à préparer.',
                'auteur' => 'Thomas Bernard',
                'niveau_expertise' => 'Débutant',
                'stock' => 63,
                'prix' => 19.99,
            ],
        ];

        foreach ($books as $bookData) {
            $category = $categories->random();
            Book::create([
                'titre' => $bookData['titre'],
                'description' => $bookData['description'],
                'auteur' => $bookData['auteur'],
                'categorie_id' => $category->id,
                'niveau_expertise' => $bookData['niveau_expertise'],
                'stock' => $bookData['stock'],
                'prix' => $bookData['prix'],
            ]);
        }

        // Créer 15 livres supplémentaires avec des données aléatoires
        Book::factory(15)->create();
    }
}
