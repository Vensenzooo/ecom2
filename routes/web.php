<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\GiftListController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestrictedController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\TokenDiscountController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route d'accueil
Route::get('/', function () {
    // Afficher la page d'accueil
    return view('welcome');
});

// Routes pour l'interface client (accessibles à tous les utilisateurs authentifiés temporairement)
Route::middleware(['auth'])->group(function () {
    // Routes pour le profil utilisateur
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/theme', [ProfileController::class, 'updateTheme'])->name('profile.theme');
});

Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    // Retirer temporairement le middleware 'can:is-client' pour déboguer
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/catalog', [ClientController::class, 'catalog'])->name('catalog');
    Route::get('/books/{book}', [ClientController::class, 'bookDetails'])->name('book.details');
    Route::get('/orders', [ClientController::class, 'orders'])->name('orders');
    Route::post('/books/{book}/comment', [ClientController::class, 'addComment'])->name('book.comment');
    
    // Routes du panier
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/pay-with-tokens', [CartController::class, 'payWithTokens'])->name('cart.pay-with-tokens');
    Route::post('/cart/apply-discount', [CartController::class, 'applyDiscount'])->name('cart.apply-discount');
    Route::get('/cart/remove-discount', [CartController::class, 'removeDiscount'])->name('cart.remove-discount');
    
    // Routes PayPal
    Route::post('/paypal/create-order', [PayPalController::class, 'createOrder'])->name('paypal.create');
    Route::get('/paypal/simulate', [PayPalController::class, 'simulatePayPalPage'])->name('paypal.simulate');
    Route::post('/paypal/capture-order', [PayPalController::class, 'captureOrder'])->name('paypal.capture');
    Route::get('/paypal/cancel', [PayPalController::class, 'cancelOrder'])->name('paypal.cancel');

    // Routes pour les listes de cadeaux
    Route::resource('gift-lists', GiftListController::class);
    Route::post('gift-lists/{giftList}/add-book', [GiftListController::class, 'addBook'])->name('gift-lists.add-book');
    Route::delete('gift-lists/{giftList}/remove-book/{itemId}', [GiftListController::class, 'removeBook'])->name('gift-lists.remove-book');
    Route::post('gift-lists/{giftList}/invite', [GiftListController::class, 'inviteFriends'])->name('gift-lists.invite');

    // Routes pour les tokens et réductions
    Route::get('/tokens', [TokenDiscountController::class, 'index'])->name('tokens.index');
    Route::post('/tokens/use', [TokenDiscountController::class, 'useTokens'])->name('tokens.use');
    Route::get('/tokens/history', [TokenDiscountController::class, 'history'])->name('tokens.history');
    
    // Nouvelles routes pour l'achat et l'utilisation des tokens/gift codes
    Route::get('/tokens/buy', [TokenDiscountController::class, 'buyTokens'])->name('tokens.buy');
    Route::post('/tokens/purchase', [TokenDiscountController::class, 'processPurchase'])->name('tokens.purchase');
    Route::get('/tokens/paypal/create', [TokenDiscountController::class, 'createPayPalOrder'])->name('tokens.paypal.create');
    Route::get('/tokens/paypal/simulate', [TokenDiscountController::class, 'simulatePayPalPage'])->name('tokens.paypal.simulate');
    Route::post('/tokens/paypal/capture', [TokenDiscountController::class, 'captureTokenPayment'])->name('tokens.paypal.capture');
    Route::get('/tokens/paypal/cancel', [TokenDiscountController::class, 'cancelTokenPurchase'])->name('tokens.paypal.cancel');
    
    // Routes pour réclamer des codes cadeaux
    Route::get('/tokens/claim', [TokenDiscountController::class, 'showClaimForm'])->name('tokens.claim');
    Route::post('/tokens/claim', [TokenDiscountController::class, 'claimGiftCode'])->name('tokens.claim.process');

    // Routes pour les alertes
    Route::get('/alertes', [AlertController::class, 'index'])->name('alerts.index');
    Route::post('/alertes/{alert}/read', [AlertController::class, 'markAsRead'])->name('alerts.read');
    Route::post('/alertes/read-all', [AlertController::class, 'markAllAsRead'])->name('alerts.readAll');
    
    // Routes détaillées pour les commandes
    Route::get('/orders', [ClientController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [ClientController::class, 'orderDetails'])->name('orders.details');
    Route::get('/orders/{order}/confirm-address', [ClientController::class, 'confirmAddressForm'])->name('orders.confirm-address');
    Route::post('/orders/{order}/confirm-address', [ClientController::class, 'confirmAddress'])->name('orders.confirm-address.store');
    Route::get('/orders/{order}/refund', [ClientController::class, 'refundForm'])->name('orders.refund');
    Route::post('/orders/{order}/refund', [ClientController::class, 'requestRefund'])->name('orders.refund.request');
});

// Route publique pour accéder à une liste partagée
Route::get('liste-cadeaux/{code}', [GiftListController::class, 'shared'])->name('gift-lists.shared');
Route::post('liste-cadeaux/{code}/reserve/{itemId}', [GiftListController::class, 'reserveItem'])->name('gift-lists.reserve-item');

// Route pour la page de compte restreint (accessible sans être authentifié)
Route::get('/compte-restreint', [RestrictedController::class, 'index'])->name('restricted.account');

// Routes pour l'admin, le gestionnaire et l'éditeur
Route::middleware(['auth'])->group(function () {
    // Afficher le dashboard approprié selon le rôle
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour les alertes admin/éditeur/gestionnaire
    Route::get('/dashboard/alertes', [AlertController::class, 'index'])->name('alerts.index');
    Route::post('/dashboard/alertes/{alert}/read', [AlertController::class, 'markAsRead'])->name('alerts.read');
    Route::post('/dashboard/alertes/read-all', [AlertController::class, 'markAllAsRead'])->name('alerts.readAll');
    
    // IMPORTANT: Routes statiques d'abord, routes avec paramètres ensuite
    
    // Routes spécifiques aux managers et admins - AVANT les routes avec paramètres
    Route::middleware(['can:is-manager'])->group(function () {
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
    });
    
    // Routes communes à tous les rôles ayant accès aux livres
    Route::middleware(['can:is-editor'])->group(function () {
        Route::get('/books', [BookController::class, 'index'])->name('books.index');
        
        // Routes avec paramètres - APRÈS les routes statiques
        Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::patch('/books/{book}', [BookController::class, 'update']);
        
        // Autres ressources accessibles aux éditeurs
        Route::resource('categories', CategoryController::class);
        Route::resource('comments', CommentController::class);
    });
    
    // Route de suppression - accessible seulement aux managers et admins
    Route::middleware(['can:is-manager'])->group(function () {
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        Route::resource('sales', SaleController::class);
    });

    // Routes accessibles aux admins uniquement
    Route::middleware(['can:is-admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);

        // Nouvelles routes pour l'assignation des utilisateurs aux rôles
        Route::get('/roles/{role}/assign-users', [RoleController::class, 'assignUsers'])->name('roles.assign-users');
        Route::put('/roles/{role}/update-users', [RoleController::class, 'updateUsers'])->name('roles.update-users');

        Route::get('/users/{user}/profile/edit', [UserController::class, 'editProfile'])->name('users.profile.edit');
        Route::put('/users/{user}/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');
        Route::get('/users/{user}/restrict', [UserController::class, 'showRestrict'])->name('users.restrict.show');
        Route::post('/users/{user}/restrict', [UserController::class, 'restrict'])->name('users.restrict');
        Route::post('/users/{user}/unrestrict', [UserController::class, 'unrestrict'])->name('users.unrestrict');
        Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
        Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');

        // Gestion des remboursements
        Route::prefix('admin/refunds')->name('admin.refunds.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\RefundController::class, 'index'])->name('index');
            Route::get('/{order}', [App\Http\Controllers\Admin\RefundController::class, 'show'])->name('show');
            Route::post('/{order}/approve', [App\Http\Controllers\Admin\RefundController::class, 'approve'])->name('approve');
            Route::post('/{order}/reject', [App\Http\Controllers\Admin\RefundController::class, 'reject'])->name('reject');
        });
    });
});

// Routes d'authentification
require __DIR__.'/auth.php';
