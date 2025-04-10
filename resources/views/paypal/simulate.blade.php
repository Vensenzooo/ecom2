<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paiement PayPal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }
        .paypal-container {
            max-width: 500px;
            margin: 0 auto;
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .paypal-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        .paypal-logo {
            width: 150px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="paypal-container">
            <div class="paypal-header">
                <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg" alt="PayPal Logo" class="paypal-logo">
                <h3>Paiement PayPal</h3>
                <p class="text-muted">Commande #{{ $orderId }}</p>
            </div>
            
            <div class="mb-4">
                <h5>Résumé de la commande</h5>
                <table class="table table-sm">
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td class="text-center">{{ $item['quantity'] }}x</td>
                                <td class="text-end">{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">Total:</th>
                            <th class="text-end">{{ number_format($total, 2) }} €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('client.paypal.cancel') }}" method="GET">
                    <button type="submit" class="btn btn-outline-secondary">Annuler</button>
                </form>
                
                <form action="{{ route('client.paypal.capture') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
                </form>
            </div>
            
            <div class="text-center mt-4">
                <small class="text-muted">
                    Ceci est une simulation de paiement PayPal pour démonstration.
                    <br>Dans un environnement réel, vous seriez redirigé vers le site PayPal.
                </small>
            </div>
        </div>
    </div>
</body>
</html>
