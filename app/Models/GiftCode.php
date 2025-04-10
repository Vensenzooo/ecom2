<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCode extends Model
{
    use HasFactory;

    /**
     * Les attributs qui sont assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'discount_percentage',
        'created_by',
        'recipient_email',
        'used_by',
        'used_at',
        'expires_at',
    ];

    /**
     * Les attributs qui doivent être convertis.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Obtenir l'utilisateur qui a créé le code.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Obtenir l'utilisateur qui a utilisé le code.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Vérifier si le code est expiré.
     */
    public function isExpired()
    {
        return $this->expires_at < now();
    }
    
    /**
     * Vérifier si le code a été utilisé.
     */
    public function isUsed()
    {
        return !is_null($this->used_at);
    }
}
