<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = Sale::with('book')->get();
        return response()->json($sales);
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

        $sale = Sale::create($validated);
        return response()->json($sale, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return response()->json($sale->load('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'book_id' => 'sometimes|required|exists:books,id',
            'quantité' => 'sometimes|required|integer|min:1',
            'prix_unitaire' => 'sometimes|required|numeric|min:0',
            'date_vente' => 'sometimes|required|date',
        ]);

        $sale->update($validated);
        return response()->json($sale);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
