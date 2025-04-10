<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GiftList extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titre',
        'description',
        'user_id',
        'date_evenement',
        'code_partage',
        'active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_evenement' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * Get the user that owns the gift list.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the gift list.
     */
    public function items(): HasMany
    {
        return $this->hasMany(GiftListItem::class);
    }

    /**
     * Get the invitations for the gift list.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(FriendInvitation::class);
    }

    /**
     * Calculate the percentage of reserved items
     */
    public function getReservationProgressAttribute()
    {
        $total = $this->items()->count();
        if ($total === 0) {
            return 0;
        }
        
        $reserved = $this->items()->where('reserve', true)->count();
        return round(($reserved / $total) * 100);
    }
}
