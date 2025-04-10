@extends('layouts.app')

@section('title', 'Gestion des Ventes')

@section('content')
<div class="row my-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h1>Liste des Ventes</h1>
        <a href="{{ route('sales.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-1"></i> Enregistrer une vente
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Livre</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total</th>
                        <th>Date de vente</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->book->titre ?? 'N/A' }}</td>
                        <td>{{ $sale->quantité }}</td>
                        <td>{{ number_format($sale->prix_unitaire, 2) }} €</td>
                        <td>{{ number_format($sale->prix_unitaire * $sale->quantité, 2) }} €</td>
                        <td>{{ \Carbon\Carbon::parse($sale->date_vente)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('sales.edit', $sale) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
