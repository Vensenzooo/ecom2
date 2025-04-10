<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Récupérer les commandes PayPal existantes
        $paypalOrders = DB::table('orders')
            ->where('mode_paiement', 'paypal')
            ->where('statut', 'completed')
            ->limit(5)
            ->get();

        // Créer des demandes de remboursement en attente pour les commandes PayPal
        foreach ($paypalOrders as $index => $order) {
            // N'utiliser que quelques commandes pour créer des demandes de remboursement
            if ($index % 2 == 0) {
                $refundReason = $this->getRandomRefundReason();
                
                DB::table('orders')
                    ->where('id', $order->id)
                    ->update([
                        'statut' => 'refund_requested',
                        'refund_reason' => $refundReason,
                        'refund_requested_at' => Carbon::now()->subDays(rand(1, 7)),
                    ]);
            }
        }

        // Créer des commandes PayPal supplémentaires avec statut 'refund_requested' si nous n'en avons pas assez
        $pendingRefundsCount = DB::table('orders')->where('statut', 'refund_requested')->count();
        
        if ($pendingRefundsCount < 3) {
            $usersIds = DB::table('users')->pluck('id')->toArray();
            
            for ($i = 0; $i < (3 - $pendingRefundsCount); $i++) {
                DB::table('orders')->insert([
                    'user_id' => $usersIds[array_rand($usersIds)],
                    'transaction_id' => 'PAYPAL_REF_' . uniqid(),
                    'montant_total' => rand(20, 150) + (rand(0, 99) / 100),
                    'statut' => 'refund_requested',
                    'mode_paiement' => 'paypal',
                    'details_paiement' => json_encode(['simulated' => true]),
                    'refund_reason' => $this->getRandomRefundReason(),
                    'refund_requested_at' => Carbon::now()->subDays(rand(1, 7)),
                    'created_at' => Carbon::now()->subDays(rand(8, 14)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 7)),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les statuts des commandes modifiées
        DB::table('orders')
            ->where('statut', 'refund_requested')
            ->where('mode_paiement', 'paypal')
            ->update([
                'statut' => 'completed',
                'refund_reason' => null,
                'refund_requested_at' => null,
            ]);
    }

    /**
     * Obtenir une raison de remboursement aléatoire
     */
    private function getRandomRefundReason(): string
    {
        $reasons = [
            "Je n'ai pas reçu ma commande après 2 semaines d'attente.",
            "Le livre reçu est endommagé - couverture déchirée.",
            "J'ai commandé par erreur, je ne souhaite plus ce livre.",
            "Ce n'est pas le bon livre, j'ai reçu une édition différente.",
            "La qualité d'impression est médiocre, texte flou sur plusieurs pages.",
            "J'ai trouvé le même livre moins cher ailleurs.",
            "Le contenu ne correspond pas à la description sur le site.",
            "Double commande effectuée par erreur.",
            "Le livre est arrivé trop tard pour la date à laquelle j'en avais besoin.",
            "Je ne suis pas satisfait de la qualité générale du produit."
        ];

        return $reasons[array_rand($reasons)];
    }
};
