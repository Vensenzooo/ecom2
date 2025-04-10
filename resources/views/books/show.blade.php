@extends('layouts.app')

@section('title', 'Détails du Livre')

@section('content')
<div class="row my-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>{{ $book->titre }}</h1>
            <div>
                <a href="{{ route('books.edit', $book) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
                <a href="{{ route('books.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Auteur:</strong> {{ $book->auteur }}
                        </div>
                        <div class="mb-3">
                            <strong>Catégorie:</strong> {{ $book->category->nom }}
                        </div>
                        <div class="mb-3">
                            <strong>Niveau d'expertise:</strong> {{ $book->niveau_expertise }}
                        </div>
                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p class="mt-2">{{ $book->description }}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5>Commentaires ({{ $book->comments->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @if($book->comments->count() > 0)
                            <div class="list-group">
                                @foreach($book->comments as $comment)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-1">{{ $comment->user->nom }}</h6>
                                            <span class="badge bg-{{ $comment->statut == 'approuvé' ? 'success' : ($comment->statut == 'en attente' ? 'warning' : 'danger') }}">
                                                {{ $comment->statut }}
                                            </span>
                                        </div>
                                        <p class="mb-1">{{ $comment->contenu }}</p>
                                        <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Aucun commentaire pour ce livre.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Informations commerciales</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Prix:</strong> {{ number_format($book->prix, 2) }} €
                        </div>
                        <div class="mb-3">
                            <strong>Stock:</strong> 
                            <span class="badge bg-{{ $book->stock < 10 ? 'danger' : 'success' }}">{{ $book->stock }}</span>
                        </div>
                        @can('is-manager')
                        <div class="mb-3">
                            <strong>Ventes totales:</strong> {{ $book->sales->sum('quantité') }} exemplaires
                        </div>
                        <div class="mb-3">
                            <strong>Chiffre d'affaires:</strong> {{ number_format($book->sales->sum(function($sale) { return $sale->prix_unitaire * $sale->quantité; }), 2) }} €
                        </div>
                        @endcan
                    </div>
                </div>
                
                @can('is-manager')
                <div class="card">
                    <div class="card-header">
                        <h5>Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('sales.create', ['book_id' => $book->id]) }}" class="btn btn-success">
                                <i class="fas fa-shopping-cart me-1"></i> Enregistrer une vente
                            </a>
                            <form action="{{ route('books.destroy', $book) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre?')">
                                    <i class="fas fa-trash me-1"></i> Supprimer ce livre
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
