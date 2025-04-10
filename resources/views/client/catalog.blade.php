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
                    <form action="{{ route('client.catalog') }}" method="GET" id="filterForm">
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Catégories</label>
                            <select name="category" id="category" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Niveau d'expertise</label>
                            <select name="expertise" id="expertise" class="form-select" onchange="document.getElementById('filterForm').submit();">
                                <option value="">Tous les niveaux</option>
                                <option value="débutant" {{ request('expertise') == 'débutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="amateur" {{ request('expertise') == 'amateur' ? 'selected' : '' }}>Amateur</option>
                                <option value="chef" {{ request('expertise') == 'chef' ? 'selected' : '' }}>Chef</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Trier par</label>
                            <select name="sort" id="sort" class="form-select" onchange="document.getElementById('filterForm').submit();">
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
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach($books as $book)
                        <div class="col">
                            <div class="card h-100 book-card">
                                <div class="position-relative book-image-container">
                                    @if($book->image_url)
                                        <img src="{{ $book->image_url }}" class="book-image" alt="{{ $book->titre }}" 
                                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1589118949245-7d38baf380d6?q=80&w=800&auto=format&fit=crop';">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1589118949245-7d38baf380d6?q=80&w=800&auto=format&fit=crop" 
                                            class="book-image" alt="{{ $book->titre }}">
                                    @endif
                                    
                                    @if($book->stock < 5)
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <span class="badge bg-danger">Stock limité</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $book->titre }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $book->auteur }}</h6>
                                    <p class="card-text">{{ Str::limit($book->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
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

<!-- Floating Bottom Navigation -->
<div class="floating-nav" id="floatingNav">
    <div class="container">
        <div class="floating-nav-content">
            <button class="floating-nav-button" onclick="scrollToTop()">
                <i class="fas fa-arrow-up"></i>
                <span>Haut</span>
            </button>
            <button class="floating-nav-button" onclick="scrollToFilters()">
                <i class="fas fa-filter"></i>
                <span>Filtres</span>
            </button>
            <button class="floating-nav-button" onclick="scrollToProducts()">
                <i class="fas fa-book"></i>
                <span>Livres</span>
            </button>
            <a href="{{ route('client.cart.index') }}" class="floating-nav-button">
                <i class="fas fa-shopping-cart"></i>
                <span>Panier</span>
            </a>
        </div>
    </div>
</div>

<!-- Floating Top Navigation Bar -->
<div class="floating-top-nav" id="floatingTopNav">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <a class="navbar-brand me-4" href="{{ url('/') }}">
                    <i class="fas fa-book me-2"></i>LivresGourmands
                </a>
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('client.catalog') }}">Catalogue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.orders') }}">Mes commandes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.tokens.index') }}">
                            Mes Étoiles ({{ Auth::user()->tokens ?? 0 }})
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                @can('is-admin')
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-user-shield me-1"></i>Admin
                    </a>
                @endcan
                <a href="{{ route('client.cart.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-shopping-cart me-1"></i>Panier
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const floatingNav = document.getElementById('floatingNav');
        const floatingTopNav = document.getElementById('floatingTopNav');
        const filterSection = document.querySelector('.card-header');
        const productsSection = document.querySelector('.col-md-9');
        let lastScrollY = window.scrollY;
        
        // Show/hide floating nav based on scroll direction and position
        window.addEventListener('scroll', function() {
            const currentScrollY = window.scrollY;
            
            // Show bottom nav when scrolling down and past 300px
            if (currentScrollY > 300) {
                floatingNav.classList.add('visible');
            } else {
                floatingNav.classList.remove('visible');
            }
            
            // Show top nav when scrolling down past 150px
            if (currentScrollY > 150) {
                floatingTopNav.classList.add('visible');
            } else {
                floatingTopNav.classList.remove('visible');
            }
            
            lastScrollY = currentScrollY;
        });
        
        // Lazy load images
        const lazyLoadImages = document.querySelectorAll('.lazy-load');
        lazyLoadImages.forEach(img => {
            img.src = img.dataset.src;
            img.onerror = function() {
                this.onerror = null;
                this.src = 'https://placehold.co/600x800?text=Image+Non+Disponible';   
            };
        });
    });
    
</script>      
@endsection

<style>
    .book-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.2s ease-in-out;
        border: 1px solid rgba(0,0,0,0.125);
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .book-image-container {
        height: 200px;
        overflow: hidden;
    }
    .book-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .card-body {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
    }
    .card-text {
        flex-grow: 1;
        overflow: hidden;
    }
    .card-title {
        height: 48px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .card-subtitle {
        height: 24px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    
    /* Floating Top Navigation Bar Styles */
    .floating-top-nav {
        position: fixed;
        top: -80px;
        left: 0;
        right: 0;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: top 0.3s ease;
        z-index: 1010;
        padding: 10px 0;
    }
    
    .floating-top-nav.visible {
        top: 0;
    }
    
    .floating-top-nav .navbar-brand {
        font-weight: bold;
        color: #4e73df;
    }
    
    .floating-top-nav .nav-link {
        color: #5a5c69;
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: all 0.2s;
    }
    
    .floating-top-nav .nav-link:hover,
    .floating-top-nav .nav-link.active {
        color: #4e73df;
    }
    
    /* Adjust body padding when floating nav is visible */
    body.floating-nav-visible {
        padding-top: 60px;
    }
    
    @media (max-width: 991.98px) {
        .floating-top-nav .nav {
             display: none;
        }
        
        .floating-top-nav .navbar-brand {
            font-size: 1rem;
            margin-right: 0;
        }
    } /* Added missing closing brace for the media query */
    
    /* Floating Bottom Navigation Styles */
    .floating-nav {
        position: fixed;
        bottom: -80px;
        left: 0;
        right: 0;
        background-color: #fff;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        transition: bottom 0.3s ease;
        z-index: 1000;
        border-top: 1px solid rgba(0,0,0,0.1);
    }
    
    .floating-nav.visible {
        bottom: 0;
    }
    
    .floating-nav-content {
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
    }
    
    .floating-nav-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 5px 15px;
        border: none;
        background: transparent;
        color: #4e73df;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .floating-nav-button:hover {
        color: #224abe;
        transform: translateY(-3px);
    }
</style>