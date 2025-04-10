@extends('layouts.client')

@section('title', 'Finaliser ma commande')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Finaliser ma commande</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('client.cart.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> Retour au panier
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="m-0">Récapitulatif de la commande</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Livre</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Prix</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    <tr>
                                        <td>
                                            <h6 class="mb-0">{{ $item->book->titre }}</h6>
                                            <small class="text-muted">{{ $item->book->auteur }}</small>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->book->prix * $item->quantity, 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold">{{ number_format($total, 2) }} €</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0">Méthode de paiement</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Choisissez votre mode de paiement:</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal" checked>
                            <label class="form-check-label" for="paypal">
                                <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" alt="PayPal" height="23">
                                PayPal
                            </label>
                        </div>
                    </div>
                    
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
@endsection
