<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
use App\Models\User;
use App\Models\Sale;
use App\Models\Order;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Afficher le panier de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->with('book')
            ->get();
        
        // Calculate the subtotal (sum of all items price * quantity)
        $subtotal = $cartItems->sum(function($item) {
            return $item->book->prix * $item->quantity;
        });
        
        // Calculate total (potentially applying discounts)
        $total = $subtotal;
        
        // Apply discount if applicable
        if (session('discount_percentage')) {
            $discountAmount = $subtotal * (session('discount_percentage') / 100);
            $total = $subtotal - $discountAmount;
        }
        
        return view('cart.index', compact('cartItems', 'total', 'subtotal'));
    }
    
    /**
     * Ajouter un livre au panier
     */
    public function add(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $book = Book::findOrFail($request->book_id);
        
        // Vérifier le stock disponible
        if ($book->stock < $request->quantity) {
            return back()->with('error', 'Stock insuffisant. Seulement ' . $book->stock . ' disponibles.');
        }
        
        // Rechercher si l'article est déjà dans le panier
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('book_id', $request->book_id)
            ->first();
            
        if ($cartItem) {
            // Mettre à jour la quantité
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Créer un nouvel élément dans le panier
            Cart::create([
                'user_id' => Auth::id(),
                'book_id' => $request->book_id,
                'quantity' => $request->quantity,
            ]);
        }
        
        return redirect()->route('client.cart.index')->with('success', 'Livre ajouté au panier');
    }
    
    /**
     * Mettre à jour la quantité d'un article du panier
     */
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $book = Book::findOrFail($cart->book_id);
        
        // Vérifier le stock disponible
        if ($book->stock < $request->quantity) {
            return back()->with('error', 'Stock insuffisant. Seulement ' . $book->stock . ' disponibles.');
        }
        
        $cart->quantity = $request->quantity;
        $cart->save();
        
        return redirect()->route('client.cart.index')->with('success', 'Panier mis à jour');
    }
    
    /**
     * Supprimer un article du panier
     */
    public function remove(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('client.cart.index')->with('success', 'Article retiré du panier');
    }
    
    /**
     * Vider le panier
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();
        return redirect()->route('client.cart.index')->with('success', 'Panier vidé');
    }
    
    /**
     * Appliquer un code de réduction
     */
    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount_code' => 'required|string',
        ]);
        
        $code = $request->discount_code;
        
        // Chercher dans la table gift_codes
        $giftCode = \App\Models\GiftCode::where('code', $code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();
        
        if ($giftCode) {
            // Vérifier si le code est destiné à un utilisateur spécifique
            if ($giftCode->recipient_email && $giftCode->recipient_email !== Auth::user()->email) {
                return redirect()->route('client.cart.index')
                    ->with('error', 'Ce code est destiné à un autre utilisateur');
            }
            
            // Stocker les informations de réduction dans la session
            session([
                'discount_code' => $code,
                'discount_percentage' => $giftCode->discount_percentage,
                'discount_id' => $giftCode->id
            ]);
            
            return redirect()->route('client.cart.index')
                ->with('success', "Code de réduction appliqué! Vous bénéficiez d'une remise de {$giftCode->discount_percentage}%.");
        }
        
        return redirect()->route('client.cart.index')
            ->with('error', 'Code de réduction invalide ou expiré.');
    }

    /**
     * Supprimer un code de réduction
     */
    public function removeDiscount()
    {
        session()->forget(['discount_code', 'discount_percentage', 'discount_id']);
        
        return redirect()->route('client.cart.index')
            ->with('success', 'Code de réduction supprimé.');
    }

    /**
     * Afficher la page checkout
     */
    public function checkout()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('book')
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Votre panier est vide');
        }
        
        $total = $cartItems->sum(function($item) {
            return $item->book->prix * $item->quantity;
        });
        
        // Calculer la réduction si un code est appliqué
        $subtotal = $total;
        $discountPercentage = session('discount_percentage', 0);
        if ($discountPercentage > 0) {
            $total = $subtotal * (1 - $discountPercentage / 100);
        }
        
        // Tokens disponibles de l'utilisateur
        $userTokens = Auth::user()->tokens;
        
        // Calculer le coût en tokens (3 tokens = 0.01€)
        $tokenCost = ceil($total * 100 * 3); // 10000 tokens = 30€, donc 3 tokens = 0.01€
        $canUseTokens = $userTokens >= $tokenCost;
        
        return view('cart.checkout', compact('cartItems', 'total', 'subtotal', 'discountPercentage', 'userTokens', 'tokenCost', 'canUseTokens'));
    }

    /**
     * Traiter le paiement avec des tokens
     */
    public function payWithTokens()
    {
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('book')
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('client.cart.index')
                ->with('error', 'Votre panier est vide');
        }
        
        $total = $cartItems->sum(function($item) {
            return $item->book->prix * $item->quantity;
        });
        
        // Appliquer la réduction si un code est appliqué
        $discountPercentage = session('discount_percentage', 0);
        if ($discountPercentage > 0) {
            $total = $total * (1 - $discountPercentage / 100);
        }
        
        // Calculer le coût en tokens
        $tokenCost = ceil($total * 100 * 3); // 10000 tokens = 30€, donc 3 tokens = 0.01€
        
        $user = User::find(Auth::id());
        
        if ($user->tokens < $tokenCost) {
            return redirect()->route('client.cart.checkout')
                ->with('error', "Vous n'avez pas assez de tokens pour effectuer cet achat");
        }
        
        // Déduire les tokens de l'utilisateur
        $user->tokens -= $tokenCost;
        $user->save();
        
        // Créer la commande et traiter les achats
        foreach ($cartItems as $item) {
            $book = Book::find($item->book_id);
            
            // Vérifier le stock
            if ($book->stock < $item->quantity) {
                return redirect()->route('client.cart.checkout')
                    ->with('error', "Stock insuffisant pour {$book->titre}. Votre transaction n'a pas été traitée.");
            }
            
            // Créer la vente
            Sale::create([
                'book_id' => $item->book_id,
                'quantité' => $item->quantity,
                'prix_unitaire' => $book->prix,
                'date_vente' => now(),
                'payment_method' => 'tokens',
            ]);
            
            // Mettre à jour le stock
            $book->stock -= $item->quantity;
            $book->save();
        }
        
        // Créer une entrée dans la table des commandes
        if (class_exists('App\Models\Order')) {
            Order::create([
                'user_id' => Auth::id(),
                'transaction_id' => 'TOKEN-' . uniqid(),
                'montant_total' => $total,
                'statut' => 'completed',
                'mode_paiement' => 'tokens',
                'details_paiement' => json_encode(['tokens_used' => $tokenCost]),
            ]);
        }
        
        // Créer une alerte pour informer l'utilisateur
        Alert::create([
            'user_id' => Auth::id(),
            'message' => "Vous avez utilisé {$tokenCost} tokens pour acheter des livres",
            'type' => 'info',
            'created_by' => Auth::id(), // Ajouter le champ created_by
        ]);
        
        // Vider le panier
        Cart::where('user_id', Auth::id())->delete();
        
        // Nettoyer la session
        session()->forget(['discount_code', 'discount_percentage']);
        
        return redirect()->route('client.orders')
            ->with('success', "Paiement réussi avec {$tokenCost} tokens! Votre commande a été traitée.");
    }
}
