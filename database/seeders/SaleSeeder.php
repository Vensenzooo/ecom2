<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = Book::all();
        
        // Créer des ventes sur les 3 derniers mois
        for ($i = 0; $i < 100; $i++) {
            $book = $books->random();
            $quantity = rand(1, 5);
            $date = Carbon::now()->subDays(rand(0, 90));
            
            Sale::create([
                'book_id' => $book->id,
                'quantité' => $quantity,
                'prix_unitaire' => $book->prix,
                'date_vente' => $date,
            ]);
        }

        // Créer plus de ventes pour le mois en cours (pour avoir des statistiques plus significatives)
        for ($i = 0; $i < 50; $i++) {
            $book = $books->random();
            $quantity = rand(1, 5);
            $date = Carbon::now()->subDays(rand(0, 29));
            
            Sale::create([
                'book_id' => $book->id,
                'quantité' => $quantity,
                'prix_unitaire' => $book->prix,
                'date_vente' => $date,
            ]);
        }
    }
}
