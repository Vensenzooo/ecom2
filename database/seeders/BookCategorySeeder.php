<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class BookCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or update categories with images
        $categories = [
            [
                'nom' => 'Pâtisserie',
                'description' => 'Livres sur la pâtisserie française et internationale',
                'image_url' => 'https://images.unsplash.com/photo-1619021897634-526cd9915a8f?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nom' => 'Cuisine italienne',
                'description' => 'La délicieuse cuisine italienne à portée de main',
                'image_url' => 'https://images.unsplash.com/photo-1595295333158-4742f28fbd85?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nom' => 'Cuisine végétarienne',
                'description' => 'Des recettes végétariennes savoureuses et équilibrées',
                'image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nom' => 'Cuisine française',
                'description' => 'Les classiques de la gastronomie française',
                'image_url' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'nom' => 'Cuisine asiatique',
                'description' => 'Découvrez les saveurs de l\'Asie',
                'image_url' => 'https://images.unsplash.com/photo-1541696490-8744a5dc0228?q=80&w=800&auto=format&fit=crop'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['nom' => $categoryData['nom']],
                $categoryData
            );
        }

        // 2. Sample book image URLs to assign to books
        $bookImages = [
            // Pâtisserie
            'https://images.unsplash.com/photo-1635324236775-115b9b649a9c?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1607365659811-c2d115f99928?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?q=80&w=800&auto=format&fit=crop',
            
            // Cuisine italienne
            'https://images.unsplash.com/photo-1498579150354-977475b7ea0b?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1605888969139-42cca4308aa2?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1525518392674-39ba1fca2ec2?q=80&w=800&auto=format&fit=crop',
            
            // Cuisine végétarienne
            'https://images.unsplash.com/photo-1543339308-43e59d6b73a6?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1615937657715-bc7b4b7962c1?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1540420773420-3366772f4999?q=80&w=800&auto=format&fit=crop',
            
            // Cuisine française
            'https://images.unsplash.com/photo-1608855238293-a8853e7f7c98?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1604908177453-7462950a6a3b?q=80&w=800&auto=format&fit=crop',
            
            // Cuisine asiatique
            'https://images.unsplash.com/photo-1563245372-f21724e3856d?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1555126634-323283e090fa?q=80&w=800&auto=format&fit=crop'
        ];

        // 3. Update books with image URLs
        $books = Book::all();
        foreach ($books as $index => $book) {
            if (empty($book->image_url)) {
                // Use modulo to cycle through available images
                $imageUrl = $bookImages[$index % count($bookImages)];
                
                $book->image_url = $imageUrl;
                $book->save();
                
                $this->command->info("Updated book '{$book->titre}' with image URL");
            }
        }
    }
}
