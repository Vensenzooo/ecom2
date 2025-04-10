<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftListItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift_list_id',
        'book_id',
        'quantite',
        'reserve',
        'reserved_by',
    ];

    protected $casts = [
        'reserve' => 'boolean',
    ];

    /**
     * Relation avec la liste de cadeaux
     */
    public function giftList()
    {
        return $this->belongsTo(GiftList::class);
    }

    /**
     * Relation avec le livre
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Relation avec l'utilisateur qui a réservé l'article
     */
    public function reserver()
    {
        return $this->belongsTo(User::class, 'reserved_by');
    }
}
