<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'description',
        'can_manage_books',
        'can_manage_categories',
        'can_manage_comments',
        'can_manage_sales',
        'can_view_dashboard',
        'max_books_per_day',
        'max_comments_per_day',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'can_manage_books' => 'boolean',
        'can_manage_categories' => 'boolean',
        'can_manage_comments' => 'boolean',
        'can_manage_sales' => 'boolean',
        'can_view_dashboard' => 'boolean',
        'max_books_per_day' => 'integer',
        'max_comments_per_day' => 'integer',
    ];

    /**
     * Get the users for the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }
    
    /**
     * Get the permissions associated with the role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}
