@extends('layouts.client')

@section('title', 'Mon tableau de bord')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <h1>Bonjour, {{ Auth::user()->name }}</h1>
            <p class="lead">Bienvenue sur votre espace personnel</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('client.catalog') }}" class="btn btn-primary">
                <i class="fas fa-book me-1"></i> Parcourir le catalogue
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Derniers livres -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">Derniers livres ajoutés</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(isset($latestBooks) && $latestBooks->count() > 0)
                            @foreach($latestBooks as $book)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $book->titre }}</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">{{ $book->auteur }}</h6>
                                        <p class="card-text">{{ \Illuminate\Support\Str::limit($book->description, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary">{{ $book->prix }} €</span>
                                            <a href="{{ route('client.book.details', $book) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-center">Aucun livre disponible pour le moment.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="m-0">Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('client.cart.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-shopping-cart me-1"></i> Mon panier
                        </a>
                        <a href="{{ route('client.orders') }}" class="btn btn-outline-success">
                            <i class="fas fa-box me-1"></i> Mes commandes
                        </a>
                        <a href="{{ route('client.gift-lists.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-gift me-1"></i> Mes listes de cadeaux
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Autres cartes -->
        
        <!-- Bouton d'accès rapide à la liste de cadeaux partagée -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Liste de cadeaux partagée</h5>
                </div>
                <div class="card-body d-flex flex-column justify-content-between">
                    <p>Accédez rapidement à la liste de cadeaux partagée.</p>
                    <div class="mt-3">
                        <a href="http://127.0.0.1:8000/liste-cadeaux/QwaMGdNmEn" 
                            class="btn btn-primary w-100" target="_blank">
                            <i class="fas fa-gift me-2"></i> Voir la liste de cadeaux
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Autres cartes -->
    </div>
</div>
@endsection
