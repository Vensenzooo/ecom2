@extends('layouts.client')

@section('title', $book->titre)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('client.catalog') }}">Catalogue</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $book->titre }}</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row">
        <!-- Détails du livre -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="row g-0">
                    <div class="col-md-4">
                        <div class="img-container" style="min-height: 300px;">
                            <img src="" data-src="{{ $book->image_url }}" class="img-fluid rounded-start lazy-load" 
                                alt="{{ $book->titre }}" 
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h1 class="card-title mb-3">{{ $book->titre }}</h1>
                            <h6 class="card-subtitle mb-3 text-muted">Par {{ $book->auteur }}</h6>
                            
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <span class="badge bg-info me-2">{{ $book->category->nom ?? 'Non catégorisé' }}</span>
                                    <span class="badge bg-secondary">Niveau: {{ $book->niveau_expertise }}</span>
                                </div>
                                <div>
                                    <span class="badge bg-{{ $book->stock > 10 ? 'success' : ($book->stock > 0 ? 'warning' : 'danger') }}">
                                        {{ $book->stock > 0 ? 'En stock (' . $book->stock . ')' : 'Épuisé' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <h3 class="text-primary">{{ number_format($book->prix, 2) }} €</h3>
                            </div>
                            
                            <div class="mb-4">
                                <h4>Description</h4>
                                <p>{{ $book->description }}</p>
                            </div>
                            
                            @if($book->stock > 0)
                                <form action="{{ route('client.cart.add') }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <div class="input-group me-2" style="max-width: 150px;">
                                        <span class="input-group-text">Qté</span>
                                        <input type="number" class="form-control" name="quantity" value="1" min="1" max="{{ $book->stock }}">
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-cart-plus me-1"></i> Ajouter au panier
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-danger">
                                    Ce produit est temporairement indisponible.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Commentaires -->
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Commentaires ({{ $book->comments->where('statut', 'approuvé')->count() }})</h4>
                </div>
                <div class="card-body">
                    @if($book->comments->where('statut', 'approuvé')->count() > 0)
                        @foreach($book->comments->where('statut', 'approuvé') as $comment)
                            <div class="border-bottom mb-3 pb-3">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-1">{{ $comment->user->name }}</h5>
                                    <small class="text-muted">{{ $comment->created_at->format('d/m/Y') }}</small>
                                </div>
                                <p class="mb-0">{{ $comment->contenu }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Aucun commentaire pour le moment.</p>
                    @endif
                    
                    <!-- Formulaire de commentaire -->
                    <div class="mt-4">
                        <h5>Laisser un commentaire</h5>
                        <form action="{{ route('client.book.comment', $book) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control @error('contenu') is-invalid @enderror" name="contenu" rows="3" required></textarea>
                                @error('contenu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Votre commentaire sera publié après modération.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Soumettre</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Livres similaires -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Vous pourriez aussi aimer</h4>
                </div>
                <div class="card-body">
                    @if($relatedBooks && $relatedBooks->count() > 0)
                        @foreach($relatedBooks as $relatedBook)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $relatedBook->titre }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $relatedBook->auteur }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary">{{ number_format($relatedBook->prix, 2) }} €</span>
                                        <a href="{{ route('client.book.details', $relatedBook) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Aucun livre similaire disponible.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lazyLoadImages = document.querySelectorAll('.lazy-load');

        lazyLoadImages.forEach(img => {
            img.src = img.dataset.src; // Charger l'image à partir de data-src

            img.onerror = function() {
                this.onerror = null;
                this.src = 'https://placehold.co/600x800?text=Image+Non+Disponible';
            };
        });
    });
</script>
@endsection
