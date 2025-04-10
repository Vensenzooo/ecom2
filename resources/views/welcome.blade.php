<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="{{ session('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>LivresGourmands - Pour les passionnés de cuisine</title>
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1606787366850-de6330128bfc?q=80&w=1470&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 8rem 0;
            margin-bottom: 3rem;
            position: relative;
        }
        .hero-content {
            max-width: 700px;
            margin: 0 auto;
        }
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .feature-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
            color: #4e73df;
        }
        .category-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .category-card:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .category-img {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        .testimonial-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            padding: 2rem;
            height: 100%;
        }
        .testimonial-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }
        .quote-icon {
            font-size: 2rem;
            color: #4e73df;
            opacity: 0.2;
            margin-bottom: 1rem;
        }
        .cta-section {
            background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
            color: white;
            padding: 5rem 0;
            margin: 5rem 0 0;
        }
        .btn-primary {
            background: linear-gradient(90deg, #4e73df 0%, #224abe 100%);
            border: none;
            padding: 0.8rem 2rem;
            font-weight: 600;
        }
        .btn-secondary {
            background: white;
            color: #4e73df;
            border: none;
            padding: 0.8rem 2rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
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
                <span class="ms-2 fw-bold">LivresGourmands</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('client.catalog') }}">Catalogue</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.dashboard') }}">Mon compte</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('client.cart.index') }}">
                                <i class="fas fa-shopping-cart"></i>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/login?register">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center hero-content">
            <h1 class="hero-title">Explorez l'Art de la Cuisine</h1>
            <p class="hero-subtitle">Découvrez notre collection de livres de cuisine pour tous les goûts et tous les niveaux</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('client.catalog') }}" class="btn btn-primary btn-lg">Parcourir le catalogue</a>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">Créer un compte</a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="container mb-5">
        <div class="text-center mb-5">
            <h2 class="mb-2">Pourquoi nous choisir?</h2>
            <p class="lead text-muted">Nous rendons l'expérience culinaire accessible à tous</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="h4">Choix Exclusif</h3>
                        <p class="text-muted">Une collection soigneusement sélectionnée de livres pour tous les niveaux d'expertise</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3 class="h4">Livraison Rapide</h3>
                        <p class="text-muted">Recevez vos livres en quelques jours pour commencer rapidement vos recettes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card feature-card p-4">
                    <div class="text-center">
                        <div class="feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="h4">Programme de Fidélité</h3>
                        <p class="text-muted">Gagnez des tokens à chaque achat pour obtenir des réductions sur vos prochaines commandes</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="container mb-5">
        <div class="text-center mb-5">
            <h2 class="mb-2">Explorez nos catégories</h2>
            <p class="lead text-muted">Trouvez l'inspiration culinaire qui vous ressemble</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card category-card">
                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                        <img src="https://images.unsplash.com/photo-1588195538326-c5b1e9f80a1b?q=80&w=800&auto=format&fit=crop" 
                             class="category-img" alt="Pâtisserie"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1619021897634-526cd9915a8f?q=80&w=800&auto=format&fit=crop'; this.classList.add('img-fluid');">
                    </div>
                    <div class="card-body">
                        <h3 class="h5">Pâtisserie</h3>
                        <p class="text-muted mb-3">L'art de la pâtisserie à portée de main</p>
                        <a href="{{ route('client.catalog') }}?category=patisserie" class="btn btn-sm btn-outline-primary">Découvrir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card category-card">
                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                        <img src="https://images.unsplash.com/photo-1595295333158-4742f28fbd85?q=80&w=800&auto=format&fit=crop" 
                             class="category-img" alt="Cuisine italienne"
                             onerror="this.onerror=null; this.src='https://plus.unsplash.com/premium_photo-1661778091956-15dcc0ed5462?q=80&w=800&auto=format&fit=crop'; this.classList.add('img-fluid');">
                    </div>
                    <div class="card-body">
                        <h3 class="h5">Cuisine italienne</h3>
                        <p class="text-muted mb-3">Des recettes traditionnelles d'Italie</p>
                        <a href="{{ route('client.catalog') }}?category=italien" class="btn btn-sm btn-outline-primary">Découvrir</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card category-card">
                    <div class="position-relative" style="height: 180px; overflow: hidden;">
                        <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?q=80&w=800&auto=format&fit=crop" 
                             class="category-img" alt="Cuisine végétarienne"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1574484284002-952d92456975?q=80&w=800&auto=format&fit=crop'; this.classList.add('img-fluid');">
                    </div>
                    <div class="card-body">
                        <h3 class="h5">Cuisine végétarienne</h3>
                        <p class="text-muted mb-3">Des plats savoureux et équilibrés</p>
                        <a href="{{ route('client.catalog') }}?category=vegetarien" class="btn btn-sm btn-outline-primary">Découvrir</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="container mb-5">
        <div class="text-center mb-5">
            <h2 class="mb-2">Ce que disent nos clients</h2>
            <p class="lead text-muted">Des cuisiniers passionnés partagent leur expérience</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="text-center">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="mb-4">"Grâce aux livres de LivresGourmands, j'ai pu me perfectionner et impressionner ma famille avec mes nouvelles recettes."</p>
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <h5 class="mb-1">Marie Dubois</h5>
                            <p class="text-muted small">Passionnée de pâtisserie</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="text-center">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="mb-4">"Une collection incroyable pour tous les niveaux. J'ai commencé en tant que débutant et maintenant je cuisine comme un chef!"</p>
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <h5 class="mb-1">Thomas Martin</h5>
                            <p class="text-muted small">Chef amateur</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="text-center">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="mb-4">"Livraison rapide et service client exceptionnel. Je recommande vivement LivresGourmands à tous les amateurs de cuisine."</p>
                        <div class="d-flex justify-content-center align-items-center flex-column">
                            <h5 class="mb-1">Sophie Lefebvre</h5>
                            <p class="text-muted small">Blogueuse culinaire</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container text-center">
            <h2 class="mb-4">Prêt à commencer votre voyage culinaire?</h2>
            <p class="lead mb-4">Explorez notre collection et trouvez votre prochaine inspiration.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('client.catalog') }}" class="btn btn-secondary btn-lg">Parcourir le catalogue</a>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-light btn-lg">S'inscrire</a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>LivresGourmands</h5>
                    <p class="text-muted">Votre destination pour les livres de cuisine et de gastronomie.</p>
                    <div class="social-icons">
                        <a href="#" class="me-2 text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="me-2 text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="me-2 text-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('client.catalog') }}" class="text-decoration-none text-white">Catalogue</a></li>
                        <li><a href="#" class="text-decoration-none text-white">À propos</a></li>
                        <li><a href="#" class="text-decoration-none text-white">Contact</a></li>
                        <li><a href="#" class="text-decoration-none text-white">Conditions d'utilisation</a></li>
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

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
