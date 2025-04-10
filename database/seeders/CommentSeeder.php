<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $books = Book::all();
        $statuses = ['approuvé', 'en attente', 'rejeté'];

        // Créer 30 commentaires
        for ($i = 0; $i < 30; $i++) {
            Comment::create([
                'contenu' => fake()->paragraph(),
                'statut' => fake()->randomElement($statuses),
                'user_id' => $users->random()->id,
                'book_id' => $books->random()->id,
            ]);
        }
    }
}
