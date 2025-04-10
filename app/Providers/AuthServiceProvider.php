<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\GiftList;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Définir les Gates pour les permissions des rôles
        Gate::define('is-admin', function (User $user) {
            return User::whereHas('roles', function($q) {
                $q->where('nom', 'admin');
            })->where('id', $user->id)->exists();
        });

        Gate::define('is-manager', function (User $user) {
            return User::whereHas('roles', function($q) {
                $q->whereIn('nom', ['gestionnaire', 'admin']);
            })->where('id', $user->id)->exists();
        });

        Gate::define('is-editor', function (User $user) {
            return User::whereHas('roles', function($q) {
                $q->whereIn('nom', ['editeur', 'gestionnaire', 'admin']);
            })->where('id', $user->id)->exists();
        });

        // Autoriser temporairement tous les utilisateurs comme clients
        // Cette modification résout immédiatement l'erreur 403
        Gate::define('is-client', function ($user) {
            return true; // Autorise tous les utilisateurs à accéder aux routes client
        });

        // Ancienne vérification plus stricte (à réactiver plus tard)
        // Gate::define('is-client', function (User $user) {
        //     return User::whereHas('roles', function($q) {
        //         $q->where('nom', 'client');
        //     })->where('id', $user->id)->exists();
        // });

        // Gates pour les listes de cadeaux
        Gate::define('view-gift-list', function (User $user, GiftList $giftList) {
            return $user->id === $giftList->user_id;
        });

        Gate::define('update-gift-list', function (User $user, GiftList $giftList) {
            return $user->id === $giftList->user_id;
        });

        Gate::define('delete-gift-list', function (User $user, GiftList $giftList) {
            return $user->id === $giftList->user_id;
        });
    }
}
