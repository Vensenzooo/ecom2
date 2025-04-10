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
        'address_confirmed',
        'refund_reason',
        'refunded_at',
        'refund_requested_at'
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'details_paiement' => 'array',
        'details_webhook' => 'array',
        'refunded_at' => 'datetime',
        'refund_requested_at' => 'datetime',
        'address_confirmed' => 'boolean'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtenir les items (ventes) associés à cette commande.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Obtenir l'adresse de livraison associée à cette commande.
     */
    public function address()
    {
        return $this->hasOne(ShippingAddress::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress()
    {
        return $this->hasOne(ShippingAddress::class);
    }
}
