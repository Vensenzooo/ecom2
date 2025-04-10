<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->get();
        return response()->json($books);
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

        $book = Book::create($validated);
        return response()->json($book, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json($book->load('category', 'comments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'auteur' => 'sometimes|required|string|max:255',
            'categorie_id' => 'sometimes|required|exists:categories,id',
            'niveau_expertise' => 'sometimes|required|string|max:255',
            'stock' => 'sometimes|required|integer|min:0',
            'prix' => 'sometimes|required|numeric|min:0',
        ]);

        $book->update($validated);
        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
