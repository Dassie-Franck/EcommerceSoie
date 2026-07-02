<?php

use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\ProductFilterController;
use App\Http\Controllers\Shop\ReviewController;
use App\Http\Controllers\Shop\WishlistController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

// ── AUTH (invités seulement) ──────────────────────────────
Route::middleware('guest')->group(function () {

    Route::get('/login',  [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:6,1');

    Route::get('/register',  [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:6,1');

    Route::get('/forgot-password',  [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendOtp'])->name('password.email')->middleware('throttle:3,5');

    Route::get('/verify-otp',  [PasswordResetController::class, 'showOtpForm'])->name('password.otp.form');
    Route::post('/verify-otp', [PasswordResetController::class, 'verifyOtp'])->name('password.otp.verify')->middleware('throttle:5,1');

    Route::get('/reset-password',  [PasswordResetController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update')->middleware('throttle:10,1');

    Route::get('/auth/google',          [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// ── LOGOUT ────────────────────────────────────────────────
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ── SHOP PUBLIC ───────────────────────────────────────────
Route::get('/',          [HomeController::class, 'index'])->name('shop.home');
Route::get('/catalogue', [ProductController::class, 'index'])->name('shop.catalogue');
Route::get('/boutique',  [ProductController::class, 'index'])->name('shop');
Route::get('/produit/{slug}', [ProductController::class, 'show'])->name('shop.product');
Route::get('/api/products/latest', [ProductFilterController::class, 'latest'])->name('shop.products.latest');

// ── SUIVI DE COLIS PUBLIC ─────────────────────────────────
Route::get('/tracking',                              [TrackingController::class, 'form'])->name('tracking.form');
Route::post('/tracking/lookup',                      [TrackingController::class, 'lookup'])->name('tracking.lookup');
Route::get('/tracking/result/{reference}/{email}',   [TrackingController::class, 'result'])->name('tracking.result');
Route::get('/api/tracking/{trackingNumber}',         [TrackingController::class, 'apiTrack'])->name('api.tracking');

// ── PANIER ────────────────────────────────────────────────
Route::get('/panier',             [CartController::class, 'index'])->name('shop.cart');
Route::post('/panier/ajouter',    [CartController::class, 'add'])->name('shop.cart.add');
Route::patch('/panier/{itemId}',  [CartController::class, 'update'])->name('shop.cart.update');
Route::delete('/panier/{itemId}', [CartController::class, 'remove'])->name('shop.cart.remove');
Route::delete('/panier',          [CartController::class, 'clear'])->name('shop.cart.clear');

// ── FAVORIS (auth requis) ─────────────────────────────────
Route::post('/favoris/toggle', [WishlistController::class, 'toggle'])->name('shop.wishlist.toggle')->middleware('auth');

// ── AVIS PRODUIT (auth requis) ────────────────────────────
Route::post('/produit/{product}/avis', [ReviewController::class, 'store'])->name('shop.review.store')->middleware('auth');

// ── CHECKOUT (auth requis) ────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/commande',              [CheckoutController::class, 'index'])->name('shop.checkout');
    Route::post('/commande',             [CheckoutController::class, 'process'])->name('shop.checkout.process')->middleware('throttle:10,2');
    Route::get('/commande/success',      [CheckoutController::class, 'success'])->name('shop.checkout.success');
    Route::get('/commande/cancel',       [CheckoutController::class, 'cancel'])->name('shop.checkout.cancel');
    Route::get('/commande/confirmation', [CheckoutController::class, 'confirmation'])->name('shop.checkout.confirmation');
});

// ── COMPTE CLIENT (auth requis) ───────────────────────────
Route::middleware('auth')->prefix('compte')->name('account.')->group(function () {

    Route::get('/profil',            [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profil',          [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/commandes',                  [OrderController::class, 'index'])->name('orders');
    Route::get('/commandes/{order}',          [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/commandes/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/favoris', [WishlistController::class, 'index'])->name('wishlist');

    Route::get('/adresses',              [AddressController::class, 'index'])->name('addresses');
    Route::post('/adresses',             [AddressController::class, 'store'])->name('addresses.store');
    Route::delete('/adresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');

    Route::get('/suivi',        [TrackingController::class, 'index'])->name('tracking');
    Route::get('/suivi/{order}', [TrackingController::class, 'trackOrder'])->name('tracking.order');
});

// ── ADMIN (auth + admin) ──────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Produits
    Route::get('/products',                     [AdminProductController::class, 'index'])->name('products.index');
    Route::get('/products/create',              [AdminProductController::class, 'create'])->name('products.create');
    Route::post('/products',                    [AdminProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit',      [AdminProductController::class, 'edit'])->name('products.edit');
    Route::get('/products/{product}/edit-data', [AdminProductController::class, 'getEditData'])->name('products.edit-data');
    Route::patch('/products/{product}',         [AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}',        [AdminProductController::class, 'destroy'])->name('products.destroy');

    // Catégories
    Route::get('/categories',                 [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create',          [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories',                [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::match(['PUT', 'PATCH'], '/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}',   [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Commandes
    Route::get('/orders',                    [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}',            [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status',   [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/tracking', [AdminOrderController::class, 'updateTracking'])->name('orders.tracking');
});
