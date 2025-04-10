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
});

// Route publique pour accéder à une liste partagée
Route::get('liste-cadeaux/{code}', [GiftListController::class, 'shared'])->name('gift-lists.shared');
Route::post('liste-cadeaux/{code}/reserve/{itemId}', [GiftListController::class, 'reserveItem'])->name('gift-lists.reserve-item');

// Route pour la page de compte restreint (accessible sans être authentifié)
Route::get('/compte-restreint', [RestrictedController::class, 'index'])->name('restricted.account');

// Routes pour les alertes
Route::middleware(['auth'])->group(function () {
    Route::get('/alertes', [AlertController::class, 'index'])->name('alerts.index');
    Route::post('/alertes/{alert}/read', [AlertController::class, 'markAsRead'])->name('alerts.read');
    Route::post('/alertes/read-all', [AlertController::class, 'markAllAsRead'])->name('alerts.readAll');
});

// Routes pour l'admin, le gestionnaire et l'éditeur
Route::middleware(['auth'])->group(function () {
    // Afficher le dashboard approprié selon le rôle
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes accessibles aux éditeurs, gestionnaires et admins
    Route::middleware(['can:is-editor'])->group(function () {
        Route::resource('books', BookController::class)->only(['index', 'show', 'edit', 'update']);
        Route::resource('categories', CategoryController::class);
        Route::resource('comments', CommentController::class);
    });
    
    // Routes accessibles aux gestionnaires et admins
    Route::middleware(['can:is-manager'])->group(function () {
        Route::resource('books', BookController::class)->except(['index', 'show', 'edit', 'update']);
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
    });
});

// Routes d'authentification
require __DIR__.'/auth.php';
