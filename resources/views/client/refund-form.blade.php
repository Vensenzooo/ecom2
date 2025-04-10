@extends('layouts.client')

@section('title', 'Demande de remboursement')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Demande de remboursement</h1>
            <p class="text-muted">Commande #{{ $order->id }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('client.orders.details', $order) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Retour aux détails
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Formulaire de demande</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        @if($order->mode_paiement === 'tokens')
                            <strong>Information:</strong> Pour les achats par tokens, le remboursement sera automatiquement crédité sur votre compte.
                        @else
                            <strong>Information:</strong> Pour les achats par PayPal, le remboursement sera traité après examen par notre équipe.
                        @endif
                    </div>
                    
                    <form method="POST" action="{{ route('client.orders.refund.request', $order) }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="reason" class="form-label">Motif de remboursement</label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason" name="reason" rows="5" placeholder="Veuillez expliquer pourquoi vous souhaitez être remboursé..." required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="confirm" required>
                            <label class="form-check-label" for="confirm">
                                Je confirme que je souhaite être remboursé pour cette commande
                            </label>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-undo me-2"></i>Demander le remboursement
                            </button>
                            <a href="{{ route('client.orders.details', $order) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Résumé de la commande</h5>
                </div>
                <div class="card-body">
                    <p><strong>Date de la commande:</strong><br> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Numéro de commande:</strong><br> #{{ $order->id }}</p>
                    <p><strong>Montant total:</strong><br> {{ number_format($order->montant_total, 2) }} €</p>
                    <p>
                        <strong>Mode de paiement:</strong><br>
                        @if($order->mode_paiement === 'tokens')
                            <span class="badge bg-info">Tokens</span>
                            @if($order->details_paiement)
                                <br>
                                @php
                                    $details = json_decode($order->details_paiement, true);
                                    $tokens = $details['tokens_used'] ?? 'N/A';
                                @endphp
                                <small class="text-muted">{{ $tokens }} tokens utilisés</small>
                            @endif
                        @else
                            <span class="badge bg-success">PayPal</span>
                        @endif
                    </p>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Produits commandés</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $item->book->titre }}
                                <span class="badge bg-primary rounded-pill">{{ $item->quantité }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
