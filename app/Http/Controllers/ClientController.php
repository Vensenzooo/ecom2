<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Afficher le tableau de bord client
     */
    public function dashboard()
    {
        // Récupérer les livres récemment ajoutés
        $latestBooks = Book::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        return view('client.dashboard', compact('latestBooks'));
    }
    
    /**
     * Afficher le catalogue de livres pour les clients
     */
    public function catalog(Request $request)
    {
        $query = Book::with('category')->where('stock', '>', 0);
        
        // Filtrage par catégorie
        if ($request->has('category')) {
            $query->where('categorie_id', $request->category);
        }
        
        // Filtrage par niveau d'expertise
        if ($request->has('level')) {
            $query->where('niveau_expertise', $request->level);
        }
        
        // Recherche par titre ou auteur
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('auteur', 'like', "%{$search}%");
            });
        }
        
        // Tri
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('prix', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('prix', 'desc');
                    break;
                case 'title':
                    $query->orderBy('titre', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $books = $query->paginate(12);
        $categories = Category::all();
        
        return view('client.catalog', compact('books', 'categories'));
    }
    
    /**
     * Afficher les détails d'un livre pour les clients
     */
    public function bookDetails(Book $book)
    {
        $book->load(['category', 'comments' => function($query) {
            $query->where('statut', 'approuvé');
        }, 'comments.user']);
        
        $relatedBooks = Book::where('categorie_id', $book->categorie_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();
        
        return view('client.book_details', compact('book', 'relatedBooks'));
    }
    
    /**
     * Afficher l'historique des commandes du client
     */
    public function orders()
    {
        // Dans un système réel, vous auriez une table d'orders liée à l'utilisateur
        $userComments = Comment::where('user_id', Auth::id())
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $bookIds = $userComments->pluck('book_id')->unique();
        $books = Book::whereIn('id', $bookIds)->get();
        
        return view('client.orders', compact('books'));
    }
    
    /**
     * Ajouter un commentaire à un livre
     */
    public function addComment(Request $request, Book $book)
    {
        $request->validate([
            'contenu' => 'required|string|min:10',
        ]);
        
        Comment::create([
            'contenu' => $request->contenu,
            'statut' => 'en attente',
            'user_id' => Auth::id(),
            'book_id' => $book->id,
        ]);
        
        return redirect()->back()->with('success', 'Votre commentaire a été soumis et sera visible après modération.');
    }
}
