<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'montant_total',
        'statut',
        'mode_paiement',
        'details_paiement',
        'details_webhook',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'details_paiement' => 'array',
        'details_webhook' => 'array',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
