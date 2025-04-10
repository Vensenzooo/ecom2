<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\Comment;
use App\Models\Alert;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 
        'email',
        'password',
        'tokens', // Ajout du champ tokens
        'is_restricted',
        'restriction_reason',
        'restricted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_restricted' => 'boolean',
            'restricted_at' => 'datetime',
        ];
    }

    /**
     * Get the roles associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role')
            ->withPivot('role_id') // Ajout explicite pour éviter l'ambiguïté
            ->withTimestamps();
    }

    /**
     * Get the comments associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the alerts for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Check if the user has unread alerts.
     *
     * @return bool
     */
    public function hasUnreadAlerts()
    {
        return $this->alerts()->whereNull('read_at')->exists();
    }

    /**
     * Count unread alerts.
     *
     * @return int
     */
    public function unreadAlertsCount()
    {
        return $this->alerts()->whereNull('read_at')->count();
    }

    /**
     * Utiliser des tokens pour obtenir une réduction
     *
     * @param int $amount Le montant de tokens à utiliser
     * @return bool Si l'opération a réussi
     */
    public function useTokens(int $amount): bool
    {
        if ($this->tokens >= $amount) {
            $this->tokens -= $amount;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Obtenir le pourcentage de réduction basé sur le montant de tokens
     *
     * @param int $tokens Le montant de tokens
     * @return int Le pourcentage de réduction
     */
    public static function getDiscountPercentage(int $tokens): int
    {
        if ($tokens >= 5000) {
            return 50;
        } elseif ($tokens >= 2000) {
            return 25;
        } elseif ($tokens >= 1000) {
            return 10;
        }
        return 0;
    }

    /**
     * Ajouter des tokens à l'utilisateur
     *
     * @param int $amount Le montant de tokens à ajouter
     * @return void
     */
    public function addTokens(int $amount): void
    {
        $this->tokens += $amount;
        $this->save();
    }
}
