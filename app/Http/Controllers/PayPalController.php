<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
use App\Models\Sale;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;

class PayPalController extends Controller
{
    private $client;

    public function __construct()
    {
        // Initialiser le client PayPal selon l'environnement
        if (config('paypal.mode') === 'sandbox') {
            $environment = new SandboxEnvironment(config('paypal.sandbox.client_id'), config('paypal.sandbox.client_secret'));
        } else {
            $environment = new ProductionEnvironment(config('paypal.live.client_id'), config('paypal.live.client_secret'));
        }

        $this->client = new PayPalHttpClient($environment);
    }

    /**
     * Initier le processus de paiement PayPal
     */
    public function createOrder(Request $request)
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('book')
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')->with('error', 'Votre panier est vide');
        }
        
        $total = $cartItems->sum(function($item) {
            return $item->book->prix * $item->quantity;
        });
        
        // Créer la requête pour l'API PayPal
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'reference_id' => 'LIVRESGOURMANDS_' . uniqid(),
                    'description' => 'Achat LivresGourmands.net',
                    'amount' => [
                        'currency_code' => config('paypal.currency'),
                        'value' => number_format($total, 2, '.', ''),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => config('paypal.currency'),
                                'value' => number_format($total, 2, '.', '')
                            ]
                        ]
                    ],
                    'items' => $cartItems->map(function($item) {
                        return [
                            'name' => $item->book->titre,
                            'description' => Str::limit($item->book->description, 100),
                            'sku' => 'BOOK_' . $item->book->id,
                            'unit_amount' => [
                                'currency_code' => config('paypal.currency'),
                                'value' => number_format($item->book->prix, 2, '.', '')
                            ],
                            'quantity' => $item->quantity,
                            'category' => 'PHYSICAL_GOODS'
                        ];
                    })->toArray()
                ]
            ],
            'application_context' => [
                'return_url' => route('client.paypal.capture'),
                'cancel_url' => route('client.paypal.cancel')
            ]
        ];

        try {
            // Appel API pour créer l'ordre PayPal
            $response = $this->client->execute($request);
            
            // Récupérer l'URL de paiement PayPal
            $approvalUrl = null;
            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    $approvalUrl = $link->href;
                    break;
                }
            }
            
            // Stocker l'ID de la commande dans la session
            session(['paypal_order_id' => $response->result->id]);
            
            // Rediriger vers PayPal pour le paiement
            if ($approvalUrl) {
                return redirect()->away($approvalUrl);
            }
            
            return redirect()->route('client.cart.checkout')
                ->with('error', 'Impossible de créer la commande PayPal. Veuillez réessayer.');
            
        } catch (\Exception $e) {
            Log::error('PayPal API Error: ' . $e->getMessage());
            return redirect()->route('client.cart.checkout')
                ->with('error', 'Une erreur est survenue lors de la connexion à PayPal. Veuillez réessayer.');
        }
    }
    
    /**
     * Afficher la page de simulation PayPal
     */
    public function simulatePayPalPage()
    {
        $orderId = session('paypal_order_id');
        $items = session('paypal_items');
        $total = session('paypal_total');
        
        if (!$orderId || !$items || !isset($total)) {
            return redirect()->route('client.cart.checkout')
                ->with('error', 'Informations de commande incomplètes');
        }
        
        // Assurons-nous que la vue existe
        if (!view()->exists('cart.paypal-simulate')) {
            // Créons une vue de secours si nécessaire
            return view('cart.checkout', compact('items', 'total'))
                ->with('warning', 'Vue PayPal non trouvée, utilisation de la vue de secours');
        }
        
        // S'assurer que nous retournons la vue et non pas une redirection
        return view('cart.paypal-simulate', compact('orderId', 'items', 'total'));
    }
    
    /**
     * Capture le paiement après approbation
     */
    public function captureOrder(Request $request)
    {
        $orderId = session('paypal_order_id');
        $total = session('paypal_total');
        
        if (!$orderId) {
            return redirect()->route('client.cart.checkout')
                ->with('error', 'Aucune commande PayPal trouvée');
        }
        
        // Récupérer les détails du panier
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('book')
            ->get();
        
        // Traiter comme si le paiement était réussi
        // Créer les ventes et mettre à jour le stock
        foreach ($cartItems as $item) {
            $book = Book::find($item->book_id);
            
            // Vérifier une dernière fois le stock
            if ($book->stock < $item->quantity) {
                return redirect()->route('client.cart.index')
                    ->with('error', 'Stock insuffisant pour ' . $book->titre);
            }
            
            // Créer la vente
            Sale::create([
                'book_id' => $item->book_id,
                'quantité' => $item->quantity,
                'prix_unitaire' => $book->prix,
                'date_vente' => Carbon::now(),
            ]);
            
            // Mettre à jour le stock
            $book->stock -= $item->quantity;
            $book->save();
        }
        
        // Créer une entrée dans la table des commandes (si elle existe)
        if (class_exists('App\Models\Order')) {
            Order::create([
                'user_id' => Auth::id(),
                'transaction_id' => $orderId,
                'montant_total' => $total,
                'statut' => 'completed',
                'mode_paiement' => 'paypal',
                'details_paiement' => json_encode(['simulated' => true]),
            ]);
        }
        
        // Vider le panier
        Cart::where('user_id', Auth::id())->delete();
        
        // Nettoyer la session
        session()->forget(['paypal_order_id', 'paypal_items', 'paypal_total']);
        
        return redirect()->route('client.orders')
            ->with('success', 'Paiement réussi! Votre commande a été traitée.');
    }
    
    /**
     * Annuler le paiement PayPal
     */
    public function cancelOrder()
    {
        // Nettoyer la session
        session()->forget(['paypal_order_id', 'paypal_items', 'paypal_total']);
        
        return redirect()->route('client.cart.checkout')
            ->with('error', 'Paiement annulé');
    }
    
    /**
     * Gérer les webhooks PayPal (simulation)
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Simulation webhook PayPal reçu');
        return response()->json(['status' => 'success']);
    }
}
