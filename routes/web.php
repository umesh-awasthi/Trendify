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
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\GlobalConfigurationController;

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

// Authentication Routes (Admin, Customer, and Agent)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Admin Login Routes
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

// Customer Login Routes
Route::get('/customer/login', [AuthController::class, 'showCustomerLoginForm'])->name('customer.login');
Route::post('/customer/login', [AuthController::class, 'customerLogin']);

// Agent Login Routes
Route::get('/agent/login', [AuthController::class, 'showAgentLoginForm'])->name('agent.login');
Route::post('/agent/login', [AuthController::class, 'agentLogin']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::prefix('admin')->group(function() {
    Route::get('/password/forget', [AuthController::class, 'showAdminResetForm'])->name('admin.password.request');
    Route::post('/password/email', [AuthController::class, 'sendAdminResetLinkEmail'])->name('admin.password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showAdminResetFormWithToken'])->name('admin.password.reset');
    Route::post('/password/reset', [AuthController::class, 'adminReset'])->name('admin.password.update');
    
});

Route::prefix('customer')->group(function() {
    Route::get('/password/forget', [AuthController::class, 'showCustomerResetForm'])->name('customer.password.request');
    Route::post('/password/email', [AuthController::class, 'sendCustomerResetLinkEmail'])->name('customer.password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showCustomerResetFormWithToken'])->name('customer.password.reset');
    Route::post('/password/reset', [AuthController::class, 'customerReset'])->name('customer.password.update');
});

Route::prefix('agent')->group(function() {
    Route::get('/password/forget', [AuthController::class, 'showAgentResetForm'])->name('agent.password.request');
    Route::post('/password/email', [AuthController::class, 'sendAgentResetLinkEmail'])->name('agent.password.email');
    Route::get('/password/reset/{token}', [AuthController::class, 'showAgentResetFormWithToken'])->name('agent.password.reset');
    Route::post('/password/reset', [AuthController::class, 'agentReset'])->name('agent.password.update');
});

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
    
    // Global Configuration Routes
  
    Route::get('/admin/global-configuration/bridge_data', function () {
        return view('admin.bridge_data');
    })->name('admin.bridge_data');

    Route::post('/admin/global-configuration/bridge_data', [GlobalConfigurationController::class, 'save'])->name('admin.bridge_data.save');
    Route::get('/admin/global-configuration/great_schools', function () {
        return view('admin.great_schools');
    })->name('admin.great_schools');

    Route::post('/admin/global-configuration/great_schools', [GlobalConfigurationController::class, 'save'])->name('admin.great_schools.save');
    Route::get('/admin/global-configuration/walkscore', function () {
        return view('admin.walkscore');
    })->name('admin.walkscore');

    Route::post('/admin/global-configuration/walkscore', [GlobalConfigurationController::class, 'save'])->name('admin.walkscore.save');
});

// Agent Dashboard
Route::middleware(AgentAuthMiddleware::class)->group(function() {
    Route::get('/agent/dashboard', function () {
        return view('agent.dashboard');
    })->name('agent.dashboard');
    Route::get('/agent/orders', [AgentController::class, 'orders'])->name('agent.orders');
    Route::get('/agent/products', [AgentController::class, 'products'])->name('agent.products');
    Route::get('/agent/my-customers', [AgentController::class, 'myCustomers'])->name('agent.my-customers');
    Route::get('/agent/report', [AgentController::class, 'report'])->name('agent.reports');
    Route::get('/agent/admin-tasks', [AgentController::class, 'adminTasks'])->name('agent.admin-tasks');
});

// Customer Dashboard Route
Route::middleware(CustomerAuthMiddleware::class)->group(function() {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');
});

Route::resource('properties', PropertyController::class);
