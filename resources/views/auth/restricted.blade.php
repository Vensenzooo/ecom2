<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Compte restreint - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body {
            background-color: #f8d7da;
            font-family: 'figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .restricted-card {
            background-color: white;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        .restricted-card h1 {
            color: #721c24;
        }
        .restricted-icon {
            font-size: 5rem;
            color: #dc3545;
            animation: pulse 2s infinite;
        }
        .contact-badge {
            background-color: #dc3545;
            color: white;
            font-weight: bold;
            padding: 0.75rem;
            border-radius: 5px;
            margin-top: 1rem;
        }
        .reason-box {
            background-color: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            margin: 1rem 0;
        }
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="restricted-card text-center">
            <div class="mb-4">
                <i class="fas fa-ban restricted-icon"></i>
            </div>
            <h1 class="mb-3">Votre compte a été restreint</h1>
            <p class="lead mb-4">Nous sommes désolés, mais l'accès à votre compte a été temporairement suspendu.</p>
            
            <div class="reason-box">
                <h5>Motif de la restriction :</h5>
                <p class="mb-0">{{ $restrictionReason }}</p>
                <small class="text-muted">Restreint le : {{ $restrictedAt }}</small>
            </div>
            
            <p>Si vous pensez qu'il s'agit d'une erreur ou si vous souhaitez discuter de cette restriction, veuillez nous contacter immédiatement.</p>
            
            <div class="contact-badge">
                <i class="fas fa-phone me-2"></i> Appelez-nous immédiatement au : <strong>+15145761564</strong>
            </div>
            
            <div class="mt-4">
                <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Retour à la page de connexion
                </a>
            </div>
        </div>
    </div>
</body>
</html>
