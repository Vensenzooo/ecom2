@extends('layouts.app')

@section('title', 'Détails de la Vente')

@section('content')
<div class="row my-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Détails de la Vente #{{ $sale->id }}</h1>
            <div>
                <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Informations de la vente</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Détails de la vente</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID</th>
                                <td>{{ $sale->id }}</td>
                            </tr>
                            <tr>
                                <th>Date de vente</th>
                                <td>{{ \Carbon\Carbon::parse($sale->date_vente)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Quantité</th>
                                <td>{{ $sale->quantité }}</td>
                            </tr>
                            <tr>
                                <th>Prix unitaire</th>
                                <td>{{ number_format($sale->prix_unitaire, 2) }} €</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td class="fw-bold">{{ number_format($sale->prix_unitaire * $sale->quantité, 2) }} €</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Informations du livre</h6>
                        <table class="table table-bordered">
                            <tr>
                                <th>Titre</th>
                                <td>{{ $sale->book->titre ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Auteur</th>
                                <td>{{ $sale->book->auteur ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Catégorie</th>
                                <td>{{ $sale->book->category->nom ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Stock actuel</th>
                                <td>
                                    @if($sale->book)
                                        @if($sale->book->stock < 10)
                                            <span class="badge bg-danger">{{ $sale->book->stock }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $sale->book->stock }}</span>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Actions</th>
                                <td>
                                    @if($sale->book)
                                        <a href="{{ route('books.show', $sale->book) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-book me-1"></i> Voir le livre
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Actions</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente?')">
                        <i class="fas fa-trash me-1"></i> Supprimer cette vente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
