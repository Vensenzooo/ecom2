@extends('layouts.client')

@section('title', 'Mes Étoiles et Réductions')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mes Étoiles et Réductions</h1>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group">
                <a href="{{ route('client.tokens.buy') }}" class="btn btn-success me-2">
                    <i class="fas fa-shopping-cart me-1"></i> Acheter des étoiles
                </a>
                <a href="{{ route('client.tokens.claim') }}" class="btn btn-primary">
                    <i class="fas fa-gift me-1"></i> Réclamer un code
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Affichage du solde de tokens -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-star text-warning fa-2x"></i>
                </div>
                <div>
                    <h5 class="mb-1">Votre solde d'étoiles</h5>
                    <h2 class="mb-0 fw-bold">{{ number_format($user->tokens) }}</h2>
                </div>
                <div class="ms-auto">
                    <a href="{{ route('client.tokens.history') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-history me-1"></i> Historique
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Si une réduction active est disponible dans la session -->
    @if(session('discount_code'))
    <div class="alert alert-success">
        <div class="d-flex align-items-center">
            <div>
                <h5 class="alert-heading"><i class="fas fa-check-circle me-2"></i> Réduction active</h5>
                <p class="mb-0">Vous bénéficiez d'une réduction de {{ session('discount_percentage') }}% avec le code <strong>{{ session('discount_code') }}</strong></p>
            </div>
            <div class="ms-auto">
                <a href="{{ route('client.catalog') }}" class="btn btn-success">
                    <i class="fas fa-shopping-basket me-1"></i> Acheter maintenant
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Réductions disponibles -->
    <div class="row mb-4">
        <div class="col-12">
            <h3>Réductions disponibles</h3>
            <p class="text-muted">Utilisez vos étoiles pour obtenir des réductions sur vos achats.</p>
        </div>
        
        <!-- Réduction 10% -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Réduction 10%</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-2">
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">1000 étoiles</h6>
                        </div>
                    </div>
                    <p>Obtenez une réduction de 10% sur votre prochain achat.</p>
                    <form action="{{ route('client.tokens.use') }}" method="POST">
                        @csrf
                        <input type="hidden" name="discount_type" value="discount_10">
                        <button type="submit" class="btn btn-outline-primary w-100" {{ $discounts['discount_10']['available'] ? '' : 'disabled' }}>
                            {{ $discounts['discount_10']['available'] ? 'Utiliser maintenant' : 'Étoiles insuffisantes' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Réduction 25% -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Réduction 25%</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-2">
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">2000 étoiles</h6>
                        </div>
                    </div>
                    <p>Obtenez une réduction de 25% sur votre prochain achat.</p>
                    <form action="{{ route('client.tokens.use') }}" method="POST">
                        @csrf
                        <input type="hidden" name="discount_type" value="discount_25">
                        <button type="submit" class="btn btn-outline-success w-100" {{ $discounts['discount_25']['available'] ? '' : 'disabled' }}>
                            {{ $discounts['discount_25']['available'] ? 'Utiliser maintenant' : 'Étoiles insuffisantes' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Réduction 50% pour un ami -->
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Cadeau pour un ami</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-2">
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <div>
                            <h6 class="mb-0">5000 étoiles</h6>
                        </div>
                    </div>
                    <p>Offrez une réduction de 50% à un ami de votre choix.</p>
                    <form action="{{ route('client.tokens.use') }}" method="POST">
                        @csrf
                        <input type="hidden" name="discount_type" value="discount_50_friend">
                        
                        @if($discounts['discount_50_friend']['available'])
                            <div class="mb-3">
                                <label for="recipient_email" class="form-label">Email de votre ami</label>
                                <input type="email" id="recipient_email" name="recipient_email" class="form-control" required>
                            </div>
                        @endif
                        
                        <button type="submit" class="btn btn-outline-warning w-100" {{ $discounts['discount_50_friend']['available'] ? '' : 'disabled' }}>
                            {{ $discounts['discount_50_friend']['available'] ? 'Offrir maintenant' : 'Étoiles insuffisantes' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mes codes générés -->
    @if(isset($generatedCodes) && $generatedCodes->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Mes codes générés récemment</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Réduction</th>
                            <th>Destinataire</th>
                            <th>État</th>
                            <th>Date d'expiration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($generatedCodes->take(5) as $code)
                        <tr>
                            <td><code>{{ $code->code }}</code></td>
                            <td>{{ $code->discount_percentage }}%</td>
                            <td>{{ $code->recipient_email ?? 'Vous-même' }}</td>
                            <td>
                                @if($code->used_at)
                                    <span class="badge bg-success">Utilisé</span>
                                @elseif($code->expires_at < now())
                                    <span class="badge bg-danger">Expiré</span>
                                @else
                                    <span class="badge bg-primary">Actif</span>
                                @endif
                            </td>
                            <td>{{ $code->expires_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($generatedCodes->count() > 5)
                <div class="text-center mt-2">
                    <a href="{{ route('client.tokens.history') }}" class="btn btn-link">Voir tous mes codes</a>
                </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Comment gagner plus d'étoiles -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Comment gagner plus d'étoiles ?</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-shopping-cart text-primary me-3"></i>
                    <div>
                        <h6 class="mb-0">Acheter des livres</h6>
                        <p class="mb-0 text-muted">Gagnez 100 étoiles pour chaque achat</p>
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-comment text-success me-3"></i>
                    <div>
                        <h6 class="mb-0">Laisser des commentaires</h6>
                        <p class="mb-0 text-muted">Gagnez 50 étoiles pour chaque commentaire approuvé</p>
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-user-plus text-warning me-3"></i>
                    <div>
                        <h6 class="mb-0">Inviter des amis</h6>
                        <p class="mb-0 text-muted">Gagnez 200 étoiles pour chaque ami qui s'inscrit avec votre code</p>
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fas fa-credit-card text-danger me-3"></i>
                    <div>
                        <h6 class="mb-0">Acheter des étoiles</h6>
                        <p class="mb-0 text-muted">Achetez directement des étoiles pour profiter de réductions plus importantes</p>
                        <a href="{{ route('client.tokens.buy') }}" class="btn btn-sm btn-success mt-2">
                            <i class="fas fa-shopping-cart me-1"></i> Acheter des étoiles
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
