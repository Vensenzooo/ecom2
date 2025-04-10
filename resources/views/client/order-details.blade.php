@extends('layouts.client')

@section('title', 'Détails de la commande #' . $order->id)

@push('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #e9ecef;
    }
    .timeline-item {
        position: relative;
        padding-bottom: 20px;
    }
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: -30px;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #007bff;
    }
    .status-badge {
        font-size: 0.85rem;
        padding: 5px 15px;
        border-radius: 20px;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Commande #{{ $order->id }}</h1>
            <p class="text-muted">
                <strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                <span class="mx-3">|</span>
                <strong>Statut:</strong> 
                <span class="badge {{ $order->statut == 'completed' ? 'bg-success' : ($order->statut == 'refunded' ? 'bg-info' : 'bg-warning') }}">
                    {{ $order->statut == 'completed' ? 'Complétée' : ($order->statut == 'refunded' ? 'Remboursée' : 'En traitement') }}
                </span>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('client.orders') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Retour aux commandes
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Articles commandés</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Livre</th>
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
                                @foreach($order->items as $item)
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
                                        $tokenTotalValue = $order->mode_paiement === 'tokens' ? $tokenUnitValue * $item->quantité : $item->prix_unitaire * $item->quantité;
                                    @endphp
                                    <td class="text-center">
                                        @if($order->mode_paiement === 'tokens')
                                            {{ number_format($tokenUnitValue) }} tokens
                                        @else
                                            {{ number_format($item->prix_unitaire, 2) }} €
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantité }}</td>
                                    <td class="text-end">
                                        @if($order->mode_paiement === 'tokens')
                                            {{ number_format($tokenTotalValue) }} tokens
                                        @else
                                            {{ number_format($item->prix_unitaire * $item->quantité, 2) }} €
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
                                                $details = json_decode($order->details_paiement, true);
                                                $tokensUsed = $details['tokens_used'] ?? 0;
                                            @endphp
                                            {{ number_format($tokensUsed) }} tokens
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
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Détails de paiement</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Mode de paiement:</strong>
                        <span class="badge {{ $order->mode_paiement === 'paypal' ? 'bg-primary' : 'bg-info' }}">
                            {{ $order->mode_paiement === 'paypal' ? 'PayPal' : 'Tokens' }}
                        </span>
                    </p>
                    <p>
                        <strong>Transaction ID:</strong> {{ $order->transaction_id }}
                    </p>
                    <p>
                        <strong>Montant total:</strong> 
                        @if($order->mode_paiement === 'tokens')
                            @php
                                $details = json_decode($order->details_paiement, true);
                                $tokensUsed = $details['tokens_used'] ?? 0;
                            @endphp
                            {{ number_format($tokensUsed) }} tokens
                        @else
                            {{ number_format($order->montant_total, 2) }} €
                        @endif
                    </p>
                    
                    @if($order->statut === 'completed' && $order->created_at->diffInDays(now()) <= 30)
                    <div class="mt-4">
                        <a href="{{ route('client.orders.refund', $order) }}" class="btn btn-outline-danger">
                            <i class="fas fa-undo me-2"></i> Demander un remboursement
                        </a>
                    </div>
                    @endif
                    
                    @if($order->statut === 'refund_requested')
                    <div class="alert alert-warning mt-3">
                        <i class="fas fa-clock me-2"></i>
                        Une demande de remboursement est en cours de traitement.
                    </div>
                    @endif
                    
                    @if($order->statut === 'refunded')
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-check-circle me-2"></i>
                        Cette commande a été remboursée le {{ $order->refunded_at->format('d/m/Y') }}.
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Statut et historique -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Historique de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <h6>Commande passée</h6>
                            <p class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($order->address_confirmed)
                            <div class="timeline-item">
                                <h6>Adresse confirmée</h6>
                                <p class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($order->statut === 'refund_requested')
                            <div class="timeline-item">
                                <h6>Remboursement demandé</h6>
                                <p class="text-muted">{{ $order->refund_requested_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                        
                        @if($order->statut === 'refunded')
                            <div class="timeline-item">
                                <h6>Remboursement effectué</h6>
                                <p class="text-muted">{{ $order->refunded_at->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions disponibles -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(!$order->address_confirmed && $order->statut !== 'refunded' && $order->statut !== 'refund_requested')
                            <a href="{{ route('client.orders.confirm-address', $order->id) }}" class="btn btn-success">
                                <i class="fas fa-map-marker-alt me-2"></i>Confirmer l'adresse
                            </a>
                        @endif

                        @if($order->statut !== 'refunded' && $order->statut !== 'refund_requested')
                            <a href="{{ route('client.orders.refund', $order->id) }}" class="btn btn-outline-danger">
                                <i class="fas fa-undo me-2"></i>Demander un remboursement
                            </a>
                        @endif
                        
                        <a href="{{ route('client.orders') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour à mes commandes
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Informations de remboursement si applicable -->
            @if($order->statut === 'refund_requested' || $order->statut === 'refunded')
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Informations de remboursement</h5>
                    </div>
                    <div class="card-body">
                        <h6>Raison du remboursement</h6>
                        <p>{{ $order->refund_reason }}</p>
                        
                        @if($order->statut === 'refund_requested')
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Votre demande de remboursement est en cours de traitement par nos équipes.
                            </div>
                        @endif
                        
                        @if($order->statut === 'refunded')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                Le remboursement a été effectué le {{ $order->refunded_at->format('d/m/Y') }}.
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
