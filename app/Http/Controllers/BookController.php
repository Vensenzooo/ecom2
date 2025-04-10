<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::with('category');
        
        if ($request->has('stock') && $request->stock === 'faible') {
            $query->where('stock', '<', 10);
        }
        
        $books = $query->paginate(10);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'auteur' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'niveau_expertise' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'prix' => 'required|numeric|min:0',
        ]);

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', 'Livre créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load('category', 'comments.user', 'sales');
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'auteur' => 'required|string|max:255',
            'categorie_id' => 'required|exists:categories,id',
            'niveau_expertise' => 'required|string|in:débutant,amateur,chef',
            'stock' => 'required|integer|min:0',
            'prix' => 'required|numeric|min:0',
            'image_url' => 'nullable|string|max:2000',
        ]);

        // Si l'URL d'image est vide, utiliser la valeur par défaut
        if (empty($validated['image_url'])) {
            $validated['image_url'] = 'https://placehold.co/600x800?text=Livre+de+Cuisine';
        }

        // Vérifier si l'URL est valide et inclut un protocole http/https
        if (!empty($validated['image_url']) && !preg_match('/^https?:\/\//i', $validated['image_url'])) {
            $validated['image_url'] = 'https://' . ltrim($validated['image_url'], ':/');
        }

        $book->update($validated);

        return redirect()->route('books.show', $book)
            ->with('success', 'Livre mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Livre supprimé avec succès.');
    }
}
