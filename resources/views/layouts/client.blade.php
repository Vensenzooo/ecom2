<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LivresGourmands') }} - @yield('title')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #28a745 !important;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        .cart-icon {
            position: relative;
        }
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 0.6rem;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('client.dashboard') }}">LivresGourmands</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('client.dashboard') ? 'active' : '' }}" href="{{ route('client.dashboard') }}">
                            Accueil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('client.catalog') ? 'active' : '' }}" href="{{ route('client.catalog') }}">
                            Catalogue
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('client.orders') ? 'active' : '' }}" href="{{ route('client.orders') }}">
                            Mes commandes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.tokens.*') ? 'active' : '' }}" href="{{ route('client.tokens.index') }}">
                            <i class="fas fa-star me-1"></i> Mes Étoiles ({{ Auth::user()->tokens }})
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownNotifications" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @if(Auth::user()->unreadAlertsCount() > 0)
                                <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
                                    {{ Auth::user()->unreadAlertsCount() }}
                                </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownNotifications">
                            <li>
                                <h6 class="dropdown-header">Alertes</h6>
                            </li>
                            @php
                                $alerts = Auth::user()->alerts()->latest()->take(5)->get();
                            @endphp
                            
                            @if($alerts->count() > 0)
                                @foreach($alerts as $alert)
                                    <li>
                                        <a class="dropdown-item {{ !$alert->read_at ? 'fw-bold' : '' }}" href="{{ route('alerts.index') }}">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <i class="fas fa-circle text-{{ $alert->type }} small"></i>
                                                </div>
                                                <div>
                                                    {{ \Illuminate\Support\Str::limit($alert->message, 30) }}
                                                    <div class="text-muted small">{{ $alert->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-center" href="{{ route('alerts.index') }}">
                                        Voir toutes les alertes
                                    </a>
                                </li>
                            @else
                                <li>
                                    <span class="dropdown-item text-center">
                                        Aucune alerte
                                    </span>
                                </li>
                            @endif
                        </ul>
                    </li>

                    <!-- Séparateur vertical -->
                    <li class="nav-item">
                        <span class="nav-link">|</span>
                    </li>
                    
                    <!-- Le reste du menu utilisateur reste inchangé -->
                    <li class="nav-item">
                        <a class="nav-link cart-icon {{ Route::is('client.cart.index') ? 'active' : '' }}" href="{{ route('client.cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                            @endphp
                            @if($cartCount > 0)
                                <span class="cart-badge badge rounded-pill bg-danger">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('client.dashboard') }}">Mon compte</a></li>
                            <li><a class="dropdown-item" href="{{ route('client.orders') }}">Mes commandes</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <!-- Autres liens du menu -->
                    
                    <!-- Lien vers la liste de cadeaux partagée -->
                    <li class="nav-item">
                        <a class="nav-link" href="http://127.0.0.1:8000/liste-cadeaux/QwaMGdNmEn" target="_blank">
                            <i class="fas fa-gift text-warning"></i>
                            <span class="ms-1">Liste Cadeaux QwaMGdNmEn</span>
                        </a>
                    </li>
                    
                    <!-- Autres liens du menu -->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>LivresGourmands</h5>
                    <p>La référence en livres de cuisine pour tous les amateurs de gastronomie.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('client.catalog') }}">Catalogue</a></li>
                        <li><a href="{{ route('client.orders') }}">Mes commandes</a></li>
                        <li><a href="{{ route('client.cart.index') }}">Mon panier</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> contact@livresgourmands.net</li>
                        <li><i class="fas fa-phone me-2"></i> +33 (0)1 23 45 67 89</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Avenue de la Gastronomie, Paris</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} LivresGourmands. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
