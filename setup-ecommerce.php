<?php

/**
 * ============================================================
 *  AFRISOIE SHOP — Script de setup complet
 *  Placer ce fichier à la RACINE du projet Laravel
 *  Exécuter : php setup-ecommerce.php
 *  Supprimer le fichier après exécution
 * ============================================================
 */

$root = __DIR__;
$created = [];
$skipped = [];

// ─────────────────────────────────────────────
// Fonctions utilitaires
// ─────────────────────────────────────────────

function makeDir(string $path): void {
    global $created;
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
        $created[] = "DIR  " . str_replace(__DIR__ . '/', '', $path);
    }
}

function makeFile(string $path, string $content = ''): void {
    global $created, $skipped;
    if (!file_exists($path)) {
        file_put_contents($path, $content);
        $created[] = "FILE " . str_replace(__DIR__ . '/', '', $path);
    } else {
        $skipped[] = "SKIP " . str_replace(__DIR__ . '/', '', $path);
    }
}

function overwriteFile(string $path, string $content): void {
    global $created;
    file_put_contents($path, $content);
    $created[] = "EDIT " . str_replace(__DIR__ . '/', '', $path);
}

// ─────────────────────────────────────────────
// 1. DOSSIERS CONTROLLERS
// ─────────────────────────────────────────────

makeDir("$root/app/Http/Controllers/Admin");
makeDir("$root/app/Http/Controllers/Shop");
makeDir("$root/app/Http/Controllers/Account");
makeDir("$root/app/Http/Controllers/Auth");

// ─────────────────────────────────────────────
// 2. CONTROLLERS ADMIN
// ─────────────────────────────────────────────

makeFile("$root/app/Http/Controllers/Admin/DashboardController.php", <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        \$stats = [
            'total_orders'    => Order::count(),
            'total_revenue'   => Order::where('status', 'completed')->sum('total'),
            'total_products'  => Product::count(),
            'total_customers' => User::where('role', 'client')->count(),
            'recent_orders'   => Order::with('user')->latest()->take(10)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Admin/ProductController.php", <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        \$products = Product::with('category')->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        \$categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest \$request)
    {
        // TODO: implémenter la création produit
        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product \$product)
    {
        \$categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest \$request, Product \$product)
    {
        // TODO: implémenter la mise à jour produit
        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product \$product)
    {
        \$product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé.');
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Admin/CategoryController.php", <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        \$categories = Category::withCount('products')->latest()->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        \$parents = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request \$request)
    {
        \$request->validate([
            'name'      => 'required|string|max:100',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        Category::create(\$request->only('name', 'parent_id', 'is_active'));
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function destroy(Category \$category)
    {
        \$category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée.');
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Admin/OrderController.php", <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        \$orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order \$order)
    {
        \$order->load('items.variant.product', 'user', 'payment', 'address');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request \$request, Order \$order)
    {
        \$request->validate(['status' => 'required|in:pending,processing,shipped,completed,cancelled']);
        \$order->update(['status' => \$request->status]);
        return back()->with('success', 'Statut mis à jour.');
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Admin/ReviewController.php", <<<PHP
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        \$reviews = Review::with('user', 'product')->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review \$review)
    {
        \$review->update(['is_approved' => true]);
        return back()->with('success', 'Avis approuvé.');
    }

    public function destroy(Review \$review)
    {
        \$review->delete();
        return back()->with('success', 'Avis supprimé.');
    }
}
PHP);

// ─────────────────────────────────────────────
// 3. CONTROLLERS SHOP
// ─────────────────────────────────────────────

makeFile("$root/app/Http/Controllers/Shop/HomeController.php", <<<PHP
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        \$featured   = Product::where('is_featured', true)->where('is_active', true)->take(8)->get();
        \$categories = Category::whereNull('parent_id')->where('is_active', true)->take(6)->get();
        return view('shop.home', compact('featured', 'categories'));
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Shop/ProductController.php", <<<PHP
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request \$request)
    {
        \$query = Product::with('category', 'images')->where('is_active', true);

        if (\$request->filled('category')) {
            \$query->where('category_id', \$request->category);
        }
        if (\$request->filled('min_price')) {
            \$query->where('base_price', '>=', \$request->min_price);
        }
        if (\$request->filled('max_price')) {
            \$query->where('base_price', '<=', \$request->max_price);
        }

        \$products   = \$query->paginate(12);
        \$categories = Category::where('is_active', true)->get();

        return view('shop.catalogue', compact('products', 'categories'));
    }

    public function show(Product \$product)
    {
        \$product->load('variants', 'images', 'reviews.user', 'category');
        \$related = Product::where('category_id', \$product->category_id)
            ->where('id', '!=', \$product->id)
            ->take(4)->get();
        return view('shop.product', compact('product', 'related'));
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Shop/CartController.php", <<<PHP
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService \$cartService) {}

    public function index()
    {
        \$cart = \$this->cartService->getCart();
        return view('shop.cart', compact('cart'));
    }

    public function add(Request \$request)
    {
        \$request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1',
        ]);
        \$this->cartService->addItem(\$request->variant_id, \$request->quantity);
        return back()->with('success', 'Produit ajouté au panier.');
    }

    public function update(Request \$request, int \$itemId)
    {
        \$this->cartService->updateItem(\$itemId, \$request->quantity);
        return back()->with('success', 'Panier mis à jour.');
    }

    public function remove(int \$itemId)
    {
        \$this->cartService->removeItem(\$itemId);
        return back()->with('success', 'Article retiré.');
    }

    public function clear()
    {
        \$this->cartService->clear();
        return back()->with('success', 'Panier vidé.');
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Shop/WishlistController.php", <<<PHP
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\WishlistItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        \$this->middleware('auth');
    }

    public function index()
    {
        \$wishlist = auth()->user()->wishlist()->with('items.variant.product')->first();
        return view('account.wishlist', compact('wishlist'));
    }

    public function toggle(Request \$request)
    {
        \$request->validate(['variant_id' => 'required|exists:product_variants,id']);
        \$wishlist = auth()->user()->wishlist()->firstOrCreate([]);
        \$item = \$wishlist->items()->where('product_variant_id', \$request->variant_id)->first();

        if (\$item) {
            \$item->delete();
            return back()->with('success', 'Retiré des favoris.');
        }
        \$wishlist->items()->create(['product_variant_id' => \$request->variant_id]);
        return back()->with('success', 'Ajouté aux favoris.');
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Shop/CheckoutController.php", <<<PHP
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\CheckoutRequest;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PayPalService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService   \$cartService,
        private OrderService  \$orderService,
        private PayPalService \$paypalService,
    ) {
        \$this->middleware('auth');
    }

    public function index()
    {
        \$cart      = \$this->cartService->getCart();
        \$addresses = auth()->user()->addresses()->get();
        return view('shop.checkout', compact('cart', 'addresses'));
    }

    public function process(CheckoutRequest \$request)
    {
        \$order   = \$this->orderService->createFromCart(\$request->validated());
        \$payLink = \$this->paypalService->createOrder(\$order);
        return redirect(\$payLink);
    }

    public function success(Request \$request)
    {
        \$this->paypalService->captureOrder(\$request->token);
        return redirect()->route('shop.orders.confirmation')->with('success', 'Paiement confirmé !');
    }

    public function cancel()
    {
        return redirect()->route('shop.cart')->with('error', 'Paiement annulé.');
    }

    public function confirmation()
    {
        return view('shop.confirmation');
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Shop/OrderController.php", <<<PHP
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function __construct()
    {
        \$this->middleware('auth');
    }

    public function index()
    {
        \$orders = auth()->user()->orders()->with('items')->latest()->paginate(10);
        return view('account.orders', compact('orders'));
    }

    public function show(Order \$order)
    {
        \$this->authorize('view', \$order);
        \$order->load('items.variant.product', 'payment', 'address');
        return view('account.order-detail', compact('order'));
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Shop/ReviewController.php", <<<PHP
<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ReviewRequest;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    public function __construct()
    {
        \$this->middleware('auth');
    }

    public function store(ReviewRequest \$request, Product \$product)
    {
        Review::create([
            'user_id'     => auth()->id(),
            'product_id'  => \$product->id,
            'order_id'    => \$request->order_id,
            'rating'      => \$request->rating,
            'title'       => \$request->title,
            'comment'     => \$request->comment,
            'is_approved' => false,
        ]);
        return back()->with('success', 'Votre avis a été soumis et sera publié après modération.');
    }
}
PHP);

// ─────────────────────────────────────────────
// 4. CONTROLLERS AUTH
// ─────────────────────────────────────────────

makeFile("$root/app/Http/Controllers/Auth/LoginController.php", <<<PHP
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request \$request)
    {
        \$credentials = \$request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(\$credentials, \$request->boolean('remember'))) {
            \$request->session()->regenerate();
            \$redirect = auth()->user()->role === 'admin' ? 'admin.dashboard' : 'shop.home';
            return redirect()->route(\$redirect);
        }

        return back()->withErrors(['email' => 'Identifiants incorrects.'])->onlyInput('email');
    }

    public function logout(Request \$request)
    {
        Auth::logout();
        \$request->session()->invalidate();
        \$request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Auth/RegisterController.php", <<<PHP
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request \$request)
    {
        \$data = \$request->validate([
            'name'                  => 'required|string|max:100',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        \$user = User::create([
            'name'     => \$data['name'],
            'email'    => \$data['email'],
            'password' => Hash::make(\$data['password']),
            'role'     => 'client',
        ]);

        Wishlist::create(['user_id' => \$user->id]);

        Auth::login(\$user);
        return redirect()->route('shop.home')->with('success', 'Bienvenue sur AfriSoie !');
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Auth/PasswordResetController.php", <<<PHP
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request \$request)
    {
        \$request->validate(['email' => 'required|email']);
        Password::sendResetLink(\$request->only('email'));
        return back()->with('success', 'Un lien de réinitialisation a été envoyé à votre email.');
    }

    public function showResetForm(string \$token)
    {
        return view('auth.reset-password', ['token' => \$token]);
    }

    public function reset(Request \$request)
    {
        \$request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:8|confirmed',
        ]);
        \$status = Password::reset(\$request->only('email','password','password_confirmation','token'),
            fn(\$user, \$password) => \$user->update(['password' => bcrypt(\$password)]));
        return \$status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Mot de passe réinitialisé.')
            : back()->withErrors(['email' => 'Lien invalide ou expiré.']);
    }
}
PHP);

// ─────────────────────────────────────────────
// 5. CONTROLLERS ACCOUNT
// ─────────────────────────────────────────────

makeFile("$root/app/Http/Controllers/Account/ProfileController.php", <<<PHP
<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        \$this->middleware('auth');
    }

    public function show()
    {
        return view('account.profile', ['user' => auth()->user()]);
    }

    public function update(Request \$request)
    {
        \$request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);
        auth()->user()->update(\$request->only('name', 'email'));
        return back()->with('success', 'Profil mis à jour.');
    }

    public function updatePassword(Request \$request)
    {
        \$request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);
        if (!Hash::check(\$request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }
        auth()->user()->update(['password' => bcrypt(\$request->password)]);
        return back()->with('success', 'Mot de passe mis à jour.');
    }
}
PHP);

makeFile("$root/app/Http/Controllers/Account/AddressController.php", <<<PHP
<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        \$this->middleware('auth');
    }

    public function index()
    {
        \$addresses = auth()->user()->addresses()->get();
        return view('account.addresses', compact('addresses'));
    }

    public function store(Request \$request)
    {
        \$request->validate([
            'full_name'   => 'required|string|max:100',
            'phone'       => 'required|string|max:20',
            'street'      => 'required|string|max:255',
            'city'        => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country'     => 'required|string|max:100',
        ]);
        auth()->user()->addresses()->create(\$request->all());
        return back()->with('success', 'Adresse ajoutée.');
    }

    public function destroy(Address \$address)
    {
        \$this->authorize('delete', \$address);
        \$address->delete();
        return back()->with('success', 'Adresse supprimée.');
    }
}
PHP);

// ─────────────────────────────────────────────
// 6. MIDDLEWARE
// ─────────────────────────────────────────────

makeFile("$root/app/Http/Middleware/IsAdmin.php", <<<PHP
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request \$request, Closure \$next)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Accès non autorisé.');
        }
        return \$next(\$request);
    }
}
PHP);

makeFile("$root/app/Http/Middleware/IsVerified.php", <<<PHP
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsVerified
{
    public function handle(Request \$request, Closure \$next)
    {
        if (auth()->check() && !auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        return \$next(\$request);
    }
}
PHP);

// ─────────────────────────────────────────────
// 7. FORM REQUESTS
// ─────────────────────────────────────────────

makeDir("$root/app/Http/Requests/Admin");
makeDir("$root/app/Http/Requests/Shop");

makeFile("$root/app/Http/Requests/Admin/StoreProductRequest.php", <<<PHP
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->role === 'admin'; }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'base_price'  => 'required|numeric|min:0',
            'fabric_type' => 'required|string|max:100',
            'origin'      => 'nullable|string|max:100',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'images.*'    => 'nullable|image|max:5120',
        ];
    }
}
PHP);

makeFile("$root/app/Http/Requests/Admin/UpdateProductRequest.php", <<<PHP
<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()?->role === 'admin'; }

    public function rules(): array
    {
        return [
            'name'        => 'sometimes|string|max:200',
            'category_id' => 'sometimes|exists:categories,id',
            'description' => 'sometimes|string',
            'base_price'  => 'sometimes|numeric|min:0',
            'fabric_type' => 'sometimes|string|max:100',
            'is_active'   => 'boolean',
            'is_featured' => 'boolean',
            'images.*'    => 'nullable|image|max:5120',
        ];
    }
}
PHP);

makeFile("$root/app/Http/Requests/Shop/CheckoutRequest.php", <<<PHP
<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'address_id'       => 'required|exists:addresses,id',
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'notes'            => 'nullable|string|max:500',
        ];
    }
}
PHP);

makeFile("$root/app/Http/Requests/Shop/ReviewRequest.php", <<<PHP
<?php

namespace App\Http\Requests\Shop;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'rating'   => 'required|integer|min:1|max:5',
            'title'    => 'nullable|string|max:150',
            'comment'  => 'required|string|min:10|max:1000',
        ];
    }
}
PHP);

// ─────────────────────────────────────────────
// 8. SERVICES
// ─────────────────────────────────────────────

makeDir("$root/app/Services");

makeFile("$root/app/Services/CartService.php", <<<PHP
<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;

class CartService
{
    public function getCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }
        \$sessionId = session()->getId();
        return Cart::firstOrCreate(['session_id' => \$sessionId]);
    }

    public function addItem(int \$variantId, int \$quantity = 1): void
    {
        \$cart = \$this->getCart();
        \$item = \$cart->items()->where('product_variant_id', \$variantId)->first();

        if (\$item) {
            \$item->increment('quantity', \$quantity);
        } else {
            \$cart->items()->create(['product_variant_id' => \$variantId, 'quantity' => \$quantity]);
        }
    }

    public function updateItem(int \$itemId, int \$quantity): void
    {
        \$item = CartItem::findOrFail(\$itemId);
        if (\$quantity <= 0) {
            \$item->delete();
        } else {
            \$item->update(['quantity' => \$quantity]);
        }
    }

    public function removeItem(int \$itemId): void
    {
        CartItem::findOrFail(\$itemId)->delete();
    }

    public function clear(): void
    {
        \$this->getCart()->items()->delete();
    }

    public function getTotal(): float
    {
        return \$this->getCart()->items->sum(fn(\$item) => \$item->quantity * \$item->variant->getPrice());
    }
}
PHP);

makeFile("$root/app/Services/OrderService.php", <<<PHP
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ShippingZone;

class OrderService
{
    public function __construct(private CartService \$cartService) {}

    public function createFromCart(array \$data): Order
    {
        \$cart = \$this->cartService->getCart();
        \$zone = ShippingZone::findOrFail(\$data['shipping_zone_id']);
        \$subtotal = \$this->cartService->getTotal();
        \$shippingCost = \$zone->isFreeFor(\$subtotal) ? 0 : \$zone->price;

        \$order = Order::create([
            'user_id'          => auth()->id(),
            'address_id'       => \$data['address_id'],
            'shipping_zone_id' => \$data['shipping_zone_id'],
            'order_number'     => 'AS-' . strtoupper(uniqid()),
            'status'           => 'pending',
            'subtotal'         => \$subtotal,
            'shipping_cost'    => \$shippingCost,
            'total'            => \$subtotal + \$shippingCost,
            'notes'            => \$data['notes'] ?? null,
        ]);

        foreach (\$cart->items as \$item) {
            \$order->items()->create([
                'product_variant_id' => \$item->product_variant_id,
                'product_name'       => \$item->variant->product->name,
                'variant_label'      => \$item->variant->size . ' / ' . \$item->variant->color,
                'unit_price'         => \$item->variant->getPrice(),
                'quantity'           => \$item->quantity,
            ]);
            \$item->variant->decrementStock(\$item->quantity);
        }

        \$this->cartService->clear();
        return \$order;
    }
}
PHP);

makeFile("$root/app/Services/PayPalService.php", <<<PHP
<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalService
{
    private PayPalClient \$client;

    public function __construct()
    {
        \$this->client = new PayPalClient;
        \$this->client->setApiCredentials(config('paypal'));
        \$this->client->getAccessToken();
    }

    public function createOrder(Order \$order): string
    {
        \$response = \$this->client->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'reference_id' => \$order->order_number,
                'amount' => [
                    'currency_code' => 'EUR',
                    'value' => number_format(\$order->total, 2, '.', ''),
                ],
            ]],
            'application_context' => [
                'return_url' => route('shop.checkout.success'),
                'cancel_url' => route('shop.checkout.cancel'),
            ],
        ]);

        Payment::create([
            'order_id'   => \$order->id,
            'provider'   => 'paypal',
            'status'     => 'pending',
            'amount'     => \$order->total,
            'currency'   => 'EUR',
        ]);

        return collect(\$response['links'])->firstWhere('rel', 'approve')['href'];
    }

    public function captureOrder(string \$token): void
    {
        \$response = \$this->client->capturePaymentOrder(\$token);
        if (isset(\$response['status']) && \$response['status'] === 'COMPLETED') {
            \$refId  = \$response['purchase_units'][0]['reference_id'];
            \$order  = Order::where('order_number', \$refId)->firstOrFail();
            \$order->update(['status' => 'processing']);
            \$order->payment()->update([
                'transaction_id' => \$response['id'],
                'status'         => 'completed',
                'paid_at'        => now(),
            ]);
        }
    }
}
PHP);

makeFile("$root/app/Services/ImageService.php", <<<PHP
<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ImageService
{
    public function uploadProductImage(UploadedFile \$file, string \$folder = 'products'): string
    {
        \$filename = uniqid() . '.webp';
        \$image    = Image::read(\$file)
            ->cover(800, 1000)
            ->toWebp(85);

        Storage::disk('public')->put("\$folder/\$filename", \$image);
        return "\$folder/\$filename";
    }

    public function delete(string \$path): void
    {
        Storage::disk('public')->delete(\$path);
    }
}
PHP);

// ─────────────────────────────────────────────
// 9. NOTIFICATIONS
// ─────────────────────────────────────────────

makeDir("$root/app/Notifications");

makeFile("$root/app/Notifications/OrderPlaced.php", <<<PHP
<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderPlaced extends Notification
{
    public function __construct(private Order \$order) {}

    public function via(object \$notifiable): array { return ['mail']; }

    public function toMail(object \$notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre commande #' . \$this->order->order_number . ' a été reçue')
            ->greeting('Bonjour ' . \$notifiable->name . ' !')
            ->line('Votre commande a bien été enregistrée.')
            ->line('Montant total : ' . number_format(\$this->order->total, 2) . ' €')
            ->action('Voir ma commande', route('account.orders'))
            ->line('Merci pour votre confiance. AfriSoie Shop.');
    }
}
PHP);

makeFile("$root/app/Notifications/OrderStatusUpdated.php", <<<PHP
<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdated extends Notification
{
    public function __construct(private Order \$order) {}

    public function via(object \$notifiable): array { return ['mail']; }

    public function toMail(object \$notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mise à jour de votre commande #' . \$this->order->order_number)
            ->greeting('Bonjour ' . \$notifiable->name . ' !')
            ->line('Le statut de votre commande a été mis à jour : ' . \$this->order->status)
            ->action('Voir ma commande', route('account.orders'));
    }
}
PHP);

makeFile("$root/app/Notifications/WelcomeUser.php", <<<PHP
<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeUser extends Notification
{
    public function via(object \$notifiable): array { return ['mail']; }

    public function toMail(object \$notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bienvenue sur AfriSoie Shop !')
            ->greeting('Bonjour ' . \$notifiable->name . ' !')
            ->line('Votre compte a été créé avec succès.')
            ->action('Découvrir nos collections', route('shop.home'))
            ->line('Merci de rejoindre AfriSoie Shop.');
    }
}
PHP);

// ─────────────────────────────────────────────
// 10. POLICIES
// ─────────────────────────────────────────────

makeDir("$root/app/Policies");

makeFile("$root/app/Policies/ProductPolicy.php", <<<PHP
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;

class ProductPolicy
{
    public function viewAny(?User \$user): bool { return true; }
    public function view(?User \$user, Product \$product): bool { return \$product->is_active; }
    public function create(User \$user): bool { return \$user->role === 'admin'; }
    public function update(User \$user, Product \$product): bool { return \$user->role === 'admin'; }
    public function delete(User \$user, Product \$product): bool { return \$user->role === 'admin'; }
}
PHP);

makeFile("$root/app/Policies/OrderPolicy.php", <<<PHP
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    public function view(User \$user, Order \$order): bool
    {
        return \$user->id === \$order->user_id || \$user->role === 'admin';
    }
}
PHP);

makeFile("$root/app/Policies/ReviewPolicy.php", <<<PHP
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Review;

class ReviewPolicy
{
    public function delete(User \$user, Review \$review): bool
    {
        return \$user->id === \$review->user_id || \$user->role === 'admin';
    }
}
PHP);

// ─────────────────────────────────────────────
// 11. VUES BLADE — Layouts
// ─────────────────────────────────────────────

makeDir("$root/resources/views/layouts");
makeDir("$root/resources/views/components");
makeDir("$root/resources/views/shop");
makeDir("$root/resources/views/admin/products");
makeDir("$root/resources/views/admin/orders");
makeDir("$root/resources/views/admin/categories");
makeDir("$root/resources/views/admin/reviews");
makeDir("$root/resources/views/account");
makeDir("$root/resources/views/auth");

makeFile("$root/resources/views/layouts/app.blade.php", <<<BLADE
<!DOCTYPE html>
<html lang="fr" data-theme="afrisoie">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AfriSoie Shop') — Vêtements Africains en Soie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-base-100 text-base-content">
    @include('components.navbar')
    @include('components.flash-message')
    <main class="min-h-screen">
        @yield('content')
    </main>
    @include('components.footer')
    @stack('scripts')
</body>
</html>
BLADE);

makeFile("$root/resources/views/layouts/admin.blade.php", <<<BLADE
<!DOCTYPE html>
<html lang="fr" data-theme="afrisoie">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Dashboard') | AfriSoie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-base-200 text-base-content">
    <div class="drawer lg:drawer-open">
        <input id="admin-drawer" type="checkbox" class="drawer-toggle">
        <div class="drawer-content flex flex-col">
            {{-- Topbar --}}
            <div class="navbar bg-base-100 shadow-sm lg:hidden">
                <label for="admin-drawer" class="btn btn-ghost drawer-button">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </label>
                <span class="font-heading text-lg font-semibold ml-2">AfriSoie Admin</span>
            </div>
            <main class="p-6 flex-1">
                @include('components.flash-message')
                @yield('content')
            </main>
        </div>
        {{-- Sidebar --}}
        <div class="drawer-side z-40">
            <label for="admin-drawer" class="drawer-overlay"></label>
            <aside class="bg-base-100 w-64 min-h-full flex flex-col shadow-md">
                <div class="p-6 border-b border-base-300">
                    <span class="font-heading text-xl font-semibold text-primary">AfriSoie</span>
                    <p class="text-xs text-base-content/60 mt-1">Espace administrateur</p>
                </div>
                <ul class="menu p-4 flex-1 text-sm">
                    <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">Produits</a></li>
                    <li><a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Catégories</a></li>
                    <li><a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">Commandes</a></li>
                    <li><a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">Avis clients</a></li>
                </ul>
                <div class="p-4 border-t border-base-300">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="btn btn-ghost btn-sm w-full">Déconnexion</button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
BLADE);

makeFile("$root/resources/views/layouts/guest.blade.php", <<<BLADE
<!DOCTYPE html>
<html lang="fr" data-theme="afrisoie">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — AfriSoie Shop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-base-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-4">
        <div class="text-center mb-8">
            <a href="{{ route('shop.home') }}" class="font-heading text-3xl font-semibold text-primary">AfriSoie</a>
        </div>
        @include('components.flash-message')
        @yield('content')
    </div>
</body>
</html>
BLADE);

// ─────────────────────────────────────────────
// 12. COMPOSANTS BLADE
// ─────────────────────────────────────────────

makeFile("$root/resources/views/components/navbar.blade.php", <<<BLADE
<div class="navbar bg-base-100 shadow-sm sticky top-0 z-30">
    <div class="container mx-auto flex items-center justify-between">
        <a href="{{ route('shop.home') }}" class="font-heading text-2xl font-semibold text-primary">AfriSoie</a>
        <ul class="menu menu-horizontal hidden md:flex text-sm gap-1">
            <li><a href="{{ route('shop.home') }}">Accueil</a></li>
            <li><a href="{{ route('shop.catalogue') }}">Catalogue</a></li>
        </ul>
        <div class="flex items-center gap-2">
            <a href="{{ route('shop.cart') }}" class="btn btn-ghost btn-sm">
                Panier
                @auth
                    <span class="badge badge-primary badge-sm">{{ auth()->user()->cart?->items_count ?? 0 }}</span>
                @endauth
            </a>
            @auth
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-sm">{{ auth()->user()->name }}</label>
                    <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box shadow-md w-48 p-2 mt-2">
                        <li><a href="{{ route('account.profile') }}">Mon profil</a></li>
                        <li><a href="{{ route('account.orders') }}">Mes commandes</a></li>
                        <li><a href="{{ route('account.wishlist') }}">Mes favoris</a></li>
                        @if(auth()->user()->role === 'admin')
                            <li><a href="{{ route('admin.dashboard') }}" class="text-primary">Admin</a></li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="w-full text-left text-error">Déconnexion</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Connexion</a>
            @endauth
        </div>
    </div>
</div>
BLADE);

makeFile("$root/resources/views/components/footer.blade.php", <<<BLADE
<footer class="footer footer-center bg-neutral text-neutral-content p-10 mt-16">
    <p class="font-heading text-xl">AfriSoie Shop</p>
    <p class="text-sm opacity-70">Vêtements africains en tissu soie — Livraison en Europe & Afrique</p>
    <p class="text-xs opacity-50">© {{ date('Y') }} AfriSoie. Tous droits réservés.</p>
</footer>
BLADE);

makeFile("$root/resources/views/components/flash-message.blade.php", <<<BLADE
@if(session('success'))
    <div class="alert alert-success max-w-4xl mx-auto mt-4" x-data="{ show: true }" x-show="show">
        <span>{{ session('success') }}</span>
        <button class="btn btn-ghost btn-xs" @click="show = false">✕</button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-error max-w-4xl mx-auto mt-4" x-data="{ show: true }" x-show="show">
        <span>{{ session('error') }}</span>
        <button class="btn btn-ghost btn-xs" @click="show = false">✕</button>
    </div>
@endif
BLADE);

makeFile("$root/resources/views/components/product-card.blade.php", <<<BLADE
@props(['product'])
<div class="card card-compact bg-base-100 shadow-sm hover:shadow-md transition-shadow border border-base-200">
    <figure class="aspect-[3/4] overflow-hidden">
        <a href="{{ route('shop.product', \$product->slug) }}">
            <img src="{{ \$product->primaryImage?->url ?? asset('images/placeholder.jpg') }}"
                 alt="{{ \$product->name }}"
                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
        </a>
    </figure>
    <div class="card-body">
        <h3 class="card-title text-sm font-medium line-clamp-2">{{ \$product->name }}</h3>
        <p class="text-xs text-base-content/60">{{ \$product->fabric_type }}</p>
        <div class="flex items-center justify-between mt-2">
            <span class="text-primary font-semibold">{{ number_format(\$product->base_price, 2) }} €</span>
            <a href="{{ route('shop.product', \$product->slug) }}" class="btn btn-primary btn-xs">Voir</a>
        </div>
    </div>
</div>
BLADE);

makeFile("$root/resources/views/components/star-rating.blade.php", <<<BLADE
@props(['rating' => 0, 'max' => 5])
<div class="rating rating-sm">
    @for(\$i = 1; \$i <= \$max; \$i++)
        <input type="radio" class="mask mask-star-2 bg-warning"
               {{ \$i <= round(\$rating) ? 'checked' : '' }} disabled />
    @endfor
</div>
BLADE);

makeFile("$root/resources/views/components/cart-drawer.blade.php", <<<BLADE
{{-- Cart Drawer — intégrable dans le layout via @include --}}
<div x-data="{ open: false }">
    <button @click="open = true" class="btn btn-ghost">Panier</button>
    <div class="fixed inset-0 z-50" x-show="open" x-cloak>
        <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
        <div class="absolute right-0 top-0 h-full w-80 bg-base-100 shadow-xl p-6 overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-heading text-xl">Mon panier</h2>
                <button @click="open = false" class="btn btn-ghost btn-sm">✕</button>
            </div>
            <p class="text-base-content/60 text-sm">Votre panier est vide.</p>
        </div>
    </div>
</div>
BLADE);

// ─────────────────────────────────────────────
// 13. VUES SHOP
// ─────────────────────────────────────────────

makeFile("$root/resources/views/shop/home.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', 'Accueil')
@section('content')
<section class="container mx-auto px-4 py-12">
    <h1 class="font-heading text-4xl md:text-5xl font-semibold text-center mb-4">Collections AfriSoie</h1>
    <p class="text-center text-base-content/60 max-w-xl mx-auto mb-12">
        Vêtements africains authentiques en tissu soie, confectionnés avec passion.
    </p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach(\$featured as \$product)
            <x-product-card :product="\$product" />
        @endforeach
    </div>
</section>
@endsection
BLADE);

makeFile("$root/resources/views/shop/catalogue.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', 'Catalogue')
@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="font-heading text-3xl font-semibold mb-8">Nos Collections</h1>
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Filtres --}}
        <aside class="w-full lg:w-64 flex-shrink-0">
            <form method="GET" action="{{ route('shop.catalogue') }}" class="space-y-4">
                <div class="form-control">
                    <label class="label"><span class="label-text font-medium">Catégorie</span></label>
                    <select name="category" class="select select-bordered w-full">
                        <option value="">Toutes</option>
                        @foreach(\$categories as \$cat)
                            <option value="{{ \$cat->id }}" {{ request('category') == \$cat->id ? 'selected' : '' }}>
                                {{ \$cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-full">Filtrer</button>
            </form>
        </aside>
        {{-- Grille produits --}}
        <div class="flex-1">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @forelse(\$products as \$product)
                    <x-product-card :product="\$product" />
                @empty
                    <p class="col-span-3 text-base-content/60">Aucun produit trouvé.</p>
                @endforelse
            </div>
            <div class="mt-8">{{ \$products->links() }}</div>
        </div>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/shop/product.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', \$product->name)
@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        {{-- Galerie --}}
        <div class="space-y-3">
            <div class="aspect-[3/4] rounded-2xl overflow-hidden bg-base-200">
                <img src="{{ \$product->primaryImage?->url ?? asset('images/placeholder.jpg') }}"
                     alt="{{ \$product->name }}" class="w-full h-full object-cover">
            </div>
        </div>
        {{-- Infos produit --}}
        <div class="space-y-6">
            <div>
                <p class="text-sm text-primary font-medium">{{ \$product->category->name }}</p>
                <h1 class="font-heading text-3xl font-semibold mt-1">{{ \$product->name }}</h1>
                <p class="text-2xl font-semibold text-primary mt-3">{{ number_format(\$product->base_price, 2) }} €</p>
            </div>
            <p class="text-base-content/70 leading-relaxed">{{ \$product->description }}</p>
            <form method="POST" action="{{ route('shop.cart.add') }}">
                @csrf
                <input type="hidden" name="variant_id" value="{{ \$product->variants->first()?->id }}">
                <div class="form-control mb-4">
                    <label class="label"><span class="label-text">Quantité</span></label>
                    <input type="number" name="quantity" value="1" min="1" class="input input-bordered w-24">
                </div>
                <button type="submit" class="btn btn-primary w-full">Ajouter au panier</button>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/shop/cart.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', 'Mon panier')
@section('content')
<div class="container mx-auto px-4 py-10 max-w-4xl">
    <h1 class="font-heading text-3xl font-semibold mb-8">Mon panier</h1>
    @if(\$cart && \$cart->items->count())
        <div class="space-y-4">
            @foreach(\$cart->items as \$item)
                <div class="card card-side bg-base-100 shadow-sm border border-base-200">
                    <div class="card-body flex-row items-center justify-between">
                        <span>{{ \$item->variant->product->name }}</span>
                        <span class="font-semibold text-primary">{{ number_format(\$item->quantity * \$item->variant->getPrice(), 2) }} €</span>
                        <form method="POST" action="{{ route('shop.cart.remove', \$item->id) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-ghost btn-xs text-error">Retirer</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-end mt-8">
            <a href="{{ route('shop.checkout') }}" class="btn btn-primary btn-lg">Passer la commande</a>
        </div>
    @else
        <div class="text-center py-20">
            <p class="text-base-content/60 text-lg">Votre panier est vide.</p>
            <a href="{{ route('shop.catalogue') }}" class="btn btn-primary mt-6">Découvrir les collections</a>
        </div>
    @endif
</div>
@endsection
BLADE);

makeFile("$root/resources/views/shop/checkout.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', 'Finaliser la commande')
@section('content')
<div class="container mx-auto px-4 py-10 max-w-2xl">
    <h1 class="font-heading text-3xl font-semibold mb-8">Finaliser la commande</h1>
    <form method="POST" action="{{ route('shop.checkout.process') }}" class="space-y-6">
        @csrf
        <div class="form-control">
            <label class="label"><span class="label-text font-medium">Adresse de livraison</span></label>
            <select name="address_id" class="select select-bordered" required>
                @foreach(\$addresses as \$address)
                    <option value="{{ \$address->id }}">{{ \$address->full_name }} — {{ \$address->street }}, {{ \$address->city }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-control">
            <label class="label"><span class="label-text font-medium">Notes (optionnel)</span></label>
            <textarea name="notes" class="textarea textarea-bordered" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary w-full btn-lg">
            Payer avec PayPal
        </button>
    </form>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/shop/confirmation.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', 'Commande confirmée')
@section('content')
<div class="container mx-auto px-4 py-20 text-center max-w-lg">
    <div class="text-6xl mb-6">✓</div>
    <h1 class="font-heading text-3xl font-semibold text-success mb-4">Commande confirmée !</h1>
    <p class="text-base-content/70 mb-8">Merci pour votre commande. Vous allez recevoir un email de confirmation.</p>
    <div class="flex gap-4 justify-center">
        <a href="{{ route('account.orders') }}" class="btn btn-primary">Mes commandes</a>
        <a href="{{ route('shop.home') }}" class="btn btn-ghost">Continuer mes achats</a>
    </div>
</div>
@endsection
BLADE);

// ─────────────────────────────────────────────
// 14. VUES AUTH
// ─────────────────────────────────────────────

makeFile("$root/resources/views/auth/login.blade.php", <<<BLADE
@extends('layouts.guest')
@section('title', 'Connexion')
@section('content')
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h2 class="font-heading text-2xl font-semibold text-center mb-6">Se connecter</h2>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div class="form-control">
                <label class="label"><span class="label-text">Email</span></label>
                <input type="email" name="email" class="input input-bordered" value="{{ old('email') }}" required autofocus>
                @error('email')<p class="text-error text-xs mt-1">{{ \$message }}</p>@enderror
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Mot de passe</span>
                    <a href="{{ route('password.request') }}" class="label-text-alt link link-primary text-xs">Oublié ?</a>
                </label>
                <input type="password" name="password" class="input input-bordered" required>
            </div>
            <div class="form-control">
                <label class="cursor-pointer label justify-start gap-3">
                    <input type="checkbox" name="remember" class="checkbox checkbox-primary checkbox-sm">
                    <span class="label-text">Se souvenir de moi</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-2">Connexion</button>
        </form>
        <p class="text-center text-sm mt-4 text-base-content/60">
            Pas encore de compte ? <a href="{{ route('register') }}" class="link link-primary">S'inscrire</a>
        </p>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/auth/register.blade.php", <<<BLADE
@extends('layouts.guest')
@section('title', 'Créer un compte')
@section('content')
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h2 class="font-heading text-2xl font-semibold text-center mb-6">Créer un compte</h2>
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <div class="form-control">
                <label class="label"><span class="label-text">Nom complet</span></label>
                <input type="text" name="name" class="input input-bordered" value="{{ old('name') }}" required autofocus>
                @error('name')<p class="text-error text-xs mt-1">{{ \$message }}</p>@enderror
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Email</span></label>
                <input type="email" name="email" class="input input-bordered" value="{{ old('email') }}" required>
                @error('email')<p class="text-error text-xs mt-1">{{ \$message }}</p>@enderror
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Mot de passe</span></label>
                <input type="password" name="password" class="input input-bordered" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Confirmer le mot de passe</span></label>
                <input type="password" name="password_confirmation" class="input input-bordered" required>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-2">Créer mon compte</button>
        </form>
        <p class="text-center text-sm mt-4 text-base-content/60">
            Déjà inscrit ? <a href="{{ route('login') }}" class="link link-primary">Se connecter</a>
        </p>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/auth/forgot-password.blade.php", <<<BLADE
@extends('layouts.guest')
@section('title', 'Mot de passe oublié')
@section('content')
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h2 class="font-heading text-2xl font-semibold text-center mb-2">Mot de passe oublié</h2>
        <p class="text-sm text-base-content/60 text-center mb-6">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf
            <div class="form-control">
                <label class="label"><span class="label-text">Email</span></label>
                <input type="email" name="email" class="input input-bordered" required autofocus>
                @error('email')<p class="text-error text-xs mt-1">{{ \$message }}</p>@enderror
            </div>
            <button type="submit" class="btn btn-primary w-full">Envoyer le lien</button>
        </form>
        <p class="text-center text-sm mt-4"><a href="{{ route('login') }}" class="link link-primary">Retour à la connexion</a></p>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/auth/reset-password.blade.php", <<<BLADE
@extends('layouts.guest')
@section('title', 'Réinitialiser le mot de passe')
@section('content')
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h2 class="font-heading text-2xl font-semibold text-center mb-6">Nouveau mot de passe</h2>
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ \$token }}">
            <div class="form-control">
                <label class="label"><span class="label-text">Email</span></label>
                <input type="email" name="email" class="input input-bordered" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Nouveau mot de passe</span></label>
                <input type="password" name="password" class="input input-bordered" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text">Confirmer</span></label>
                <input type="password" name="password_confirmation" class="input input-bordered" required>
            </div>
            <button type="submit" class="btn btn-primary w-full">Réinitialiser</button>
        </form>
    </div>
</div>
@endsection
BLADE);

// ─────────────────────────────────────────────
// 15. VUES ACCOUNT
// ─────────────────────────────────────────────

makeFile("$root/resources/views/account/profile.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', 'Mon profil')
@section('content')
<div class="container mx-auto px-4 py-10 max-w-xl">
    <h1 class="font-heading text-3xl font-semibold mb-8">Mon profil</h1>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body space-y-6">
            <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4">
                @csrf @method('PATCH')
                <div class="form-control">
                    <label class="label"><span class="label-text">Nom</span></label>
                    <input type="text" name="name" class="input input-bordered" value="{{ \$user->name }}" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Email</span></label>
                    <input type="email" name="email" class="input input-bordered" value="{{ \$user->email }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/account/orders.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', 'Mes commandes')
@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="font-heading text-3xl font-semibold mb-8">Mes commandes</h1>
    @forelse(\$orders as \$order)
        <div class="card bg-base-100 shadow-sm border border-base-200 mb-4">
            <div class="card-body flex-row items-center justify-between">
                <div>
                    <p class="font-medium">{{ \$order->order_number }}</p>
                    <p class="text-sm text-base-content/60">{{ \$order->created_at->format('d/m/Y') }}</p>
                </div>
                <div class="badge badge-outline">{{ \$order->status }}</div>
                <span class="font-semibold text-primary">{{ number_format(\$order->total, 2) }} €</span>
            </div>
        </div>
    @empty
        <p class="text-base-content/60">Aucune commande pour le moment.</p>
    @endforelse
    {{ \$orders->links() }}
</div>
@endsection
BLADE);

makeFile("$root/resources/views/account/wishlist.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', 'Mes favoris')
@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="font-heading text-3xl font-semibold mb-8">Mes favoris</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse(\$wishlist?->items ?? [] as \$item)
            <x-product-card :product="\$item->variant->product" />
        @empty
            <p class="col-span-4 text-base-content/60">Votre liste de favoris est vide.</p>
        @endforelse
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/account/addresses.blade.php", <<<BLADE
@extends('layouts.app')
@section('title', 'Mes adresses')
@section('content')
<div class="container mx-auto px-4 py-10 max-w-2xl">
    <h1 class="font-heading text-3xl font-semibold mb-8">Mes adresses</h1>
    <div class="space-y-4 mb-8">
        @forelse(\$addresses as \$address)
            <div class="card bg-base-100 shadow-sm border border-base-200">
                <div class="card-body flex-row items-center justify-between">
                    <div>
                        <p class="font-medium">{{ \$address->full_name }}</p>
                        <p class="text-sm text-base-content/60">{{ \$address->street }}, {{ \$address->city }} {{ \$address->postal_code }}, {{ \$address->country }}</p>
                    </div>
                    <form method="POST" action="{{ route('account.addresses.destroy', \$address) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-ghost btn-xs text-error">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-base-content/60">Aucune adresse enregistrée.</p>
        @endforelse
    </div>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-lg font-heading">Ajouter une adresse</h2>
            <form method="POST" action="{{ route('account.addresses.store') }}" class="grid grid-cols-2 gap-4">
                @csrf
                <div class="form-control col-span-2"><label class="label"><span class="label-text">Nom complet</span></label><input type="text" name="full_name" class="input input-bordered" required></div>
                <div class="form-control"><label class="label"><span class="label-text">Téléphone</span></label><input type="text" name="phone" class="input input-bordered" required></div>
                <div class="form-control col-span-2"><label class="label"><span class="label-text">Rue</span></label><input type="text" name="street" class="input input-bordered" required></div>
                <div class="form-control"><label class="label"><span class="label-text">Ville</span></label><input type="text" name="city" class="input input-bordered" required></div>
                <div class="form-control"><label class="label"><span class="label-text">Code postal</span></label><input type="text" name="postal_code" class="input input-bordered" required></div>
                <div class="form-control col-span-2"><label class="label"><span class="label-text">Pays</span></label><input type="text" name="country" class="input input-bordered" required></div>
                <button type="submit" class="btn btn-primary col-span-2">Enregistrer</button>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE);

// ─────────────────────────────────────────────
// 16. VUES ADMIN
// ─────────────────────────────────────────────

makeFile("$root/resources/views/admin/dashboard.blade.php", <<<BLADE
@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
<h1 class="font-heading text-2xl font-semibold mb-8">Tableau de bord</h1>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat bg-base-100 rounded-2xl shadow-sm">
        <div class="stat-title">Commandes</div>
        <div class="stat-value text-primary">{{ \$stats['total_orders'] }}</div>
    </div>
    <div class="stat bg-base-100 rounded-2xl shadow-sm">
        <div class="stat-title">Chiffre d'affaires</div>
        <div class="stat-value text-primary">{{ number_format(\$stats['total_revenue'], 0) }} €</div>
    </div>
    <div class="stat bg-base-100 rounded-2xl shadow-sm">
        <div class="stat-title">Produits</div>
        <div class="stat-value">{{ \$stats['total_products'] }}</div>
    </div>
    <div class="stat bg-base-100 rounded-2xl shadow-sm">
        <div class="stat-title">Clients</div>
        <div class="stat-value">{{ \$stats['total_customers'] }}</div>
    </div>
</div>
<div class="card bg-base-100 shadow-sm">
    <div class="card-body">
        <h2 class="card-title font-heading">Commandes récentes</h2>
        <div class="overflow-x-auto">
            <table class="table table-sm">
                <thead><tr><th>N°</th><th>Client</th><th>Total</th><th>Statut</th></tr></thead>
                <tbody>
                    @foreach(\$stats['recent_orders'] as \$order)
                        <tr>
                            <td class="font-mono text-xs">{{ \$order->order_number }}</td>
                            <td>{{ \$order->user->name }}</td>
                            <td class="text-primary font-medium">{{ number_format(\$order->total, 2) }} €</td>
                            <td><span class="badge badge-sm badge-outline">{{ \$order->status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/admin/products/index.blade.php", <<<BLADE
@extends('layouts.admin')
@section('title', 'Produits')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-heading text-2xl font-semibold">Produits</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Nouveau produit</a>
</div>
<div class="card bg-base-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="table">
            <thead><tr><th>Produit</th><th>Catégorie</th><th>Prix</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach(\$products as \$product)
                    <tr>
                        <td class="font-medium">{{ \$product->name }}</td>
                        <td>{{ \$product->category->name }}</td>
                        <td class="text-primary">{{ number_format(\$product->base_price, 2) }} €</td>
                        <td>
                            @if(\$product->is_active)
                                <span class="badge badge-success badge-sm">Actif</span>
                            @else
                                <span class="badge badge-error badge-sm">Inactif</span>
                            @endif
                        </td>
                        <td class="flex gap-2">
                            <a href="{{ route('admin.products.edit', \$product) }}" class="btn btn-ghost btn-xs">Éditer</a>
                            <form method="POST" action="{{ route('admin.products.destroy', \$product) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-ghost btn-xs text-error" onclick="return confirm('Supprimer ?')">Suppr.</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ \$products->links() }}</div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/admin/products/create.blade.php", <<<BLADE
@extends('layouts.admin')
@section('title', 'Nouveau produit')
@section('content')
<div class="max-w-2xl">
    <h1 class="font-heading text-2xl font-semibold mb-6">Nouveau produit</h1>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Nom du produit</span></label>
                    <input type="text" name="name" class="input input-bordered" value="{{ old('name') }}" required>
                    @error('name')<p class="text-error text-xs">{{ \$message }}</p>@enderror
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Catégorie</span></label>
                    <select name="category_id" class="select select-bordered" required>
                        @foreach(\$categories as \$cat)
                            <option value="{{ \$cat->id }}">{{ \$cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <textarea name="description" class="textarea textarea-bordered" rows="5" required>{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Prix de base (€)</span></label>
                        <input type="number" name="base_price" class="input input-bordered" step="0.01" min="0" value="{{ old('base_price') }}" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Type de tissu</span></label>
                        <input type="text" name="fabric_type" class="input input-bordered" value="{{ old('fabric_type') }}" required>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Photos du produit</span></label>
                    <input type="file" name="images[]" class="file-input file-input-bordered" multiple accept="image/*">
                </div>
                <div class="flex gap-6">
                    <label class="cursor-pointer label gap-2">
                        <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary" checked>
                        <span class="label-text">Produit actif</span>
                    </label>
                    <label class="cursor-pointer label gap-2">
                        <input type="checkbox" name="is_featured" value="1" class="checkbox checkbox-secondary">
                        <span class="label-text">Mis en avant</span>
                    </label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">Créer le produit</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/admin/products/edit.blade.php", <<<BLADE
@extends('layouts.admin')
@section('title', 'Éditer ' . \$product->name)
@section('content')
<div class="max-w-2xl">
    <h1 class="font-heading text-2xl font-semibold mb-6">Éditer : {{ \$product->name }}</h1>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', \$product) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf @method('PUT')
                <div class="form-control">
                    <label class="label"><span class="label-text">Nom</span></label>
                    <input type="text" name="name" class="input input-bordered" value="{{ old('name', \$product->name) }}" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Catégorie</span></label>
                    <select name="category_id" class="select select-bordered">
                        @foreach(\$categories as \$cat)
                            <option value="{{ \$cat->id }}" {{ \$product->category_id == \$cat->id ? 'selected' : '' }}>{{ \$cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <textarea name="description" class="textarea textarea-bordered" rows="5">{{ old('description', \$product->description) }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Prix (€)</span></label>
                        <input type="number" name="base_price" class="input input-bordered" step="0.01" value="{{ old('base_price', \$product->base_price) }}">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Tissu</span></label>
                        <input type="text" name="fabric_type" class="input input-bordered" value="{{ old('fabric_type', \$product->fabric_type) }}">
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Nouvelles photos</span></label>
                    <input type="file" name="images[]" class="file-input file-input-bordered" multiple accept="image/*">
                </div>
                <div class="flex gap-6">
                    <label class="cursor-pointer label gap-2">
                        <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary" {{ \$product->is_active ? 'checked' : '' }}>
                        <span class="label-text">Actif</span>
                    </label>
                    <label class="cursor-pointer label gap-2">
                        <input type="checkbox" name="is_featured" value="1" class="checkbox checkbox-secondary" {{ \$product->is_featured ? 'checked' : '' }}>
                        <span class="label-text">Mis en avant</span>
                    </label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/admin/orders/index.blade.php", <<<BLADE
@extends('layouts.admin')
@section('title', 'Commandes')
@section('content')
<h1 class="font-heading text-2xl font-semibold mb-6">Commandes</h1>
<div class="card bg-base-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="table">
            <thead><tr><th>N° commande</th><th>Client</th><th>Total</th><th>Date</th><th>Statut</th><th>Action</th></tr></thead>
            <tbody>
                @foreach(\$orders as \$order)
                    <tr>
                        <td class="font-mono text-xs">{{ \$order->order_number }}</td>
                        <td>{{ \$order->user->name }}</td>
                        <td class="text-primary font-medium">{{ number_format(\$order->total, 2) }} €</td>
                        <td class="text-xs text-base-content/60">{{ \$order->created_at->format('d/m/Y') }}</td>
                        <td><span class="badge badge-sm badge-outline">{{ \$order->status }}</span></td>
                        <td><a href="{{ route('admin.orders.show', \$order) }}" class="btn btn-ghost btn-xs">Voir</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ \$orders->links() }}</div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/admin/orders/show.blade.php", <<<BLADE
@extends('layouts.admin')
@section('title', 'Commande ' . \$order->order_number)
@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-ghost btn-sm">← Retour</a>
    <h1 class="font-heading text-2xl font-semibold">Commande {{ \$order->order_number }}</h1>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 card bg-base-100 shadow-sm">
        <div class="card-body">
            <h2 class="card-title font-heading">Articles</h2>
            <table class="table table-sm">
                <thead><tr><th>Produit</th><th>Variante</th><th>Qté</th><th>Prix</th></tr></thead>
                <tbody>
                    @foreach(\$order->items as \$item)
                        <tr>
                            <td>{{ \$item->product_name }}</td>
                            <td class="text-xs text-base-content/60">{{ \$item->variant_label }}</td>
                            <td>{{ \$item->quantity }}</td>
                            <td class="text-primary">{{ number_format(\$item->unit_price * \$item->quantity, 2) }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="divider"></div>
            <div class="flex justify-between font-semibold"><span>Total</span><span class="text-primary">{{ number_format(\$order->total, 2) }} €</span></div>
        </div>
    </div>
    <div class="space-y-4">
        <div class="card bg-base-100 shadow-sm">
            <div class="card-body">
                <h2 class="card-title font-heading text-sm">Changer le statut</h2>
                <form method="POST" action="{{ route('admin.orders.status', \$order) }}">
                    @csrf @method('PATCH')
                    <select name="status" class="select select-bordered w-full mb-3">
                        @foreach(['pending','processing','shipped','completed','cancelled'] as \$s)
                            <option value="{{ \$s }}" {{ \$order->status === \$s ? 'selected' : '' }}>{{ ucfirst(\$s) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary btn-sm w-full">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/admin/categories/index.blade.php", <<<BLADE
@extends('layouts.admin')
@section('title', 'Catégories')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="font-heading text-2xl font-semibold">Catégories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">+ Nouvelle catégorie</a>
</div>
<div class="card bg-base-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="table">
            <thead><tr><th>Nom</th><th>Produits</th><th>Statut</th><th>Action</th></tr></thead>
            <tbody>
                @foreach(\$categories as \$cat)
                    <tr>
                        <td class="font-medium">{{ \$cat->name }}</td>
                        <td>{{ \$cat->products_count }}</td>
                        <td><span class="badge badge-sm {{ \$cat->is_active ? 'badge-success' : 'badge-error' }}">{{ \$cat->is_active ? 'Actif' : 'Inactif' }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.categories.destroy', \$cat) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-ghost btn-xs text-error" onclick="return confirm('Supprimer ?')">Suppr.</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/admin/categories/create.blade.php", <<<BLADE
@extends('layouts.admin')
@section('title', 'Nouvelle catégorie')
@section('content')
<div class="max-w-md">
    <h1 class="font-heading text-2xl font-semibold mb-6">Nouvelle catégorie</h1>
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Nom</span></label>
                    <input type="text" name="name" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Catégorie parente (optionnel)</span></label>
                    <select name="parent_id" class="select select-bordered">
                        <option value="">Aucune (catégorie principale)</option>
                        @foreach(\$parents as \$parent)
                            <option value="{{ \$parent->id }}">{{ \$parent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="cursor-pointer label gap-2">
                    <input type="checkbox" name="is_active" value="1" class="checkbox checkbox-primary" checked>
                    <span class="label-text">Catégorie active</span>
                </label>
                <div class="flex gap-3">
                    <button type="submit" class="btn btn-primary">Créer</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-ghost">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE);

makeFile("$root/resources/views/admin/reviews/index.blade.php", <<<BLADE
@extends('layouts.admin')
@section('title', 'Avis clients')
@section('content')
<h1 class="font-heading text-2xl font-semibold mb-6">Avis clients</h1>
<div class="card bg-base-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="table">
            <thead><tr><th>Client</th><th>Produit</th><th>Note</th><th>Commentaire</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach(\$reviews as \$review)
                    <tr>
                        <td>{{ \$review->user->name }}</td>
                        <td class="text-sm">{{ \$review->product->name }}</td>
                        <td>{{ \$review->rating }}/5</td>
                        <td class="text-sm text-base-content/70 max-w-xs truncate">{{ \$review->comment }}</td>
                        <td><span class="badge badge-sm {{ \$review->is_approved ? 'badge-success' : 'badge-warning' }}">{{ \$review->is_approved ? 'Approuvé' : 'En attente' }}</span></td>
                        <td class="flex gap-2">
                            @if(!\$review->is_approved)
                                <form method="POST" action="{{ route('admin.reviews.approve', \$review) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-ghost btn-xs text-success">Approuver</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.reviews.destroy', \$review) }}">
                                @csrf @method('DELETE')
                                <button class="btn btn-ghost btn-xs text-error">Suppr.</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4">{{ \$reviews->links() }}</div>
</div>
@endsection
BLADE);

// ─────────────────────────────────────────────
// 17. ROUTES
// ─────────────────────────────────────────────

makeFile("$root/routes/admin.php", <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class)->except(['show', 'edit', 'update']);

Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
PHP);

overwriteFile("$root/routes/web.php", <<<PHP
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\WishlistController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\ReviewController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\AddressController;

// ── Auth ──────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ── Shop ──────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('shop.home');
Route::get('/catalogue', [ProductController::class, 'index'])->name('shop.catalogue');
Route::get('/produit/{product:slug}', [ProductController::class, 'show'])->name('shop.product');

// Panier
Route::get('/panier', [CartController::class, 'index'])->name('shop.cart');
Route::post('/panier/ajouter', [CartController::class, 'add'])->name('shop.cart.add');
Route::patch('/panier/{item}', [CartController::class, 'update'])->name('shop.cart.update');
Route::delete('/panier/{item}', [CartController::class, 'remove'])->name('shop.cart.remove');
Route::delete('/panier', [CartController::class, 'clear'])->name('shop.cart.clear');

// Favoris
Route::post('/favoris/toggle', [WishlistController::class, 'toggle'])->name('shop.wishlist.toggle')->middleware('auth');

// Checkout & PayPal
Route::middleware('auth')->group(function () {
    Route::get('/commande', [CheckoutController::class, 'index'])->name('shop.checkout');
    Route::post('/commande', [CheckoutController::class, 'process'])->name('shop.checkout.process');
    Route::get('/commande/success', [CheckoutController::class, 'success'])->name('shop.checkout.success');
    Route::get('/commande/cancel', [CheckoutController::class, 'cancel'])->name('shop.checkout.cancel');
    Route::get('/commande/confirmation', [CheckoutController::class, 'confirmation'])->name('shop.orders.confirmation');
});

// Avis
Route::post('/produit/{product}/avis', [ReviewController::class, 'store'])->name('shop.review.store')->middleware('auth');

// ── Compte client ─────────────────────────────────────────
Route::middleware('auth')->prefix('compte')->name('account.')->group(function () {
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profil/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/commandes', [OrderController::class, 'index'])->name('orders');
    Route::get('/commandes/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/favoris', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/adresses', [AddressController::class, 'index'])->name('addresses');
    Route::post('/adresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::delete('/adresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
});
PHP);

// ─────────────────────────────────────────────
// 18. CONFIG PAYPAL
// ─────────────────────────────────────────────

makeFile("$root/config/paypal.php", <<<PHP
<?php

return [
    'mode'    => env('PAYPAL_MODE', 'sandbox'),
    'sandbox' => [
        'client_id'     => env('PAYPAL_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_CLIENT_SECRET', ''),
    ],
    'live' => [
        'client_id'     => env('PAYPAL_LIVE_CLIENT_ID', ''),
        'client_secret' => env('PAYPAL_LIVE_CLIENT_SECRET', ''),
    ],
    'payment_action' => 'Sale',
    'currency'       => 'EUR',
    'notify_url'     => '',
    'locale'         => 'fr_FR',
    'validate_ssl'   => true,
];
PHP);

// ─────────────────────────────────────────────
// 19. CSS & JS
// ─────────────────────────────────────────────

overwriteFile("$root/resources/css/app.css", <<<CSS
@import 'tailwindcss';
@plugin "daisyui";

@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Inter:wght@400;500&display=swap');
CSS);

overwriteFile("$root/resources/js/app.js", <<<JS
import Alpine from 'alpinejs'

window.Alpine = Alpine
Alpine.start()
JS);

// ─────────────────────────────────────────────
// 20. STORAGE DOSSIERS
// ─────────────────────────────────────────────

makeDir("$root/storage/app/public/products");
makeDir("$root/storage/app/public/categories");

// ─────────────────────────────────────────────
// RAPPORT FINAL
// ─────────────────────────────────────────────

echo "\n";
echo "╔══════════════════════════════════════════════════════╗\n";
echo "║       AFRISOIE SHOP — Setup terminé !                ║\n";
echo "╠══════════════════════════════════════════════════════╣\n";
echo "║  Fichiers créés / modifiés : " . count($created) . str_repeat(' ', max(0, 24 - strlen(count($created)))) . "║\n";
echo "║  Fichiers ignorés (existants) : " . count($skipped) . str_repeat(' ', max(0, 20 - strlen(count($skipped)))) . "║\n";
echo "╠══════════════════════════════════════════════════════╣\n";
echo "║  PROCHAINES ÉTAPES :                                 ║\n";
echo "║  1. composer require srmklive/paypal                 ║\n";
echo "║  2. composer require intervention/image-laravel      ║\n";
echo "║  3. composer require barryvdh/laravel-dompdf         ║\n";
echo "║  4. npm install -D daisyui alpinejs @tailwindcss/vite║\n";
echo "║  5. npm install                                      ║\n";
echo "║  6. php artisan migrate                              ║\n";
echo "║  7. php artisan storage:link                         ║\n";
echo "║  8. npm run dev                                      ║\n";
echo "║  9. php artisan serve                                ║\n";
echo "║  10. Supprimer ce fichier setup-ecommerce.php        ║\n";
echo "╚══════════════════════════════════════════════════════╝\n\n";

echo "Détail des fichiers créés :\n";
foreach ($created as $f) {
    echo "  ✓ $f\n";
}
if ($skipped) {
    echo "\nFichiers ignorés (déjà existants) :\n";
    foreach ($skipped as $f) {
        echo "  - $f\n";
    }
}
echo "\n";
