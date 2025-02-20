<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\CustomerAuthMiddleware;
use App\Http\Middleware\AgentAuthMiddleware;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::resource('products', ProductController::class);

// Public routes - accessible to everyone
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Cart routes - Available for everyone (Unauthenticated users and customers)
Route::post('/cart/add/{productId}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update/{productId}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::get('/cart/total', [CartController::class, 'getCartTotal'])->name('cart.total');

// Checkout routes
Route::get('checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('checkout', [CheckoutController::class, 'processCheckout']);
Route::get('payment', [CheckoutController::class, 'showPaymentPage'])->name('checkout.payment');
Route::post('payment', [CheckoutController::class, 'processPayment']);
Route::post('/checkout/billing', [CheckoutController::class, 'processBilling'])->name('checkout.billing');
Route::get('/checkout/success', [CheckoutController::class, 'showSuccessPage'])->name('checkout.success');
Route::get('/checkout/billingAddress', [CheckoutController::class,'addBillingAddress'])->name('checkout.addBillingAddress');
Route::get('/charge', [PaymentController::class, 'showcharge'])->name('show.charge');
Route::post('/charge', [PaymentController::class, 'processPayment'])->name('payment.charge');

// Category routes
Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('category.show');

// Authentication Routes (Admin & Customer in One Controller)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Comparison functionality
Route::get('/compare', [ProductController::class, 'compare'])->name('products.compare');
Route::post('/compare/add/{product}', [ProductController::class, 'addToCompare'])->name('products.addToCompare');
Route::delete('/compare/remove/{product}', [ProductController::class, 'removeFromCompare'])->name('products.removeFromCompare');

// Admin Dashboard (Protected for Admins)
Route::middleware(AdminAuthMiddleware::class)->group(function(){
    Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Customer Dashboard (Protected for Customers)
Route::middleware(CustomerAuthMiddleware::class)->group(function(){
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
});

// Agent Dashboard (Protected for Agents)
Route::middleware(AgentAuthMiddleware::class)->group(function(){
    Route::get('/agent/dashboard', function () {
        return view('agent.dashboard');
    })->name('agent.dashboard');
});
