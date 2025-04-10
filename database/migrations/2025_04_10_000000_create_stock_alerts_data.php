<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use App\Models\User;
use App\Models\Alert;

class CreateStockAlertsData extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Trouver les livres avec un stock faible (moins de 10)
        $lowStockBooks = Book::where('stock', '<', 10)->get();
        
        // Trouver un administrateur pour créer les alertes
        $admin = User::whereHas('roles', function($query) {
            $query->where('nom', 'admin');
        })->first();
        
        // Si aucun admin n'est trouvé, utiliser le premier utilisateur
        if (!$admin) {
            $admin = User::first();
        }
        
        // Récupérer tous les utilisateurs ayant un rôle de gestionnaire ou éditeur
        $managers = User::whereHas('roles', function($query) {
            $query->whereIn('nom', ['gestionnaire', 'editeur']);
        })->get();
        
        // Si aucun livre avec stock faible n'est trouvé, on crée des données fictives
        if ($lowStockBooks->isEmpty()) {
            // Mettre à jour quelques livres pour avoir un stock faible
            $books = Book::take(5)->get();
            foreach ($books as $index => $book) {
                $stock = rand(1, 5);
                $book->stock = $stock;
                $book->save();
                
                // Ajouter à notre collection de livres à faible stock
                $lowStockBooks->push($book);
            }
        }
        
        // Créer une alerte pour chaque livre à stock faible pour chaque gestionnaire/éditeur
        foreach ($managers as $manager) {
            foreach ($lowStockBooks as $book) {
                Alert::create([
                    'user_id' => $manager->id,
                    'created_by' => $admin ? $admin->id : 1,
                    'message' => "Stock faible: {$book->titre} n'a plus que {$book->stock} exemplaire(s) en stock.",
                    'type' => $book->stock <= 2 ? 'danger' : 'warning',
                    'read_at' => null,
                ]);
            }
        }
        
        // Mettre à jour le stock de certains livres de manière compatible avec MariaDB
        // Rendre 3 livres avec un stock de 3 unités
        $randomBooks1 = Book::inRandomOrder()->take(3)->get();
        foreach ($randomBooks1 as $book) {
            $book->stock = 3;
            $book->save();
        }
        
        // Rendre 2 livres avec un stock de 1 unité
        $randomBooks2 = Book::where('stock', '>', 3)
                           ->inRandomOrder()
                           ->take(2)
                           ->get();
        foreach ($randomBooks2 as $book) {
            $book->stock = 1;
            $book->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Supprimer toutes les alertes de type stock faible
        Alert::where('message', 'like', 'Stock faible:%')->delete();
    }
}
