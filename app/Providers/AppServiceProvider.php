<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// ── AUTH ───────────────────────────────────────────────────────
use App\Contracts\Auth\AuthServiceInterface;
use App\Contracts\Auth\PasswordServiceInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\PasswordService;

// ── SHOP ───────────────────────────────────────────────────────
use App\Contracts\Shop\CartServiceInterface;
use App\Contracts\Shop\CheckoutServiceInterface;
use App\Contracts\Shop\HomeServiceInterface;
use App\Contracts\Shop\OrderServiceInterface as ShopOrderServiceInterface;
use App\Contracts\Shop\ReviewServiceInterface;
use App\Contracts\Shop\WishlistServiceInterface;
use App\Services\Shop\CartService;
use App\Services\Shop\CheckoutService;
use App\Services\Shop\HomeService;
use App\Services\Shop\OrderService as ShopOrderService;
use App\Services\Shop\ReviewService;
use App\Services\Shop\WishlistService;

// ── SHOP PRODUCT ───────────────────────────────────────────────
use App\Contracts\Shop\ProductServiceInterface as ShopProductServiceInterface;
use App\Services\Shop\ProductService as ShopProductService;

// ── ADMIN ──────────────────────────────────────────────────────
use App\Contracts\Admin\CategoryServiceInterface;
use App\Contracts\Admin\DashboardServiceInterface;
use App\Contracts\Admin\OrderServiceInterface as AdminOrderServiceInterface;
use App\Contracts\Admin\ProductServiceInterface as AdminProductServiceInterface;
use App\Services\Admin\CategoryService;
use App\Services\Admin\DashboardService;
use App\Services\Admin\OrderService as AdminOrderService;
use App\Services\Admin\ProductService as AdminProductService;

// ── ACCOUNT ────────────────────────────────────────────────────
use App\Contracts\Account\AddressServiceInterface;
use App\Contracts\Account\ProfileServiceInterface;
use App\Services\Account\AddressService;
use App\Services\Account\ProfileService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ── AUTH ───────────────────────────────────────────────
        $this->app->bind(AuthServiceInterface::class,     AuthService::class);
        $this->app->bind(PasswordServiceInterface::class, PasswordService::class);

        // ── SHOP ───────────────────────────────────────────────
        $this->app->bind(CartServiceInterface::class,         CartService::class);
        $this->app->bind(CheckoutServiceInterface::class,     CheckoutService::class);
        $this->app->bind(HomeServiceInterface::class,         HomeService::class);
        $this->app->bind(ShopOrderServiceInterface::class,    ShopOrderService::class);
        $this->app->bind(ReviewServiceInterface::class,       ReviewService::class);
        $this->app->bind(WishlistServiceInterface::class,     WishlistService::class);
        $this->app->bind(ShopProductServiceInterface::class,  ShopProductService::class);

        // ── ADMIN ──────────────────────────────────────────────
        $this->app->bind(AdminProductServiceInterface::class, AdminProductService::class);
        $this->app->bind(CategoryServiceInterface::class,     CategoryService::class);
        $this->app->bind(AdminOrderServiceInterface::class,   AdminOrderService::class);
        $this->app->bind(DashboardServiceInterface::class,    DashboardService::class);

        // ── ACCOUNT ────────────────────────────────────────────
        $this->app->bind(ProfileServiceInterface::class, ProfileService::class);
        $this->app->bind(AddressServiceInterface::class, AddressService::class);
    }

    public function boot(): void {}
}