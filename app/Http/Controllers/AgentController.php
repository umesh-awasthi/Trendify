<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;

class AgentController extends Controller
{
    /**
     * Display customers list
     */
    public function customers()
    {
        $customers = Customer::all();
        return view('agent.customers', compact('customers'));
    }

    /**
     * Display orders list
     */
    public function orders()
    {
        $orders = Order::with('customer')->get();
        return view('agent.orders', compact('orders'));
    }

    /**
     * Update order status
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered'
        ]);

        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated successfully');
    }

    /**
     * Display product management
     */
    public function products()
    {
        $products = Product::all();
        return view('agent.products', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function createProduct()
    {
        return app(ProductController::class)->create();
    }

    /**
     * Store a newly created product
     */
    public function storeProduct(Request $request)
    {
        return app(ProductController::class)->store($request);
    }

    /**
     * Display the specified product
     */
    public function showProduct(Product $product)
    {
        return app(ProductController::class)->show($product);
    }

    /**
     * Show the form for editing the specified product
     */
    public function editProduct(Product $product)
    {
        return app(ProductController::class)->edit($product);
    }

    /**
     * Update the specified product
     */
    public function updateProduct(Request $request, Product $product)
    {
        return app(ProductController::class)->update($request, $product);
    }

    /**
     * Remove the specified product
     */
    public function destroyProduct(Product $product)
    {
        return app(ProductController::class)->destroy($product);
    }
  

    /**
     * Display reports
     */
    public function reports()
    {
        // Generate reports data
        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->get();

        return view('agent.reports', compact('salesData'));
    }

    /**
     * Display admin assistance tasks
     */
    public function adminTasks()
    {
        // Placeholder for admin assistance tasks
        return view('agent.admin-tasks');
    }
}
