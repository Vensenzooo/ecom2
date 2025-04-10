<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ session('theme', 'light') }}">
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
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('client.dashboard') }}">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Book base -->
                    <path d="M7 10C7 8.89543 7.89543 8 9 8H31C32.1046 8 33 8.89543 33 10V30C33 31.1046 32.1046 32 31 32H9C7.89543 32 7 31.1046 7 30V10Z" fill="#4e73df"/>
                    <!-- Book pages -->
                    <path d="M10 12H30V28H10V12Z" fill="#ffffff"/>
                    <!-- Book binding -->
                    <path d="M9 8C7.89543 8 7 8.89543 7 10V30C7 31.1046 7.89543 32 9 32H10V8H9Z" fill="#224abe"/>
                    <!-- Fork icon -->
                    <path d="M15 16V22M19 16V22M23 16V22M15 14V15C15 16.6569 16.3431 18 18 18H20C21.6569 18 23 16.6569 23 15V14" stroke="#4e73df" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <!-- Plate/dish icon -->
                    <circle cx="20" cy="24" r="3" fill="#4e73df"/>
                    <circle cx="20" cy="24" r="1.5" fill="#ffffff"/>
                </svg>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
                    
                    <!-- Bouton de retour à la version admin pour les rôles système -->
                    @php
                        $isAdmin = Auth::user()->roles()->where('nom', 'admin')->exists();
                        $isEditor = Auth::user()->roles()->where('nom', 'editeur')->exists();
                        $isManager = Auth::user()->roles()->where('nom', 'gestionnaire')->exists();
                    @endphp
                    
                    @if($isAdmin || $isEditor || $isManager)
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-danger text-dark ms-2" href="{{ route('dashboard') }}">
                            <i class="fas fa-user-shield me-1"></i> Version Admin
                        </a>
                    </li>
                    @endif
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
                                        <a class="dropdown-item {{ !$alert->read_at ? 'fw-bold' : '' }}" href="{{ route('client.alerts.index') }}">
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
                                    <a class="dropdown-item text-center" href="{{ route('client.alerts.index') }}">
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
                        <a id="userDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            @if(Auth::user()->avatar)
                                <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                            @else
                                <div class="avatar-circle me-2" style="width: 32px; height: 32px; background-color: #4e73df; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="fas fa-user-circle me-2"></i> Mon profil
                            </a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </form>
                        </div>
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

    <!-- Footer - Hide on specific pages -->
    @if(!Request::is('profile') && !Request::is('/') && !Request::is('client/tokens*') && !Request::is('client/catalog*') && !Request::is('client/orders*') && !Request::is('liste-cadeaux*'))
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>LivresGourmands</h5>
                    <p class="text-muted">Votre destination pour les livres de cuisine et de gastronomie.</p>
                    <div class="social-icons">
                        <a href="#" class="me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-2"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('client.catalog') }}" class="text-decoration-none text-white">Catalogue</a></li>
                        <li><a href="{{ route('client.orders') }}" class="text-decoration-none text-white">Mes commandes</a></li>
                        <li><a href="{{ route('profile.show') }}" class="text-decoration-none text-white">Mon compte</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <address class="text-muted">
                        123 Rue de la Cuisine<br>
                        75000 Paris, France<br>
                        <i class="fas fa-envelope me-2"></i>contact@livresgourmands.net<br>
                        <i class="fas fa-phone me-2"></i>+33 1 23 45 67 89
                    </address>
                </div>
            </div>
            <hr class="mt-4 mb-3">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} LivresGourmands. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    @endif

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Apply theme changes immediately when selected -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeRadios = document.querySelectorAll('input[name="theme"]');
            if (themeRadios.length > 0) {
                themeRadios.forEach(radio => {
                    radio.addEventListener('change', function() {
                        document.documentElement.setAttribute('data-bs-theme', this.value);
                    });
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
