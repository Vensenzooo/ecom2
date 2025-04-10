<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Sale;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    /**
     * Afficher le tableau de bord client
     */
    public function dashboard()
    {
        // Récupérer les livres récemment ajoutés
        $latestBooks = Book::where('stock', '>', 0)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        
        return view('client.dashboard', compact('latestBooks'));
    }
    
    /**
     * Afficher le catalogue de livres pour les clients
     */
    public function catalog(Request $request)
    {
        $query = Book::with('category')->where('stock', '>', 0);
        
        // Filtrage par catégorie
        if ($request->has('category')) {
            $query->where('categorie_id', $request->category);
        }
        
        // Filtrage par niveau d'expertise
        if ($request->has('level')) {
            $query->where('niveau_expertise', $request->level);
        }
        
        // Recherche par titre ou auteur
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titre', 'like', "%{$search}%")
                  ->orWhere('auteur', 'like', "%{$search}%");
            });
        }
        
        // Tri
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('prix', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('prix', 'desc');
                    break;
                case 'title':
                    $query->orderBy('titre', 'asc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $books = $query->paginate(12);
        $categories = Category::all();
        
        return view('client.catalog', compact('books', 'categories'));
    }
    
    /**
     * Afficher les détails d'un livre pour les clients
     */
    public function bookDetails(Book $book)
    {
        $book->load(['category', 'comments' => function($query) {
            $query->where('statut', 'approuvé');
        }, 'comments.user']);
        
        $relatedBooks = Book::where('categorie_id', $book->categorie_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();
        
        return view('client.book_details', compact('book', 'relatedBooks'));
    }
    
    /**
     * Afficher les commandes de l'utilisateur
     */
    public function orders()
    {
        $user = Auth::user();
        
        // Récupérer toutes les commandes de l'utilisateur avec pagination
        $orders = Order::where('user_id', $user->id)
            ->with(['items.book', 'address'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('client.orders', compact('orders'));
    }

    /**
     * Afficher les détails d'une commande spécifique
     */
    public function orderDetails(Order $order)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('client.orders')
                ->with('error', 'Vous n\'avez pas accès à cette commande');
        }
        
        // Charger les relations nécessaires
        $order->load(['items.book', 'address']);
        
        return view('client.order-details', compact('order'));
    }

    /**
     * Afficher le formulaire pour confirmer l'adresse de livraison
     */
    public function confirmAddressForm(Order $order)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('client.orders')
                ->with('error', 'Vous n\'avez pas accès à cette commande');
        }
        
        // Vérifier que l'adresse n'a pas déjà été confirmée
        if ($order->address_confirmed) {
            return redirect()->route('client.orders.details', $order)
                ->with('info', 'L\'adresse a déjà été confirmée');
        }
        
        return view('client.confirm-address', compact('order'));
    }

    /**
     * Confirmer l'adresse de livraison
     */
    public function confirmAddress(Request $request, Order $order)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('client.orders')
                ->with('error', 'Vous n\'avez pas accès à cette commande');
        }
        
        $validated = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:255',
        ]);
        
        // Mettre à jour ou créer l'adresse
        if ($order->address) {
            $order->address->update($validated);
        } else {
            $address = new ShippingAddress($validated);
            $address->order_id = $order->id;
            $address->save();
        }
        
        // Marquer l'adresse comme confirmée
        $order->address_confirmed = true;
        $order->save();
        
        return redirect()->route('client.orders.details', $order)
            ->with('success', 'Adresse de livraison confirmée avec succès');
    }

    /**
     * Afficher le formulaire de demande de remboursement
     */
    public function refundForm(Order $order)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('client.orders')
                ->with('error', 'Vous n\'avez pas accès à cette commande');
        }
        
        // Vérifier que la commande n'a pas déjà été remboursée ou qu'une demande n'est pas déjà en cours
        if ($order->statut === 'refunded' || $order->statut === 'refund_requested') {
            return redirect()->route('client.orders.details', $order)
                ->with('error', 'Cette commande a déjà été remboursée ou une demande est en cours');
        }
        
        return view('client.refund-form', compact('order'));
    }

    /**
     * Traiter la demande de remboursement
     */
    public function requestRefund(Request $request, Order $order)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la commande
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('client.orders')
                ->with('error', 'Vous n\'avez pas accès à cette commande');
        }
        
        // Vérifier que la commande n'a pas déjà été remboursée ou qu'une demande n'est pas déjà en cours
        if ($order->statut === 'refunded' || $order->statut === 'refund_requested') {
            return redirect()->route('client.orders.details', $order)
                ->with('error', 'Cette commande a déjà été remboursée ou une demande est en cours');
        }
        
        $validated = $request->validate([
            'reason' => 'required|string|min:10|max:1000',
        ]);
        
        // Différencier le traitement selon le mode de paiement
        if ($order->mode_paiement === 'tokens') {
            // Remboursement automatique pour les tokens
            $userId = Auth::id();
            $user = User::find($userId);
            
            // Extraire le nombre de tokens utilisés des détails de paiement
            $paymentDetails = json_decode($order->details_paiement, true);
            $tokensUsed = $paymentDetails['tokens_used'] ?? 0;
            
            // Rembourser les tokens
            $user->tokens += $tokensUsed;
            $user->save();
            
            // Mettre à jour le statut de la commande
            $order->statut = 'refunded';
            $order->refund_reason = $validated['reason'];
            $order->refunded_at = now();
            $order->save();
            
            // Créer une alerte pour l'utilisateur
            Alert::create([
                'user_id' => $user->id,
                'created_by' => $user->id,
                'message' => "Remboursement effectué: {$tokensUsed} tokens ont été ajoutés à votre compte",
                'type' => 'info',  // Changed from 'success' to 'info'
            ]);
            
            return redirect()->route('client.orders.details', $order)
                ->with('success', 'Remboursement effectué avec succès. Vos tokens ont été crédités sur votre compte.');
        } else {
            // Pour PayPal ou autres modes de paiement, créer une demande de remboursement
            $order->statut = 'refund_requested';
            $order->refund_reason = $validated['reason'];
            $order->refund_requested_at = now();
            $order->save();
            
            // Créer une notification pour l'administrateur
            $admins = User::whereHas('roles', function($query) {
                $query->where('nom', 'admin');
            })->get();
            
            foreach ($admins as $admin) {
                Alert::create([
                    'user_id' => $admin->id,
                    'created_by' => Auth::id(),
                    'message' => "Nouvelle demande de remboursement pour la commande #{$order->id}",
                    'type' => 'warning',
                ]);
            }
            
            return redirect()->route('client.orders.details', $order)
                ->with('success', 'Votre demande de remboursement a été soumise et est en attente de validation par un administrateur.');
        }
    }

    /**
     * Ajouter un commentaire à un livre
     */
    public function addComment(Request $request, Book $book)
    {
        $request->validate([
            'contenu' => 'required|string|min:10',
        ]);
        
        Comment::create([
            'contenu' => $request->contenu,
            'statut' => 'en attente',
            'user_id' => Auth::id(),
            'book_id' => $book->id,
        ]);
        
        return redirect()->back()->with('success', 'Votre commentaire a été soumis et sera visible après modération.');
    }
}
