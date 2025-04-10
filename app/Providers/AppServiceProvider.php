<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Définir les Gates pour les permissions
        Gate::define('is-editor', function (User $user) {
            return $user->roles()->whereIn('nom', ['editeur', 'gestionnaire', 'admin'])->exists();
        });

        Gate::define('is-manager', function (User $user) {
            return $user->roles()->whereIn('nom', ['gestionnaire', 'admin'])->exists();
        });

        Gate::define('is-admin', function (User $user) {
            return $user->roles()->where('nom', 'admin')->exists();
        });

        // Utiliser Bootstrap pour la pagination
        Paginator::useBootstrap();
        
        // Personnaliser les flèches de pagination pour qu'elles aient la même taille
        $this->customizePaginationView();
    }
    
    /**
     * Personnaliser les vues de pagination pour corriger la taille des flèches
     */
    protected function customizePaginationView(): void
    {
        // Si le répertoire des vues de pagination n'existe pas encore, le créer
        $paginationDir = resource_path('views/vendor/pagination');
        if (!file_exists($paginationDir)) {
            mkdir($paginationDir, 0755, true);
        }
        
        // Publier les vues de pagination si elles n'existent pas encore
        if (!file_exists($paginationDir . '/bootstrap-4.blade.php')) {
            Artisan::call('vendor:publish', [
                '--provider' => 'Illuminate\Pagination\PaginationServiceProvider',
            ]);
        }
    }
}
