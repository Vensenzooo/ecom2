@extends('layouts.client')

@section('title', 'PayPal - Paiement')

@push('styles')
<style>
    .paypal-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 2rem;
        background: linear-gradient(to bottom, #ffffff, #f8f8f8);
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        font-family: 'Helvetica Neue', Arial, sans-serif;
    }
    .paypal-header {
        text-align: center;
        margin-bottom: 2rem;
        border-bottom: 1px solid #eee;
        padding-bottom: 1.5rem;
    }
    .paypal-header img {
        height: 40px;
        margin-bottom: 1rem;
    }
    .paypal-header h2 {
        font-size: 1.5rem;
        font-weight: 500;
        color: #2c2e2f;
    }
    .order-details {
        background-color: white;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 1px solid #e7e7e7;
    }
    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        color: #2c2e2f;
    }
    .price-total {
        font-weight: bold;
        border-top: 1px solid #ddd;
        padding-top: 0.75rem;
        margin-top: 0.75rem;
        color: #0070ba;
        font-size: 1.2rem;
    }
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }
    .btn-paypal {
        background-color: #0070ba;
        color: white;
        border: none;
        padding: 12px 24px;
        font-weight: bold;
        transition: all 0.3s;
        border-radius: 25px;
    }
    .btn-paypal:hover {
        background-color: #005ea6;
        color: white;
    }
    .btn-cancel {
        background-color: #fff;
        color: #0070ba;
        border: 1px solid #0070ba;
        padding: 12px 24px;
        font-weight: normal;
        transition: all 0.3s;
        border-radius: 25px;
    }
    .btn-cancel:hover {
        background-color: #f5f5f5;
    }
    .secure-badges {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }
    .secure-badges img {
        margin: 0 10px;
        height: 35px;
    }
    .payment-method {
        background-color: #f5f7fa;
        border-radius: 5px;
        padding: 10px 15px;
        margin-top: 15px;
        display: flex;
        align-items: center;
    }
    .payment-method img {
        height: 24px;
        margin-right: 10px;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="paypal-container">
                <div class="paypal-header">
                    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg" alt="PayPal Logo">
                    <h2>Finaliser votre achat d'étoiles</h2>
                </div>

                <div class="order-details">
                    <h3 class="h5 mb-3">Détails de la commande</h3>

                    @if(isset($tokenPurchase) && isset($orderId))
                        <div class="price-row">
                            <span>Pack d'étoiles:</span>
                            <span>{{ $tokenPurchase['tokens'] }} étoiles</span>
                        </div>
                        <div class="price-row">
                            <span>Vendeur:</span>
                            <span>LivresGourmands</span>
                        </div>
                        <div class="price-total">
                            <span>Total à payer:</span>
                            <span>{{ number_format($tokenPurchase['amount'], 2) }} €</span>
                        </div>
                        <div class="payment-method">
                            <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal">
                            <span>Paiement via PayPal</span>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">Numéro de commande: {{ $orderId }}</small>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Information de commande non disponible. Veuillez retourner à la page précédente et réessayer.
                        </div>
                    @endif
                </div>

                <div class="action-buttons">
                    <form action="{{ route('client.tokens.paypal.capture') }}" method="POST" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-paypal w-100">Confirmer et payer {{ isset($tokenPurchase) ? number_format($tokenPurchase['amount'], 2) . ' €' : '' }}</button>
                    </form>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('client.tokens.paypal.cancel') }}" class="btn-cancel btn">Annuler et retourner</a>
                </div>

                <div class="secure-badges">
                    <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/cc-badges-ppmcvdam.png" alt="Credit Card Badges">
                </div>

                <div class="mt-4 text-center">
                    <small class="text-muted">Cette interface simule PayPal à des fins de démonstration seulement.<br>Aucun paiement réel n'est effectué.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
