<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CustomerController;
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

    // Customer management routes
    Route::get('/admin/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
    Route::delete('/admin/customers/{customer}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');
    
    // Agent management routes
    Route::get('/agent', [AgentController::class, 'index'])->name('admin.agents.index');
    Route::get('/admin/agents', [AgentController::class, 'getagent'])->name('admin.agents.getagent');
    Route::get('/admin/agents/create', [AgentController::class, 'createAgent'])->name('admin.agents.create');
    Route::post('/admin/agents', [AgentController::class, 'storeAgent'])->name('admin.agents.store');
    Route::get('/admin/agents/{agent}/edit', [AgentController::class, 'editAgentPermissions'])->name('admin.agents.edit');
    Route::put('/admin/agents/{agent}', [AgentController::class, 'updateAgentPermissions'])->name('admin.agents.update');
    Route::delete('/admin/agents/{agent}', [AgentController::class, 'destroyAgent'])->name('admin.agents.destroy');
    Route::get('/admin/agents/{agent}/assign-customers', [AgentController::class, 'showAssignCustomersForm'])->name('admin.agents.assign-customers');
    Route::post('/admin/agents/{agent}/assign-customers', [AgentController::class, 'assignCustomers'])->name('admin.agents.assign-customers.store');
    Route::get('/admin/agents/{agent}/assigned-customers', [AgentController::class, 'viewAssignedCustomers'])->name('admin.agents.assigned-customers');
});

// Customer Dashboard (Protected for Customers)
Route::middleware(CustomerAuthMiddleware::class)->group(function(){
    Route::get('/customer/dashboard', function () {
        return view('customer.dashboard');
    })->name('customer.dashboard');
});

// Agent Dashboard (Protected for Agents)
Route::middleware(AgentAuthMiddleware::class)->group(function(){
    Route::get('/agent/dashboard', [AgentController::class, 'index'])->name('agent.dashboard');
    
    // Agent specific routes
    Route::get('/agent/customers', [AgentController::class, 'customers'])->name('agent.customers');
    Route::get('/agent/orders', [AgentController::class, 'orders'])->name('agent.orders');
    Route::post('/agent/orders/{order}/status', [AgentController::class, 'updateOrderStatus'])->name('agent.orders.update-status');
    // Product management routes
    Route::get('/agent/products', [AgentController::class, 'products'])->name('agent.products');
    Route::get('/agent/products/create', [AgentController::class, 'createProduct'])->name('agent.products.create');
    Route::post('/agent/products', [AgentController::class, 'storeProduct'])->name('agent.products.store');
    Route::get('/agent/products/{product}', [AgentController::class, 'showProduct'])->name('agent.products.show');
    Route::get('/agent/products/{product}/edit', [AgentController::class, 'editProduct'])->name('agent.products.edit');
    Route::put('/agent/products/{product}', [AgentController::class, 'updateProduct'])->name('agent.products.update');
    // Route::delete('/agent/products/{product}', [AgentController::class, 'destroyProduct'])->name('agent.products.destroy');
    Route::get('/agent/reports', [AgentController::class, 'reports'])->name('agent.reports');
    Route::get('/agent/admin-tasks', [AgentController::class, 'adminTasks'])->name('agent.admin-tasks');
    Route::get('/agent/my-customers', [AgentController::class, 'myCustomers'])->name('agent.my-customers');
});
