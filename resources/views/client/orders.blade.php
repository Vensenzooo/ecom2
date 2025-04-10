@extends('layouts.client')

@section('title', 'Mes commandes')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Mes commandes</h1>
        </div>
    </div>
    
    @if(isset($books) && $books->count() > 0)
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Livre</th>
                                <th>Auteur</th>
                                <th>Prix</th>
                                <th>Date d'achat</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($books as $book)
                                <tr>
                                    <td>{{ $book->titre }}</td>
                                    <td>{{ $book->auteur }}</td>
                                    <td>{{ number_format($book->prix, 2) }} €</td>
                                    <td>{{ $book->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('client.book.details', $book) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-bag fa-3x mb-3 text-muted"></i>
                <h3>Vous n'avez pas encore de commandes</h3>
                <p class="text-muted">Parcourez notre catalogue pour trouver des livres qui vous intéressent.</p>
                <a href="{{ route('client.catalog') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-book me-1"></i> Découvrir le catalogue
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
