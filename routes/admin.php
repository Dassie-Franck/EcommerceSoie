<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;

// ── DASHBOARD ─────────────────────────────────────────────
Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

// ── PRODUITS ──────────────────────────────────────────────
Route::get('/produits',                [ProductController::class, 'index'])->name('admin.products.index');
Route::get('/produits/create',         [ProductController::class, 'create'])->name('admin.products.create');
Route::post('/produits',               [ProductController::class, 'store'])->name('admin.products.store');
Route::get('/produits/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
Route::put('/produits/{product}',      [ProductController::class, 'update'])->name('admin.products.update');
Route::delete('/produits/{product}',   [ProductController::class, 'destroy'])->name('admin.products.destroy');

// ── CATÉGORIES ────────────────────────────────────────────
Route::get('/categories',                [CategoryController::class, 'index'])->name('admin.categories.index');
Route::get('/categories/create',         [CategoryController::class, 'create'])->name('admin.categories.create');
Route::post('/categories',               [CategoryController::class, 'store'])->name('admin.categories.store');
Route::delete('/categories/{category}',  [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

// ── COMMANDES ─────────────────────────────────────────────
Route::get('/commandes',                    [OrderController::class, 'index'])->name('admin.orders.index');
Route::get('/commandes/{order}',            [OrderController::class, 'show'])->name('admin.orders.show');
Route::patch('/commandes/{order}/status',   [OrderController::class, 'updateStatus'])->name('admin.orders.status');

// ── AVIS ──────────────────────────────────────────────────
Route::get('/avis',                    [ReviewController::class, 'index'])->name('admin.reviews.index');
Route::patch('/avis/{review}/approve', [ReviewController::class, 'approve'])->name('admin.reviews.approve');
Route::delete('/avis/{review}',        [ReviewController::class, 'destroy'])->name('admin.reviews.destroy');
