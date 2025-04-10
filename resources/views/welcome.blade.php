<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivresGourmands - Livres de Cuisine</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1505682634904-d7c8d95cdc50');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            margin-bottom: 3rem;
        }
        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #28a745;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="/">LivresGourmands</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @if (Auth::check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.dashboard') }}">Mon compte</a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="nav-link border-0 bg-transparent">Déconnexion</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('signup') }}">Inscription</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Livres Gourmands</h1>
            <p class="lead">La référence en livres de cuisine pour tous les amateurs de gastronomie</p>
            <div class="mt-5">
                @if (Auth::check())
                    <a href="{{ route('client.catalog') }}" class="btn btn-success btn-lg me-3">Explorer le catalogue</a>
                @else
                    <a href="{{ route('signup') }}" class="btn btn-success btn-lg me-3">Créer un compte</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Se connecter</a>
                @endif
            </div>
        </div>
    </section>

    <div class="container mb-5">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3>Large Sélection</h3>
                <p>Découvrez notre vaste catalogue de livres de cuisine pour tous les niveaux.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-star"></i>
                </div>
                <h3>Qualité Garantie</h3>
                <p>Tous nos livres sont soigneusement sélectionnés par nos experts culinaires.</p>
            </div>
            <div class="col-md-4 mb-4">
                <div class="feature-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <h3>Livraison Rapide</h3>
                <p>Commandez aujourd'hui et recevez votre livre en quelques jours seulement.</p>
            </div>
        </div>
    </div>

    <footer class="bg-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; {{ date('Y') }} LivresGourmands. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-decoration-none me-3">Mentions légales</a>
                    <a href="#" class="text-decoration-none me-3">Conditions d'utilisation</a>
                    <a href="#" class="text-decoration-none">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
