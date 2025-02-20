<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;

class AgentController extends Controller
{
    public function index()
    { 
      try{
          $agents = User::where('role', 'agent')->first();
      
        //   print_r($agent);
          
          return view('agent.dashboard', compact('agents'));
      }catch(\Exception $e){
        print_r($e->getMessage());
      }

    }

    public function getagent()
    { 
      try{
          $agents = User::where('role', 'agent')->get();
      
        //   print_r($agent);
          
          return view('admin.agents.index', compact('agents'));
      }catch(\Exception $e){
        print_r($e->getMessage());
      }

    }

    public function customers()
    {
        $customers = Customer::all();
        return view('agent.customers', compact('customers'));
    }

    public function orders()
    {
        $orders = Order::with('customer')->get();
        return view('agent.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,shipped,delivered'
        ]);

        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated successfully');
    }

    public function products()
    {
        $products = Product::all();
        return view('agent.products', compact('products'));
    }

    public function createProduct()
    {
        return app(ProductController::class)->create();
    }

    public function storeProduct(Request $request)
    {
        return app(ProductController::class)->store($request);
    }

    public function showProduct(Product $product)
    {
        return app(ProductController::class)->show($product);
    }

    public function editProduct(Product $product)
    {
        return app(ProductController::class)->edit($product);
    }

    public function updateProduct(Request $request, Product $product)
    {
        return app(ProductController::class)->update($request, $product);
    }

    public function destroyProduct(Product $product)
    {
        return app(ProductController::class)->destroy($product);
    }

    public function reports()
    {
        $salesData = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->get();

        return view('agent.reports', compact('salesData'));
    }

    public function adminTasks()
    {
        return view('agent.admin-tasks');
    }

    public function createAgent()
    {
        return view('admin.create-agent');
    }

    public function storeAgent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'permissions' => 'nullable|array'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'agent',
            'permissions' => json_encode($request->permissions ?? [])
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Agent created successfully');
    }

    public function editAgentPermissions(User $agent)
    {
        $permissions = [
            'manage_products',
            'manage_orders',
            'manage_customers',
            'view_reports',
            'admin_tasks'
        ];
        
        return view('admin.edit-agent-permissions', compact('agent', 'permissions'));
    }

    public function updateAgent(Request $request, User $agent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$agent->id,
            'permissions' => 'nullable|array'
        ]);

        $agent->update([
            'name' => $request->name,
            'email' => $request->email,
            'permissions' => json_encode($request->permissions ?? [])
        ]);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent updated successfully');
    }

    public function updateAgentPermissions(Request $request, User $agent)
    {
        $request->validate([
            'permissions' => 'nullable|array'
        ]);

        $agent->update([
            'permissions' => json_encode($request->permissions ?? [])
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Agent permissions updated successfully');
    }

    public function destroyAgent(User $agent)
    {
        // Delete related sessions first
        $agent->sessions()->delete();
        
        // Then delete the agent
        $agent->delete();
        
        return redirect()->route('admin.agents.getagent')
            ->with('success', 'Agent deleted successfully');
    }
}
