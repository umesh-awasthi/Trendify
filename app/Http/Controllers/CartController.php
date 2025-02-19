<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Coupon;

class CartController extends Controller
{
    // Show Cart Page
    // Show Cart Page
    // public function showCart()
    // {
    //     if (Auth::guard('customer')->check()) {
    //         //  Clear session cart when switching to a logged-in user
    //         session()->forget('cart');

    //         $cart = Cart::where('customer_id', Auth::guard('customer')->id())->with('product')->get();
    //     } else {
    //         $cart = collect(session()->get('cart', []));
    //     }

    //     $discount = session('discount', 0);

    //     //  Fix Total Calculation (Handles Both DB & Session-Based Cart)
    //     $grandTotal = $cart->sum(fn ($item) => 
    //         isset($item->product) 
    //             ? ($item->product->price * $item->quantity)  // Customer Cart (DB)
    //             : (($item['price'] ?? 0) * ($item['quantity'] ?? 1)) // Guest Cart (Session)
    //     );

    //     //  Reset to 0 When Cart is Empty
    //     if ($cart->isEmpty()) {
    //         $grandTotal = 0;
    //         $discount = 0;
    //         session()->forget('discount'); // Remove discount when cart is empty
    //     }

    //     $totalAfterDiscount = max($grandTotal - $discount, 0);

    //     return view('cart.cart', compact('cart', 'grandTotal', 'discount', 'totalAfterDiscount'));
    // }
    public function showCart()
    {
        if (Auth::guard('customer')->check()) {
            session()->forget(['cart', 'discount']);
            \Log::info('Customer Cart Loaded', ['customer_id' => Auth::guard('customer')->id()]);
            $cart = Cart::where('customer_id', Auth::guard('customer')->id())->with('product')->get();
        } else {
            \Log::info('Guest Cart Loaded');
            $cart = collect(session()->get('cart', []));
        }

        //  Get Discount from Session (Fix for Customers)
        $discount = session('discount');

        //  Fix: Calculate Grand Total Properly
        $grandTotal = $cart->sum(
            fn($item) =>
            isset($item->product)
            ? ($item->product->price * $item->quantity)  // Customer Cart (DB)
            : (($item['price'] ?? 0) * ($item['quantity'] ?? 1)) // Guest Cart (Session)
        );

        //  Ensure Discount is Applied Correctly
        $discount = min($discount, $grandTotal);
        $totalAfterDiscount = max($grandTotal - $discount, 0);

        //  Debug: Log Correct Values
        \Log::info('Cart Items:', $cart->toArray());
        \Log::info('Grand Total Calculated:', ['grandTotal' => $grandTotal]);
        \Log::info('Final Total After Discount:', ['totalAfterDiscount' => $totalAfterDiscount]);

        return view('cart.cart', compact('cart', 'grandTotal', 'discount', 'totalAfterDiscount'));
    }




    public function addToCart(Request $request, $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }
        if (Auth::guard('customer')->check()) {
            // LOGGED-IN CUSTOMER: Save to Database**
            $customerId = Auth::guard('customer')->id();

            // 🔹 Check if the product is already in the cart
            $cartItem = Cart::where('customer_id', $customerId)
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                // If the product exists, update the quantity and total price

                $cartItem->quantity += $request->input('quantity',1);

                $cartItem->total_price = $cartItem->quantity * $product->price;
                $cartItem->save();
            } else {
                //  If the product is not in the cart, create a new cart entry

                $total_price = $product->price * $request->input('quantity', 1);


                $cartNew = Cart::create([
                    'customer_id' => $customerId,
                    'product_id' => $product->id,
                    'quantity' => $request->quantity ?? 1,
                    // 'price' => $product->price,
                    'total_price' => $total_price,
                ]);
                //   
            }


            // Clear Guest Session Cart After Login**
            session()->forget('cart');

        } else {
            // GUEST USER: Save to Session**
            $cart = session()->get('cart', []);

            if (isset($cart[$productId])) {
                // If product exists, update quantity & total price
                $cart[$productId]['quantity'] += $request->quantity ?? 1;
                $cart[$productId]['total_price'] = $cart[$productId]['quantity'] * $cart[$productId]['price'];
            } else {
                //  If product does not exist, add a new entry
                $cart[$productId] = [
                    "product_id" => $product->id,
                    "name" => $product->name,
                    "quantity" => $request->quantity ?? 1,
                    "price" => $product->price,
                    "image" => $product->image ?? 'default.jpg',
                    "total_price" => $product->price * ($request->quantity ?? 1),
                ];
            }

            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    // Update Quantity in Cart
    public function updateQuantity(Request $request, $productId)
    {
        $product = Product::find($productId);
        if (Auth::guard('customer')->check()) {
            $cartItem = Cart::where('customer_id', Auth::guard('customer')->id())
                ->where('product_id', $productId)
                ->first();
            if ($cartItem) {
                $cartItem->quantity = $request->input('quantity');
                $cartItem->total_price = $cartItem->quantity * $product->price;
                // print_r($cartItem->quantity);
                // print_r($cartItem->total_price);
                // die('ccc');
                $cartItem->save();
            }
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] = (int) $request->quantity;
                $cart[$productId]['total_price'] = $cart[$productId]['price'] * $cart[$productId]['quantity'];
                session()->put('cart', $cart);
            }
        }

        return response()->json(['success' => true, 'message' => 'Cart updated successfully!']);
    }

    // Remove Item from Cart
    public function remove($productId)
    {
        $cartEmpty = false;
        $discount = session('discount', 0); // Get the applied discount

        if (Auth::guard('customer')->check()) {
            // Remove item from the database cart
            Cart::where('customer_id', Auth::guard('customer')->id())
                ->where('product_id', $productId)
                ->delete();

            // Check if the cart is now empty
            $cartEmpty = !Cart::where('customer_id', Auth::guard('customer')->id())->exists();
        } else {
            // Handle cart in session
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }

            // If the session cart is empty, remove it
            if (!session()->has('cart') || empty(session('cart'))) {
                session()->forget('cart');
                $cartEmpty = true;
            }
        }

        // If the cart is empty, remove the discount
        if ($cartEmpty) {
            session()->forget('discount');
        }

        // Recalculate Grand Total
        $cart = Auth::guard('customer')->check()
            ? Cart::where('customer_id', Auth::guard('customer')->id())->with('product')->get()
            : collect(session()->get('cart', []));

        $grandTotal = $cart->sum(
            fn($item) =>
            isset($item->product)
            ? ($item->product->price * $item->quantity)
            : (($item['price'] ?? 0) * ($item['quantity'] ?? 1))
        );

        if ($cart->isEmpty()) {
            $grandTotal = 0;
        }

        $totalAfterDiscount = max($grandTotal - $discount, 0);

        return response()->json([
            'success' => true,
            'message' => 'Product removed successfully!',
            'grandTotal' => number_format($grandTotal, 2),
            'totalAfterDiscount' => number_format($totalAfterDiscount, 2),
            'cartEmpty' => $cartEmpty
        ]);
    }


    // Apply Coupon (Only One Coupon at a Time)
    public function applyCoupon(Request $request)
    {
        $couponCode = strtoupper($request->coupon_code);

        $coupon = Coupon::where('code', $couponCode)->where('is_active', true)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }

        //  Store Discount in Session (Fix for Customers)
        $discount = (float) $coupon->discount;

        session(['discount' => $discount]);



        //  Fix: Ensure Consistent Cart Calculation
        if (Auth::guard('customer')->check()) {
            $cart = Cart::where('customer_id', Auth::guard('customer')->id())->with('product')->get();
        } else {
            $cart = collect(session('cart', []));
        }

        // Compute Grand Total Properly (price * quantity)
        $grandTotal = $cart->sum(
            fn($item) =>
            isset($item->product)
            ? ($item->product->price * $item->quantity)
            : (($item['price'] ?? 0) * ($item['quantity'] ?? 1))
        );

        // Ensure Discount Does Not Exceed Grand Total
        $discount = min($discount, $grandTotal);

        $totalAfterDiscount = max($grandTotal - $discount, 0);

        session(['total_after_discount' => $totalAfterDiscount]);

        \Log::info('Coupon Applied', [
            'coupon' => $couponCode,
            'discount' => $discount,
            'grandTotal' => $grandTotal,
            'totalAfterDiscount' => $totalAfterDiscount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => $discount,
            'grandTotal' => $grandTotal,
            'totalAfterDiscount' => $totalAfterDiscount
        ]);
    }


    // Get Grand Total After Discount
    public function getCartTotal()
    {
        $cart = session('cart', []);
        $discount = session('discount', 0);

        $grandTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return response()->json([
            'grandTotal' => number_format($grandTotal, 2),
            'discount' => number_format($discount, 2),
            'totalAfterDiscount' => number_format($grandTotal - $discount, 2)
        ]);
    }
}