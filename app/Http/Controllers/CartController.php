<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
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
     * Passer à la page de paiement
     */
    public function checkout()
    {
        // Get cart items
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)
            ->with('book')
            ->get();
        
        // Calculate subtotal
        $subtotal = $cartItems->sum(function($item) {
            return $item->book->prix * $item->quantity;
        });
        
        // Apply the discount if applicable
        $total = $subtotal;
        if (session('discount_percentage')) {
            $discountAmount = $subtotal * (session('discount_percentage') / 100);
            $total = $subtotal - $discountAmount;
        }
        
        // Store the cart items and total in the session for PayPal
        session([
            'paypal_items' => $cartItems,
            'paypal_total' => $total,
            'paypal_subtotal' => $subtotal
        ]);
        
        return view('cart.checkout', compact('cartItems', 'subtotal', 'total'));
    }
}
