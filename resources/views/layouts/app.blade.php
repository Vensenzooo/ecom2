<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'LivresGourmands') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <!-- Thème CSS -->
    @if(session('theme') === 'dark')
    <link href="{{ asset('css/dark-theme.css') }}" rel="stylesheet">
    @endif

    <style>
        body {
            font-family: 'figtree', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Style général */
        .wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* Sidebar styles */
        #sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 999;
            background: #343a40;
            color: white;
            transition: all 0.3s;
        }
        
        #sidebar .sidebar-header {
            padding: 20px;
            background: #212529;
        }
        
        #sidebar ul.components {
            padding: 20px 0;
            border-bottom: 1px solid #4b545c;
        }
        
        #sidebar ul li a {
            padding: 10px 20px;
            font-size: 1rem;
            display: block;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }
        
        #sidebar ul li a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        
        #sidebar ul li a i {
            margin-right: 10px;
        }
        
        #sidebar ul li.active > a {
            color: white;
            background: #007bff;
        }
        
        /* Content area */
        #content {
            width: calc(100% - 250px);
            min-height: 100vh;
            transition: all 0.3s;
            position: absolute;
            top: 0;
            right: 0;
        }
        
        #content .navbar {
            padding: 15px 10px;
            background: #fff;
            border: none;
            border-radius: 0;
            margin-bottom: 20px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        
        /* When sidebar is closed */
        #sidebar.active {
            margin-left: -250px;
        }
        
        #content.active {
            width: 100%;
        }
        
        /* Media query for mobile */
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                width: 100%;
            }
            #content.active {
                width: calc(100% - 250px);
            }
            #sidebarCollapse span {
                display: none;
            }
        }
        
        /* Styles spécifiques au thème sombre gérés via classe sur le body */
        body.dark-mode {
            background-color: #121212;
            color: #e0e0e0;
        }
        
        body.dark-mode #sidebar {
            background: #1e1e1e;
        }
        
        body.dark-mode #sidebar .sidebar-header {
            background: #121212;
        }
        
        body.dark-mode #content .navbar {
            background: #1e1e1e;
            color: #e0e0e0;
        }
        
        body.dark-mode .card {
            background-color: #2d2d2d;
            color: #e0e0e0;
        }
        
        body.dark-mode .card-header {
            background-color: #1e1e1e;
            border-bottom: 1px solid #333;
        }
    </style>
    
    @stack('styles')
</head>
<body class="{{ session('theme') === 'dark' ? 'dark-mode' : '' }}">
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h5 class="mb-0">LivresGourmands</h5>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="{{ request()->routeIs('books.*') ? 'active' : '' }}">
                    <a href="{{ route('books.index') }}">
                        <i class="fas fa-book"></i> Livres
                    </a>
                </li>
                <li class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <a href="{{ route('categories.index') }}">
                        <i class="fas fa-folder"></i> Catégories
                    </a>
                </li>
                <li class="{{ request()->routeIs('comments.*') ? 'active' : '' }}">
                    <a href="{{ route('comments.index') }}">
                        <i class="fas fa-comments"></i> Commentaires
                    </a>
                </li>
                <li class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <a href="{{ route('sales.index') }}">
                        <i class="fas fa-chart-bar"></i> Ventes
                    </a>
                </li>
                @can('is-admin')
                <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}">
                        <i class="fas fa-users"></i> Utilisateurs
                    </a>
                </li>
                <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}">
                        <i class="fas fa-user-tag"></i> Rôles
                    </a>
                </li>
                @endcan
            </ul>

            <ul class="list-unstyled">
                <li>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-primary">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="ms-auto d-flex align-items-center">
                        <!-- Affichage des alertes -->
                        <div class="dropdown me-3">
                            <a href="#" class="nav-link position-relative" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                @if(Auth::user()->hasUnreadAlerts())
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ Auth::user()->unreadAlertsCount() }}
                                        <span class="visually-hidden">Alertes non lues</span>
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown" style="width: 300px;">
                                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                                    <h6 class="mb-0">Notifications</h6>
                                    <a href="{{ route('alerts.index') }}" class="text-decoration-none">Voir tout</a>
                                </div>
                                
                                @php
                                    $alerts = Auth::user()->alerts()->latest()->take(5)->get();
                                @endphp
                                
                                @if($alerts->isEmpty())
                                    <div class="dropdown-item text-center text-muted py-3">
                                        <i class="fas fa-bell-slash me-2"></i>Aucune notification
                                    </div>
                                @else
                                    @foreach($alerts as $alert)
                                        <div class="dropdown-item d-flex {{ is_null($alert->read_at) ? 'bg-light' : '' }}">
                                            <div class="me-2">
                                                @if($alert->type == 'danger')
                                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                                @elseif($alert->type == 'warning')
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                @else
                                                    <i class="fas fa-info-circle text-info"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="{{ is_null($alert->read_at) ? 'fw-bold' : '' }}">{{ Str::limit($alert->message, 50) }}</div>
                                                <small class="text-muted">{{ $alert->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if(is_null($alert->read_at))
                                                <form action="{{ route('alerts.read', $alert) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0">
                                                        <i class="fas fa-check text-muted"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @endforeach
                                @endif
                                
                                <div class="dropdown-item text-center border-top">
                                    <a href="{{ route('alerts.index') }}" class="btn btn-link text-decoration-none">Voir toutes les alertes</a>
                                </div>
                            </div>
                        </div>

                        <!-- Avatar utilisateur -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="me-2">{{ Auth::user()->name }}</span>
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" class="rounded-circle" width="40" height="40">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user-cog me-2"></i>Mon profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mx-4" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);

            // Appliquer le thème automatique si configuré
            if ("{{ session('theme') }}" === 'auto') {
                const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
                if (prefersDarkScheme.matches) {
                    document.body.classList.add('dark-mode');
                } else {
                    document.body.classList.remove('dark-mode');
                }
                
                // Écouter les changements de préférence de thème du système
                prefersDarkScheme.addEventListener('change', (e) => {
                    if (e.matches) {
                        document.body.classList.add('dark-mode');
                    } else {
                        document.body.classList.remove('dark-mode');
                    }
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
