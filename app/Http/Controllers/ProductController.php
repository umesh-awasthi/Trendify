<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
                          ->latest()
                          ->paginate(3); // Show 9 products per page
        return view('product.list', ['products' => $products]);
    }

    public function create()
    {
        // print_r("xdfgdfg");
        // die("gdfg");
        return view('product.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'short_notes' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        $product->load('category');  // Eager load the category
        return view('product.show', ['product' => $product]);
    }

    public function edit(Product $product)
    {
        return view('product.edit', ['product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'short_notes' => 'required',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    public function compare()
    {
        $productIds = Session::get('compare_products', []);
        $products = Product::whereIn('id', $productIds)->get();
        
        return view('product.compare', compact('products'));
    }

    public function addToCompare(Product $product)
    {
        $compareList = Session::get('compare_products', []);
        
        if (in_array($product->id, $compareList)) {
            return response()->json([
                'success' => false,
                'message' => 'Product is already in comparison list'
            ], 400);
        }
        
        if (count($compareList) >= 4) {
            return response()->json([
                'success' => false,
                'message' => 'You can compare up to 4 products only'
            ], 400);
        }
        
        $compareList[] = $product->id;
        Session::put('compare_products', $compareList);
        
        $products = Product::whereIn('id', $compareList)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image_url' => $product->image ? asset('storage/'.$product->image) : null
                ];
            });
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to comparison',
            'count' => count($compareList),
            'products' => $products
        ]);
    }

    public function removeFromCompare(Product $product)
    {
        $compareList = Session::get('compare_products', []);
        
        if (($key = array_search($product->id, $compareList)) !== false) {
            unset($compareList[$key]);
            Session::put('compare_products', array_values($compareList));
        }
        
        $products = Product::whereIn('id', $compareList)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image_url' => $product->image ? asset('storage/'.$product->image) : null
                ];
            });
        
        return response()->json([
            'success' => true,
            'message' => 'Product removed from comparison',
            'count' => count($compareList),
            'products' => $products
        ]);
    }
}
