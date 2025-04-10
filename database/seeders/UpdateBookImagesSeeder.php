<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

class UpdateBookImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Collection of high-quality cookbook images from Unsplash
        $bookImages = [
            'https://images.unsplash.com/photo-1589118949245-7d38baf380d6?q=80&w=800&auto=format&fit=crop', // Cookbook general
            'https://images.unsplash.com/photo-1476275466078-4007374efbbe?q=80&w=800&auto=format&fit=crop', // Pasta
            'https://images.unsplash.com/photo-1556229174-5e42a09e45af?q=80&w=800&auto=format&fit=crop', // Bread
            'https://images.unsplash.com/photo-1607920592519-bab4d7db727b?q=80&w=800&auto=format&fit=crop', // Pastry
            'https://images.unsplash.com/photo-1621303837174-89787a7d4729?q=80&w=800&auto=format&fit=crop', // Cake
            'https://images.unsplash.com/photo-1612203985729-70726954388c?q=80&w=800&auto=format&fit=crop', // French pastry
            'https://images.unsplash.com/photo-1488477181946-6428a0291777?q=80&w=800&auto=format&fit=crop', // Italian
            'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?q=80&w=800&auto=format&fit=crop', // Asian
            'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop', // Vegetarian
            'https://images.unsplash.com/photo-1498837167922-ddd27525d352?q=80&w=800&auto=format&fit=crop', // French
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop', // Drinks
            'https://images.unsplash.com/photo-1522184216316-3c25379f9760?q=80&w=800&auto=format&fit=crop', // Desserts
            'https://images.unsplash.com/photo-1518133835878-5a93cc3f89e5?q=80&w=800&auto=format&fit=crop', // BBQ
            'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=800&auto=format&fit=crop', // Fine dining
            'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=800&auto=format&fit=crop', // Seafood
            'https://images.unsplash.com/photo-1630442923896-552f5a1e4868?q=80&w=800&auto=format&fit=crop', // Soups
            'https://images.unsplash.com/photo-1541795795328-f073b763494e?q=80&w=800&auto=format&fit=crop', // Breakfast
            'https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=800&auto=format&fit=crop', // Salads
            'https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?q=80&w=800&auto=format&fit=crop', // Ice cream
            'https://images.unsplash.com/photo-1529042410759-befb1204b468?q=80&w=800&auto=format&fit=crop'  // Burgers
        ];

        // Get all books
        $books = Book::all();

        foreach ($books as $index => $book) {
            // Get a unique image URL by using the modulo operator to cycle through the available images
            $imageIndex = $index % count($bookImages);
            
            DB::table('books')
                ->where('id', $book->id)
                ->update(['image_url' => $bookImages[$imageIndex]]);
                
            $this->command->info("Updated image for book: {$book->titre}");
        }
        
        // Add specific image URLs for the first 5 books based on their themes
        // These are important books that should have more relevant images

        // Voyage culinaire en Asie
        DB::table('books')
            ->where('id', 3)
            ->update(['image_url' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?q=80&w=800&auto=format&fit=crop']);

        // Le grand livre du véganisme
        DB::table('books')
            ->where('id', 4)
            ->update(['image_url' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop']);

        // Cuisiner pour les enfants
        DB::table('books')
            ->where('id', 5)
            ->update(['image_url' => 'https://images.unsplash.com/photo-1577308856961-8e9ec64d4e5a?q=80&w=800&auto=format&fit=crop']);
    }
}
