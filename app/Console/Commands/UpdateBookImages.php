<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Book;

class UpdateBookImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:update-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all book images with high-quality cookbook images';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to update book images...');

        // Collection of high-quality cookbook images from Unsplash
        $bookImages = [
            'https://images.unsplash.com/photo-1589118949245-7d38baf380d6?q=80&w=800&auto=format&fit=crop', // Cookbook general
            'https://images.unsplash.com/photo-1476275466078-4007374efbbe?q=80&w=800&auto=format&fit=crop', // Pasta
            // ...existing code...
            'https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?q=80&w=800&auto=format&fit=crop', // Ice cream
            'https://images.unsplash.com/photo-1529042410759-befb1204b468?q=80&w=800&auto=format&fit=crop'  // Burgers
        ];

        // First make sure the image_url column exists
        try {
            if (!$this->columnExists('books', 'image_url')) {
                $this->info('Adding image_url column to books table...');
                DB::statement("ALTER TABLE books ADD COLUMN IF NOT EXISTS image_url VARCHAR(500) NULL");
            }
        } catch (\Exception $e) {
            $this->error('Error checking/adding column: ' . $e->getMessage());
            return 1;
        }

        // Get all books and update their images
        try {
            $books = Book::all();
            $count = 0;

            foreach ($books as $index => $book) {
                // Get a unique image URL by using the modulo operator to cycle through available images
                $imageIndex = $index % count($bookImages);
                
                DB::table('books')
                    ->where('id', $book->id)
                    ->update(['image_url' => $bookImages[$imageIndex]]);
                
                $count++;
                $this->info("Updated image for book: {$book->titre}");
            }

            // Update specific books with themed images
            $specialBooks = [
                3 => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?q=80&w=800&auto=format&fit=crop', // Asian cuisine
                4 => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop', // Vegetarian
                5 => 'https://images.unsplash.com/photo-1577308856961-8e9ec64d4e5a?q=80&w=800&auto=format&fit=crop'  // Kids cooking
            ];
            
            foreach ($specialBooks as $id => $imageUrl) {
                if (Book::find($id)) {
                    DB::table('books')
                        ->where('id', $id)
                        ->update(['image_url' => $imageUrl]);
                    
                    $this->info("Updated special book ID: {$id}");
                }
            }

            $this->info("Successfully updated {$count} book images!");

        } catch (\Exception $e) {
            $this->error('Error updating book images: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Check if a column exists in a table
     *
     * @param string $table
     * @param string $column
     * @return bool
     */
    private function columnExists($table, $column)
    {
        return DB::getSchemaBuilder()->hasColumn($table, $column);
    }
}