<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\GiftCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TokenDiscountController extends Controller
{
    /**
     * Afficher la page des tokens et réductions
     */
    public function index()
    {
        // Utiliser le modèle User directement pour s'assurer que toutes les méthodes sont disponibles
        $user = User::find(Auth::id());
        
        // Calculer les réductions disponibles
        $discounts = [
            'discount_10' => [
                'tokens' => 1000,
                'percentage' => 10,
                'available' => $user->tokens >= 1000
            ],
            'discount_25' => [
                'tokens' => 2000,
                'percentage' => 25,
                'available' => $user->tokens >= 2000
            ],
            'discount_50_friend' => [
                'tokens' => 5000,
                'percentage' => 50,
                'available' => $user->tokens >= 5000
            ]
        ];
        
        // Récupérer les codes cadeaux générés par l'utilisateur
        $generatedCodes = GiftCode::where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Récupérer les codes cadeaux utilisés par l'utilisateur
        $usedCodes = GiftCode::where('used_by', Auth::id())
            ->orderBy('used_at', 'desc')
            ->get();
        
        return view('tokens.index', compact('user', 'discounts', 'generatedCodes', 'usedCodes'));
    }
    
    /**
     * Utiliser des tokens pour obtenir un code de réduction
     */
    public function useTokens(Request $request)
    {
        $validated = $request->validate([
            'discount_type' => 'required|string|in:discount_10,discount_25,discount_50_friend',
            'recipient_email' => 'nullable|email',
        ]);
        
        // Récupérer l'utilisateur depuis la base de données pour pouvoir utiliser la méthode save()
        $user = User::find(Auth::id());
        
        if (!$user) {
            return redirect()->route('client.tokens.index')
                ->with('error', "Utilisateur non trouvé.");
        }
        
        $tokens_required = 0;
        $percentage = 0;
        
        // Déterminer le nombre de tokens requis et le pourcentage de réduction
        switch($validated['discount_type']) {
            case 'discount_10':
                $tokens_required = 1000;
                $percentage = 10;
                break;
            case 'discount_25':
                $tokens_required = 2000;
                $percentage = 25;
                break;
            case 'discount_50_friend':
                $tokens_required = 5000;
                $percentage = 50;
                break;
        }
        
        // Vérifier si l'utilisateur a assez de tokens
        if ($user->tokens >= $tokens_required) {
            // Déduire les tokens manuellement
            $user->tokens -= $tokens_required;
            $user->save();
            
            // Générer un code de réduction
            $code = 'GIFT' . strtoupper(substr(md5(uniqid($user->id, true)), 0, 8)) . $percentage;
            
            // Créer le code cadeau dans la base de données
            $giftCode = GiftCode::create([
                'code' => $code,
                'discount_percentage' => $percentage,
                'created_by' => $user->id,
                'recipient_email' => $validated['recipient_email'] ?? null,
                'expires_at' => now()->addDays(30),
            ]);
            
            // Envoyer un email si un destinataire est spécifié
            if (!empty($validated['recipient_email'])) {
                // Envoyer l'email (à implémenter)
                // Mail::to($validated['recipient_email'])->send(new GiftCodeMail($giftCode, $user));
                
                return redirect()->route('client.tokens.index')
                    ->with('success', "Vous avez utilisé $tokens_required tokens pour générer un code de réduction de $percentage% pour {$validated['recipient_email']}. Code: $code");
            }
            
            // Enregistrer le code dans la session pour utilisation directe
            session(['discount_code' => $code, 'discount_percentage' => $percentage]);
            
            return redirect()->route('client.tokens.index')
                ->with('success', "Vous avez utilisé $tokens_required tokens pour obtenir une réduction de $percentage%. Votre code: $code");
        }
        
        return redirect()->route('client.tokens.index')
            ->with('error', "Vous n'avez pas assez de tokens pour cette réduction.");
    }
    
    /**
     * Acheter des tokens via PayPal
     */
    public function buyTokens()
    {
        // Différentes options d'achat de tokens
        $packages = [
            [
                'id' => 'basic',
                'name' => 'Pack Basic',
                'tokens' => 2000,
                'price' => 6.00,
                'description' => 'Pour obtenir une réduction de 10%'
            ],
            [
                'id' => 'standard',
                'name' => 'Pack Standard',
                'tokens' => 5000,
                'price' => 15.00,
                'description' => 'Économisez et obtenez une réduction de 25%'
            ],
            [
                'id' => 'premium',
                'name' => 'Pack Premium',
                'tokens' => 10000,
                'price' => 30.00,
                'description' => 'Notre meilleure offre! Réduction de 50% pour vous ou un ami'
            ]
        ];
        
        return view('tokens.buy', compact('packages'));
    }
    
    /**
     * Traiter l'achat de tokens
     */
    public function processPurchase(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|string|in:basic,standard,premium',
        ]);
        
        $tokens = 0;
        $amount = 0;
        
        // Déterminer le nombre de tokens et le montant à payer
        switch($validated['package_id']) {
            case 'basic':
                $tokens = 1000;
                $amount = 5.99;
                break;
            case 'standard':
                $tokens = 2500;
                $amount = 12.99;
                break;
            case 'premium':
                $tokens = 5000;
                $amount = 23.00;
                break;
        }
        
        // Stocker les informations dans la session pour la suite du processus
        session([
            'token_purchase' => [
                'tokens' => $tokens,
                'amount' => $amount,
                'package_id' => $validated['package_id']
            ]
        ]);
        
        // Rediriger vers PayPal pour le paiement
        return redirect()->route('client.tokens.paypal.create');
    }
    
    /**
     * Créer un paiement PayPal pour acheter des tokens
     */
    public function createPayPalOrder()
    {
        $tokenPurchase = session('token_purchase');
        
        if (!$tokenPurchase) {
            return redirect()->route('client.tokens.buy')
                ->with('error', 'Informations d\'achat introuvables');
        }
        
        // Logique similaire à PayPalController, à adapter pour l'achat de tokens
        // Pour la démonstration, nous allons simuler la réponse PayPal
        
        // Stocker l'ID de la commande PayPal dans la session
        session(['paypal_token_order_id' => 'TOKEN_ORDER_' . uniqid()]);
        
        // Rediriger vers la page de simulation PayPal
        return redirect()->route('client.tokens.paypal.simulate');
    }
    
    /**
     * Simuler la page PayPal
     */
    public function simulatePayPalPage()
    {
        $orderId = session('paypal_token_order_id');
        $tokenPurchase = session('token_purchase');
        
        if (!$orderId || !$tokenPurchase) {
            return redirect()->route('client.tokens.buy')
                ->with('error', 'Informations de commande incomplètes');
        }
        
        return view('tokens.paypal-simulate', compact('orderId', 'tokenPurchase'));
    }
    
    /**
     * Capturer le paiement et créditer les tokens
     */
    public function captureTokenPayment()
    {
        $orderId = session('paypal_token_order_id');
        $tokenPurchase = session('token_purchase');
        
        if (!$orderId || !$tokenPurchase) {
            return redirect()->route('client.tokens.buy')
                ->with('error', 'Informations de commande incomplètes');
        }
        
        // Récupérer l'utilisateur
        $user = User::find(Auth::id());
        
        if (!$user) {
            return redirect()->route('client.tokens.buy')
                ->with('error', 'Utilisateur non trouvé');
        }
        
        // Ajouter les tokens à l'utilisateur
        $user->tokens += $tokenPurchase['tokens'];
        $user->save();
        
        // Enregistrer la transaction
        DB::table('token_transactions')->insert([
            'user_id' => $user->id,
            'tokens' => $tokenPurchase['tokens'],
            'amount' => $tokenPurchase['amount'],
            'transaction_id' => $orderId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Nettoyer la session
        session()->forget(['paypal_token_order_id', 'token_purchase']);
        
        return redirect()->route('client.tokens.index')
            ->with('success', "Paiement réussi! {$tokenPurchase['tokens']} étoiles ont été ajoutées à votre compte.");
    }
    
    /**
     * Annuler l'achat de tokens
     */
    public function cancelTokenPurchase()
    {
        session()->forget(['paypal_token_order_id', 'token_purchase']);
        
        return redirect()->route('client.tokens.buy')
            ->with('error', 'Achat annulé');
    }
    
    /**
     * Afficher le formulaire pour réclamer un code cadeau
     */
    public function showClaimForm()
    {
        return view('tokens.claim');
    }
    
    /**
     * Réclamer un code cadeau
     */
    public function claimGiftCode(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
        ]);
        
        // Chercher le code dans la base de données
        $giftCode = GiftCode::where('code', $validated['code'])
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();
        
        if (!$giftCode) {
            return redirect()->route('client.tokens.claim')
                ->with('error', 'Code invalide ou déjà utilisé');
        }
        
        // Vérifier si le code est destiné à un utilisateur spécifique
        if ($giftCode->recipient_email && $giftCode->recipient_email !== Auth::user()->email) {
            return redirect()->route('client.tokens.claim')
                ->with('error', 'Ce code est destiné à un autre utilisateur');
        }
        
        // Marquer le code comme utilisé
        $giftCode->used_at = now();
        $giftCode->used_by = Auth::id();
        $giftCode->save();
        
        // Stocker la réduction dans la session pour l'appliquer lors du prochain achat
        session([
            'discount_code' => $giftCode->code,
            'discount_percentage' => $giftCode->discount_percentage
        ]);
        
        return redirect()->route('client.catalog')
            ->with('success', "Code cadeau appliqué! Vous bénéficiez d'une réduction de {$giftCode->discount_percentage}% sur votre prochain achat.");
    }
    
    /**
     * Afficher l'historique des tokens utilisés
     */
    public function history()
    {
        $user = User::find(Auth::id());
        
        // Transactions d'achat de tokens
        $purchases = DB::table('token_transactions')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Codes générés par l'utilisateur
        $generatedCodes = GiftCode::where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Codes utilisés par l'utilisateur
        $usedCodes = GiftCode::where('used_by', $user->id)
            ->orderBy('used_at', 'desc')
            ->get();
        
        return view('tokens.history', compact('user', 'purchases', 'generatedCodes', 'usedCodes'));
    }
}
