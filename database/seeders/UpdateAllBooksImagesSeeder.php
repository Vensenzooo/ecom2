<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateAllBooksImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // High-quality cookbook images from Unsplash
        $bookImages = [
            'https://images.unsplash.com/photo-1589118949245-7d38baf380d6?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1476275466078-4007374efbbe?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1556229174-5e42a09e45af?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1607920592519-bab4d7db727b?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1621303837174-89787a7d4729?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1612203985729-70726954388c?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1488477181946-6428a0291777?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1498837167922-ddd27525d352?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1522184216316-3c25379f9760?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1518133835878-5a93cc3f89e5?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=800&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?q=80&w=800&auto=format&fit=crop'
        ];

        // Get all book IDs
        $bookIds = DB::table('books')->pluck('id')->toArray();
        
        // Update each book with an image URL
        foreach ($bookIds as $index => $id) {
            $imageUrl = $bookImages[$index % count($bookImages)];
            
            DB::table('books')
                ->where('id', $id)
                ->update(['image_url' => $imageUrl]);
                
            $this->command->info("Updated image for book ID: {$id}");
        }
        
        // Special books with specific images
        $specialBooks = [
            // Asian cuisine books
            3 => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?q=80&w=800&auto=format&fit=crop',
            // Vegan books
            4 => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop',
            // Kids cooking books
            5 => 'https://images.unsplash.com/photo-1577308856961-8e9ec64d4e5a?q=80&w=800&auto=format&fit=crop',
            // Desserts
            6 => 'https://images.unsplash.com/photo-1621303837174-89787a7d4729?q=80&w=800&auto=format&fit=crop',
            // Italian food
            7 => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?q=80&w=800&auto=format&fit=crop'
        ];
        
        // Update special books
        foreach ($specialBooks as $id => $imageUrl) {
            // Check if book exists before updating
            if (DB::table('books')->where('id', $id)->exists()) {
                DB::table('books')
                    ->where('id', $id)
                    ->update(['image_url' => $imageUrl]);
                    
                $this->command->info("Updated special book ID: {$id}");
            }
        }
    }
}
