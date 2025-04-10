<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'street',
        'address',
        'city',
        'postal_code',
        'country',
        'phone'
    ];

    /**
     * Get the order that owns the shipping address.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
