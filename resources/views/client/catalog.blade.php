@extends('layouts.client')

@section('title', 'Catalogue de livres')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Catalogue de livres</h1>
        </div>
        <div class="col-md-4">
            <form action="{{ route('client.catalog') }}" method="GET" class="d-flex">
                <input type="text" name="search" placeholder="Rechercher..." class="form-control me-2" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Filtres -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('client.catalog') }}" method="GET">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Catégories</label>
                            @foreach($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" id="category{{ $category->id }}" 
                                        value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category{{ $category->id }}">
                                        {{ $category->nom }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Niveau d'expertise</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="level" id="levelDebutant" 
                                    value="débutant" {{ request('level') == 'débutant' ? 'checked' : '' }}>
                                <label class="form-check-label" for="levelDebutant">
                                    Débutant
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="level" id="levelAmateur" 
                                    value="amateur" {{ request('level') == 'amateur' ? 'checked' : '' }}>
                                <label class="form-check-label" for="levelAmateur">
                                    Amateur
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="level" id="levelChef" 
                                    value="chef" {{ request('level') == 'chef' ? 'checked' : '' }}>
                                <label class="form-check-label" for="levelChef">
                                    Chef
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Trier par</label>
                            <select name="sort" class="form-select">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Les plus récents</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Titre (A-Z)</option>
                            </select>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Appliquer les filtres</button>
                            <a href="{{ route('client.catalog') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Liste des livres -->
        <div class="col-md-9">
            @if($books->isEmpty())
                <div class="alert alert-info">
                    Aucun livre trouvé correspondant à vos critères.
                </div>
            @else
                <div class="row">
                    @foreach($books as $book)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="img-container" style="height: 250px; overflow: hidden;">
                                    <img src="{{ $book->image_url }}" class="card-img-top" alt="{{ $book->titre }}" 
                                        style="width: 100%; height: 100%; object-fit: cover;"
                                        onerror="this.onerror=null; this.src='https://placehold.co/600x800?text=Image+Non+Disponible';"
                                        crossorigin="anonymous">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $book->titre }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $book->auteur }}</h6>
                                    <p class="card-text">{{ Str::limit($book->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-primary">{{ number_format($book->prix, 2) }} €</span>
                                        <span class="badge bg-{{ $book->stock > 10 ? 'success' : ($book->stock > 0 ? 'warning' : 'danger') }}">
                                            {{ $book->stock > 0 ? 'En stock' : 'Épuisé' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="{{ route('client.book.details', $book) }}" class="btn btn-sm btn-outline-primary">Voir détails</a>
                                    @if($book->stock > 0)
                                        <form action="{{ route('client.cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-cart-plus"></i> Ajouter
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $books->links() }}
                </div>
            @endif
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
