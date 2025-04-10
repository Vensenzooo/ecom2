<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Simulation PayPal - LivresGourmands</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f7fa;
            padding-top: 40px;
        }
        .paypal-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 6px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .paypal-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .paypal-logo {
            height: 60px;
            margin-bottom: 15px;
        }
        .paypal-btn {
            background-color: #0070ba;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
        }
        .paypal-btn:hover {
            background-color: #005ea6;
            color: white;
        }
    </style>
</head>
<body>
    <div class="paypal-container">
        <div class="paypal-header">
            <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/PP_logo_h_200x51.png" alt="PayPal Logo" class="paypal-logo">
            <h4>Récapitulatif de votre commande</h4>
            <p class="text-muted">{{ $orderId }}</p>
        </div>
        
        <div class="mb-4">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th class="text-center">Qté</th>
                            <th class="text-end">Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td class="text-center">{{ $item['quantity'] }}</td>
                                <td class="text-end">{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="2" class="text-end">Total</th>
                            <th class="text-end">{{ number_format($total, 2) }} €</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
            <form action="{{ route('client.paypal.cancel') }}" method="GET">
                <button type="submit" class="btn btn-outline-secondary">Annuler</button>
            </form>
            
            <form action="{{ route('client.paypal.capture') }}" method="POST">
                @csrf
                <button type="submit" class="paypal-btn">Payer {{ number_format($total, 2) }} €</button>
            </form>
        </div>
        
        <div class="text-center mt-5">
            <small class="text-muted">
                Ceci est une simulation de paiement PayPal pour les besoins du développement.
                <br>Aucune transaction réelle n'est effectuée.
            </small>
        </div>
    </div>
</body>
</html>
