@extends('layouts.client')

@section('title', 'Finaliser la commande')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Finaliser la commande</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('client.cart.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour au panier
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Résumé de la commande -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Résumé de votre commande</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($item->book->image_url)
                                                    <img src="{{ $item->book->image_url }}" alt="{{ $item->book->titre }}" class="img-thumbnail" style="width: 60px;">
                                                @else
                                                    <div class="bg-light text-center" style="width: 60px; height: 60px; line-height: 60px;">
                                                        <i class="fas fa-book text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $item->book->titre }}</h6>
                                                <small class="text-muted">{{ $item->book->auteur }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->book->prix * $item->quantity, 2) }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Options de paiement -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Résumé</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total:</span>
                        <strong>{{ number_format($subtotal, 2) }} €</strong>
                    </div>
                    
                    @if(session('discount_percentage'))
                    <div class="d-flex justify-content-between text-success mb-2">
                        <span>Réduction ({{ session('discount_percentage') }}%):</span>
                        <strong>-{{ number_format($subtotal * session('discount_percentage') / 100, 2) }} €</strong>
                    </div>
                    @endif
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between mb-4">
                        <span>Total:</span>
                        <strong>{{ number_format($total, 2) }} €</strong>
                    </div>
                    
                    <div class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="mb-2">Payer avec vos tokens</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tokens disponibles:</span>
                                    <strong>{{ number_format($userTokens) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Coût en tokens:</span>
                                    <strong>{{ number_format($tokenCost) }}</strong>
                                </div>
                                <form action="{{ route('client.cart.pay-with-tokens') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100" {{ $canUseTokens ? '' : 'disabled' }}>
                                        <i class="fas fa-star me-1"></i> Payer avec des tokens
                                    </button>
                                </form>
                                @if(!$canUseTokens)
                                    <div class="text-danger small mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Vous n'avez pas assez de tokens pour cet achat.
                                        <a href="{{ route('client.tokens.buy') }}">Acheter plus de tokens</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="mb-3">Payer avec PayPal</h6>
                        <form action="{{ route('client.paypal.create') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fab fa-paypal me-1"></i> Payer avec PayPal
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
