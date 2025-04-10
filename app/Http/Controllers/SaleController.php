<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Book;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('book')->orderBy('date_vente', 'desc')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $books = Book::where('stock', '>', 0)->get();
        $preselectedBookId = $request->book_id;
        
        return view('sales.create', compact('books', 'preselectedBookId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantité' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'date_vente' => 'required|date',
        ]);

        // Vérifier si le stock est suffisant
        $book = Book::findOrFail($validated['book_id']);
        
        if ($validated['quantité'] > $book->stock) {
            return back()->withErrors(['quantité' => 'Stock insuffisant. Seulement ' . $book->stock . ' disponibles.'])->withInput();
        }
        
        // Mettre à jour le stock du livre
        $book->stock -= $validated['quantité'];
        $book->save();
        
        // Créer la vente
        Sale::create($validated);

        return redirect()->route('sales.index')
            ->with('success', 'Vente enregistrée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale->load('book.category');
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $books = Book::all();
        return view('sales.edit', compact('sale', 'books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantité' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'date_vente' => 'required|date',
        ]);

        $oldQuantity = $sale->getAttribute('quantité');
        $oldBookId = $sale->getAttribute('book_id');
        $book = Book::findOrFail($validated['book_id']);
        
        // Ajuster le stock si nécessaire
        if ($oldBookId == $validated['book_id']) {
            // Même livre, ajuster la différence de quantité
            $stockDifference = $oldQuantity - $validated['quantité'];
            
            if ($stockDifference < 0 && abs($stockDifference) > $book->stock) {
                return back()->withErrors(['quantité' => 'Stock insuffisant.'])->withInput();
            }
            
            $book->stock += $stockDifference;
            $book->save();
        } else {
            // Livre différent, restaurer l'ancien stock et diminuer le nouveau
            $oldBook = Book::findOrFail($oldBookId);
            $oldBook->stock += $oldQuantity;
            $oldBook->save();
            
            if ($validated['quantité'] > $book->stock) {
                return back()->withErrors(['quantité' => 'Stock insuffisant.'])->withInput();
            }
            
            $book->stock -= $validated['quantité'];
            $book->save();
        }
        
        $sale->update($validated);

        return redirect()->route('sales.index')
            ->with('success', 'Vente mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        // Restaurer le stock
        $book = Book::findOrFail($sale->getAttribute('book_id'));
        $book->stock += $sale->getAttribute('quantité');
        $book->save();
        
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Vente supprimée avec succès.');
    }
}
