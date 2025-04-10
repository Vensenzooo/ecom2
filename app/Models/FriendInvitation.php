<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FriendInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'gift_list_id',
        'email',
        'nom',
        'token',
        'sent_at',
        'accepted_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'accepted_at' => 'datetime',
    ];

    /**
     * Génère un token unique lors de la création
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->token = $model->token ?? Str::random(32);
        });
    }

    /**
     * Relation avec la liste de cadeaux
     */
    public function giftList()
    {
        return $this->belongsTo(GiftList::class);
    }

    /**
     * Vérifie si l'invitation a été acceptée
     */
    public function isAccepted()
    {
        return $this->accepted_at !== null;
    }

    /**
     * Vérifie si l'invitation a été envoyée
     */
    public function isSent()
    {
        return $this->sent_at !== null;
    }
}
