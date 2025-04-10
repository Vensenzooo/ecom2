@extends('layouts.client')

@section('title', 'Historique des Étoiles')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Historique des Étoiles</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('client.tokens.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Retour aux Étoiles
            </a>
        </div>
    </div>

    <!-- Solde actuel -->
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
            </div>
        </div>
    </div>

    <!-- Navigation par onglets -->
    <ul class="nav nav-tabs mb-4" id="historyTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button" role="tab">
                <i class="fas fa-exchange-alt me-1"></i> Transactions
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="generated-tab" data-bs-toggle="tab" data-bs-target="#generated" type="button" role="tab">
                <i class="fas fa-gift me-1"></i> Codes générés
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="used-tab" data-bs-toggle="tab" data-bs-target="#used" type="button" role="tab">
                <i class="fas fa-check-circle me-1"></i> Codes utilisés
            </button>
        </li>
    </ul>
    
    <!-- Contenu des onglets -->
    <div class="tab-content" id="historyTabContent">
        <!-- Transactions -->
        <div class="tab-pane fade show active" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Achats d'étoiles</h5>
                </div>
                <div class="card-body">
                    @if(isset($purchases) && $purchases->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Référence</th>
                                        <th>Étoiles</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchases as $purchase)
                                    <tr>
                                        <td>{{ Carbon\Carbon::parse($purchase->created_at)->format('d/m/Y H:i') }}</td>
                                        <td><code>{{ $purchase->transaction_id }}</code></td>
                                        <td>{{ number_format($purchase->tokens) }}</td>
                                        <td>{{ number_format($purchase->amount, 2) }} €</td>
                                        <td>
                                            <span class="badge bg-success">{{ $purchase->status }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Vous n'avez pas encore acheté d'étoiles.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Codes générés -->
        <div class="tab-pane fade" id="generated" role="tabpanel" aria-labelledby="generated-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Codes de réduction générés</h5>
                </div>
                <div class="card-body">
                    @if(isset($generatedCodes) && $generatedCodes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Code</th>
                                        <th>Réduction</th>
                                        <th>Destinataire</th>
                                        <th>État</th>
                                        <th>Expire le</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($generatedCodes as $code)
                                    <tr>
                                        <td>{{ $code->created_at->format('d/m/Y') }}</td>
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
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Vous n'avez pas encore généré de codes de réduction.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Codes utilisés -->
        <div class="tab-pane fade" id="used" role="tabpanel" aria-labelledby="used-tab">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Codes de réduction utilisés</h5>
                </div>
                <div class="card-body">
                    @if(isset($usedCodes) && $usedCodes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date d'utilisation</th>
                                        <th>Code</th>
                                        <th>Réduction</th>
                                        <th>Créé par</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($usedCodes as $code)
                                    <tr>
                                        <td>{{ $code->used_at->format('d/m/Y H:i') }}</td>
                                        <td><code>{{ $code->code }}</code></td>
                                        <td>{{ $code->discount_percentage }}%</td>
                                        <td>
                                            @if($code->created_by == $user->id)
                                                Vous-même
                                            @else
                                                @if($code->creator)
                                                    {{ $code->creator->name }}
                                                @else
                                                    Utilisateur inconnu
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> Vous n'avez pas encore utilisé de codes de réduction.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4">
        <h3>Comment obtenir plus d'étoiles ?</h3>
        <div class="row mt-3">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5><i class="fas fa-shopping-cart text-primary me-2"></i> Achats</h5>
                        <p class="text-muted">Achetez des livres pour gagner des étoiles sur chaque commande.</p>
                        <a href="{{ route('client.catalog') }}" class="btn btn-outline-primary">Explorer le catalogue</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5><i class="fas fa-comment text-success me-2"></i> Commentaires</h5>
                        <p class="text-muted">Laissez des commentaires sur les livres pour gagner des étoiles supplémentaires.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h5><i class="fas fa-shopping-basket text-warning me-2"></i> Acheter des étoiles</h5>
                        <p class="text-muted">Achetez directement des packs d'étoiles pour obtenir des réductions.</p>
                        <a href="{{ route('client.tokens.buy') }}" class="btn btn-outline-success">Acheter des étoiles</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activer l'onglet approprié si spécifié dans l'URL 
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            const triggerEl = document.querySelector('#historyTab button[data-bs-target="#' + tab + '"]');
            if (triggerEl) {
                const tabTrigger = new bootstrap.Tab(triggerEl);
                tabTrigger.show();
            }
        }
    });
</script>
@endpush
