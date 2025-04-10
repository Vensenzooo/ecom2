@extends('layouts.client')

@section('title', 'Confirmer l\'adresse de livraison')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Confirmer l'adresse de livraison</h1>
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
                    <h5 class="mb-0">Votre adresse de livraison</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.orders.confirm-address.store', $order) }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="street" class="form-label">Rue et numéro</label>
                            <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street', $order->address->street ?? '') }}" required>
                            @error('street')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="postal_code" class="form-label">Code postal</label>
                            <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code', $order->address->postal_code ?? '') }}" required>
                            @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="city" class="form-label">Ville</label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $order->address->city ?? '') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="country" class="form-label">Pays</label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $order->address->country ?? 'France') }}" required>
                            @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle me-2"></i>Confirmer cette adresse
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
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Résumé de la commande</h5>
                </div>
                <div class="card-body">
                    <p><strong>Date de la commande:</strong><br> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Numéro de commande:</strong><br> #{{ $order->id }}</p>
                    <p><strong>Montant total:</strong><br> {{ number_format($order->montant_total, 2) }} €</p>
                    
                    <h6 class="mt-4">Produits commandés:</h6>
                    <ul class="list-group mb-3">
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
