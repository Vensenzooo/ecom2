@extends('layouts.app')

@section('title', 'Détails du remboursement')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Demande de remboursement #{{ $order->id }}</h1>
            <p class="text-muted">Détails de la demande de remboursement</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.refunds.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Informations générales -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informations générales</h5>
                    <span class="badge {{ $order->statut === 'refund_requested' ? 'bg-warning' : 'bg-success' }}">
                        {{ $order->statut === 'refund_requested' ? 'En attente' : 'Remboursé' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Client:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">{{ $order->user->name }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Email:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">{{ $order->user->email }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Date de commande:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Demande de remboursement:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">
                                @if($order->refund_requested_at)
                                    {{ $order->refund_requested_at->format('d/m/Y H:i') }}
                                @else
                                    Non spécifiée
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($order->refunded_at)
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Remboursé le:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">{{ $order->refunded_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Mode de paiement:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext">
                                <span class="badge {{ $order->mode_paiement === 'paypal' ? 'bg-primary' : 'bg-info' }}">
                                    {{ $order->mode_paiement === 'paypal' ? 'PayPal' : 'Tokens' }}
                                </span>
                            </p>
                        </div>
                    </div>
                    @if($order->mode_paiement === 'tokens')
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Tokens utilisés:</label>
                        <div class="col-sm-8">
                            @php
                                // Make sure details_paiement is properly decoded from JSON
                                $details = is_string($order->details_paiement) ? json_decode($order->details_paiement, true) : $order->details_paiement;
                                $tokens = $details['tokens_used'] ?? null;
                            @endphp
                            <p class="form-control-plaintext">
                                @if(is_numeric($tokens))
                                    {{ number_format((int)$tokens) }} tokens
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Montant total:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext fw-bold">{{ number_format($order->montant_total, 2) }} €</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Raison du remboursement -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Raison du remboursement</h5>
                </div>
                <div class="card-body">
                    <div class="border rounded p-3 bg-light mb-4">
                        <p>{{ $order->refund_reason }}</p>
                    </div>
                    
                    @if($order->statut === 'refund_requested')
                        <div class="d-flex gap-2 mt-4">
                            @if($order->mode_paiement === 'paypal')
                                @php
                                    $paypalData = is_string($order->details_paiement) ? json_decode($order->details_paiement, true) : $order->details_paiement;
                                    $hasPaypalData = isset($paypalData['paypal_id']) || isset($paypalData['simulated']);
                                @endphp
                                
                                @if(!$hasPaypalData)
                                    <div class="alert alert-warning w-100">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Données PayPal manquantes pour ce remboursement. Vous pouvez effectuer un remboursement en tokens.
                                    </div>
                                    
                                    <form action="{{ route('admin.refunds.approve', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="refund_with_tokens" value="1">
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Êtes-vous sûr de vouloir rembourser en tokens? {{ ceil($order->montant_total * 100 * 3) }} tokens seront ajoutés au compte client.')">
                                            <i class="fas fa-coins me-1"></i> Rembourser en tokens ({{ ceil($order->montant_total * 100 * 3) }} tokens)
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.refunds.approve', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir approuver ce remboursement?')">
                                            <i class="fas fa-check me-1"></i> Approuver le remboursement
                                        </button>
                                    </form>
                                @endif
                            @else
                                <form action="{{ route('admin.refunds.approve', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir approuver ce remboursement?')">
                                        <i class="fas fa-check me-1"></i> Approuver le remboursement
                                    </button>
                                </form>
                            @endif
                            
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-1"></i> Rejeter
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Articles de la commande -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Articles commandés</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    @if($order->mode_paiement === 'tokens')
                                        <th class="text-center">Valeur en tokens</th>
                                    @else
                                        <th class="text-center">Prix unitaire</th>
                                    @endif
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalTokens = 0;
                                @endphp
                                @foreach($order->items ?? [] as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->book->image_url)
                                                <img src="{{ $item->book->image_url }}" alt="{{ $item->book->titre }}" class="img-thumbnail" style="width: 60px;">
                                            @endif
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $item->book->titre }}</h6>
                                                <small class="text-muted">{{ $item->book->auteur }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    @php
                                        // Pour les paiements en tokens, convertir le prix unitaire en tokens (3 tokens = 0.01€)
                                        $tokenUnitValue = $order->mode_paiement === 'tokens' ? ceil($item->prix_unitaire * 100 * 3) : $item->prix_unitaire;
                                        $tokenTotalValue = $order->mode_paiement === 'tokens' ? $tokenUnitValue * $item->quantite : $item->prix_unitaire * $item->quantite;
                                        if($order->mode_paiement === 'tokens') {
                                            $totalTokens += $tokenTotalValue;
                                        }
                                    @endphp
                                    <td class="text-center">
                                        @if($order->mode_paiement === 'tokens')
                                            {{ number_format($tokenUnitValue) }} tokens
                                        @else
                                            {{ number_format($item->prix_unitaire, 2) }} €
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantite }}</td>
                                    <td class="text-end">
                                        @if($order->mode_paiement === 'tokens')
                                            {{ number_format($tokenTotalValue) }} tokens
                                        @else
                                            {{ number_format($item->prix_unitaire * $item->quantite, 2) }} €
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">
                                        @if($order->mode_paiement === 'tokens')
                                            @php
                                                if($totalTokens == 0) {
                                                    $details = is_string($order->details_paiement) ? json_decode($order->details_paiement, true) : $order->details_paiement;
                                                    $tokensUsed = $details['tokens_used'] ?? 0;
                                                    $totalTokens = $tokensUsed;
                                                }
                                            @endphp
                                            {{ number_format($totalTokens) }} tokens
                                        @else
                                            {{ number_format($order->montant_total, 2) }} €
                                        @endif
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Adresse de livraison si disponible -->
    @if($order->address)
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Adresse de livraison</h5>
                </div>
                <div class="card-body">
                    <address>
                        {{ $order->address->street }}<br>
                        {{ $order->address->postal_code }} {{ $order->address->city }}<br>
                        {{ $order->address->country }}
                    </address>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Refuser la demande de remboursement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.refunds.reject', $order) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Raison du refus</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                        <div class="form-text">Cette raison sera communiquée au client.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Refuser le remboursement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
