<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Comment::with(['user', 'book.category']);
        $filtered = false;
        
        // Filtrer par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('statut', $request->status);
            $filtered = true;
        }
        
        // Filtrer par utilisateur
        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
            $filtered = true;
        }
        
        // Filtrer par livre
        if ($request->has('book_id') && !empty($request->book_id)) {
            $query->where('book_id', $request->book_id);
            $filtered = true;
        }
        
        // Filtrer par catégorie
        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->whereHas('book', function($q) use ($request) {
                $q->where('categorie_id', $request->category_id);
            });
            $filtered = true;
        }
        
        // Appliquer la pagination après tous les filtres
        $comments = $query->latest()->paginate(10);
        
        // Maintenir les paramètres de filtrage dans les liens de pagination
        if ($filtered) {
            $comments->appends($request->all());
        }
        
        // Récupérer les livres et catégories pour les menus déroulants
        $books = Book::orderBy('titre')->get();
        $categories = Category::orderBy('nom')->get();
        
        // Récupérer le nom du livre sélectionné s'il existe
        $selectedBook = null;
        if ($request->has('book_id') && !empty($request->book_id)) {
            $selectedBook = Book::find($request->book_id);
        }
        
        // Récupérer le nom de la catégorie sélectionnée s'il existe
        $selectedCategory = null;
        if ($request->has('category_id') && !empty($request->category_id)) {
            $selectedCategory = Category::find($request->category_id);
        }
        
        // Compter les commentaires par statut
        $pendingCount = Comment::where('statut', 'en attente')->count();
        $approvedCount = Comment::where('statut', 'approuvé')->count();
        $rejectedCount = Comment::where('statut', 'rejeté')->count();
        
        return view('comments.index', compact(
            'comments',
            'books',
            'categories',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'filtered',
            'selectedBook',
            'selectedCategory'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $books = Book::all();
        return view('comments.create', compact('books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contenu' => 'required|string',
            'statut' => 'required|string|in:en attente,approuvé,rejeté',
            'book_id' => 'required|exists:books,id',
        ]);

        $validated['user_id'] = Auth::id();
        
        Comment::create($validated);

        return redirect()->route('comments.index')
            ->with('success', 'Commentaire créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        $comment->load('user', 'book');
        return view('comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        $books = Book::all();
        return view('comments.edit', compact('comment', 'books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'contenu' => 'required|string',
            'statut' => 'required|string|in:en attente,approuvé,rejeté',
            'book_id' => 'required|exists:books,id',
        ]);

        $comment->update($validated);

        return redirect()->route('comments.index')
            ->with('success', 'Commentaire mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();

        return redirect()->route('comments.index')
            ->with('success', 'Commentaire supprimé avec succès.');
    }
}
