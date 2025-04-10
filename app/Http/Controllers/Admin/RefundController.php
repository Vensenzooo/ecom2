<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Alert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class RefundController extends Controller
{
    /**
     * Display a listing of pending refund requests.
     */
    public function index()
    {
        // Verify the user is an admin
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les remboursements.");
        }

        // Get all orders with refund requests
        $pendingRefunds = Order::where('statut', 'refund_requested')
            ->with(['user', 'items.book'])
            ->orderBy('refund_requested_at', 'desc')
            ->paginate(10);

        // Get all completed refunds for history
        $completedRefunds = Order::where('statut', 'refunded')
            ->with(['user', 'items.book'])
            ->orderBy('refunded_at', 'desc')
            ->paginate(10);

        return view('admin.refunds.index', compact('pendingRefunds', 'completedRefunds'));
    }

    /**
     * Show a specific refund request.
     */
    public function show(Order $order)
    {
        // Verify the user is an admin
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les remboursements.");
        }

        // Load order items and related data
        $order->load(['user', 'items.book']);

        // Load shipping address only if the relationship exists in the database
        if (Schema::hasTable('shipping_addresses')) {
            $shippingAddress = $order->shippingAddress;
        }

        return view('admin.refunds.show', compact('order'));
    }

    /**
     * Approve a refund request.
     */
    public function approve(Request $request, Order $order)
    {
        // Verify the user is an admin
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les remboursements.");
        }

        // Check if the order has a refund request
        if ($order->statut !== 'refund_requested') {
            return redirect()->route('admin.refunds.index')
                ->with('error', 'Cet ordre ne contient pas de demande de remboursement valide.');
        }

        // Handle PayPal refunds
        if ($order->mode_paiement === 'paypal') {
            // Check if we have the necessary PayPal data for refund
            $paypalData = is_array($order->details_paiement) ? $order->details_paiement : json_decode($order->details_paiement, true);
            if (!isset($paypalData['paypal_id']) && !isset($paypalData['simulated'])) {
                // Missing PayPal data, offer token refund option
                if ($request->has('refund_with_tokens')) {
                    return $this->processTokenRefund($order);
                }
                
                // Show warning and redirect to a page with token refund option
                return redirect()->route('admin.refunds.show', $order)
                    ->with('warning', 'Données PayPal manquantes pour le remboursement. Vous pouvez effectuer un remboursement en tokens à la place.');
            }
            
            // Process PayPal refund with API (simplified for demo)
        }

        // Update the order status
        $order->statut = 'refunded';
        $order->refunded_at = now();
        $order->save();

        // Create an alert for the user
        Alert::create([
            'user_id' => $order->user_id,
            'created_by' => Auth::id(),
            'message' => "Votre demande de remboursement pour la commande #{$order->id} a été approuvée.",
            'type' => 'info',
        ]);

        return redirect()->route('admin.refunds.index')
            ->with('success', 'Remboursement approuvé avec succès.');
    }

    /**
     * Process a token refund when PayPal data is missing
     */
    private function processTokenRefund(Order $order)
    {
        // Calculate token amount (3 tokens = 0.01€)
        $tokenAmount = ceil($order->montant_total * 100 * 3);
        
        // Get the user
        $user = User::find($order->user_id);
        
        if (!$user) {
            return redirect()->route('admin.refunds.show', $order)
                ->with('error', 'Utilisateur introuvable pour le remboursement en tokens.');
        }
        
        // Add tokens to user account
        $user->tokens += $tokenAmount;
        $user->save();
        
        // Update order status
        $order->statut = 'refunded';
        $order->refunded_at = now();
        $order->details_paiement = json_encode([
            'tokens_refund' => true,
            'tokens_amount' => $tokenAmount,
            'original_amount' => $order->montant_total
        ]);
        $order->save();
        
        // Create an alert for the user
        Alert::create([
            'user_id' => $order->user_id,
            'created_by' => Auth::id(),
            'message' => "Votre demande de remboursement pour la commande #{$order->id} a été approuvée. {$tokenAmount} tokens ont été ajoutés à votre compte.",
            'type' => 'info',
        ]);
        
        return redirect()->route('admin.refunds.index')
            ->with('success', "Remboursement en tokens effectué avec succès ({$tokenAmount} tokens).");
    }

    /**
     * Reject a refund request.
     */
    public function reject(Request $request, Order $order)
    {
        // Verify the user is an admin
        if (!Gate::allows('is-admin')) {
            abort(403, "Seuls les administrateurs peuvent gérer les remboursements.");
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        // Check if the order has a refund request
        if ($order->statut !== 'refund_requested') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Cet ordre ne contient pas de demande de remboursement valide.'
                ]);
            }
            return redirect()->route('admin.refunds.index')
                ->with('error', 'Cet ordre ne contient pas de demande de remboursement valide.');
        }

        try {
            // Update the order status
            $order->statut = 'completed'; // Set back to completed
            $order->rejection_reason = $validated['rejection_reason'];
            $result = $order->save();

            // Create an alert for the user
            $alert = Alert::create([
                'user_id' => $order->user_id,
                'created_by' => Auth::id(),
                'message' => "Votre demande de remboursement pour la commande #{$order->id} a été rejetée. Raison: {$validated['rejection_reason']}",
                'type' => 'warning',
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Demande de remboursement rejetée avec succès.'
                ]);
            }

            return redirect()->route('admin.refunds.index')
                ->with('success', 'Demande de remboursement rejetée avec succès.');
                
        } catch (\Exception $e) {
            Log::error('Error rejecting refund', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors du rejet du remboursement: ' . $e->getMessage()
                ]);
            }
            
            return redirect()->route('admin.refunds.index')
                ->with('error', 'Une erreur est survenue lors du rejet du remboursement: ' . $e->getMessage());
        }
    }
}
