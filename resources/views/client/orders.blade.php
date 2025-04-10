@extends('layouts.client')

@section('title', 'Mes commandes')

@push('styles')
<style>
    .order-card {
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    }
    .order-status {
        font-size: 0.85rem;
        padding: 5px 10px;
        border-radius: 20px;
    }
    .status-completed { background-color: #d4edda; color: #155724; }
    .status-refunded { background-color: #d1ecf1; color: #0c5460; }
    .status-refund_requested { background-color: #fff3cd; color: #856404; }
    .status-processing { background-color: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Mes commandes</h1>

    @if($orders->count() > 0)
        @foreach($orders as $order)
            <div class="card order-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold">Commande #{{ $order->id }}</span> | 
                        <span class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <span class="order-status status-{{ $order->statut }}">
                        @switch($order->statut)
                            @case('completed')
                                Complétée
                                @break
                            @case('refunded')
                                Remboursée
                                @break
                            @case('refund_requested')
                                Remboursement demandé
                                @break
                            @default
                                En traitement
                        @endswitch
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">Produits</h5>
                            <ul class="list-group list-group-flush">
                                @foreach($order->items as $item)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $item->book->titre }}
                                        <span class="badge bg-primary">Qtté: {{ $item->quantité }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Mode de paiement:</strong> 
                                @if($order->mode_paiement === 'tokens')
                                    <span class="badge bg-info">Tokens</span>
                                @else
                                    <span class="badge bg-success">PayPal</span>
                                @endif
                            </p>
                            <p><strong>Montant total:</strong> {{ number_format($order->montant_total, 2) }} €</p>
                            
                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('client.orders.details', $order->id) }}" class="btn btn-primary">
                                    <i class="fas fa-info-circle me-2"></i>Détails complets
                                </a>
                                
                                @if(!$order->address_confirmed && $order->statut !== 'refunded' && $order->statut !== 'refund_requested')
                                    <a href="{{ route('client.orders.confirm-address', $order->id) }}" class="btn btn-outline-success">
                                        <i class="fas fa-map-marker-alt me-2"></i>Confirmer l'adresse de livraison
                                    </a>
                                @endif
                                
                                @if($order->statut !== 'refunded' && $order->statut !== 'refund_requested')
                                    <a href="{{ route('client.orders.refund', $order->id) }}" class="btn btn-outline-danger">
                                        <i class="fas fa-undo me-2"></i>Demander un remboursement
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-box-open fa-3x mb-3"></i>
            <h3>Vous n'avez pas encore de commandes</h3>
            <p>Parcourez notre catalogue pour trouver des livres qui vous intéressent.</p>
            <a href="{{ route('client.catalog') }}" class="btn btn-primary mt-3">Découvrir notre catalogue</a>
        </div>
    @endif
</div>
@endsection
