<?php

// app/Http/Controllers/CheckoutController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Product;
use App\Models\OrderItems;
use Stripe\Stripe;

use Stripe\Charge;

class CheckoutController extends Controller
{
    // Display checkout page
    
    public function checkout()
    {
        if (Auth::guard('customer')->check()) {
           
            \Log::info('Customer Checkout Loaded', ['customer_id' => Auth::guard('customer')->id()]);
            $cart = Cart::where('customer_id', Auth::guard('customer')->id())->with('product')->get();
            // print_r($cart);
            //     die();
          
            $grandTotal = $cart->sum(fn ($item) => $item->product->price * $item->quantity);
        } else {
           
            \Log::info('Guest Checkout Loaded');
            $cart = session('cart', []);
    
          
            $grandTotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));
        }
    
        
        $discount = session('discount', 0);
        $totalAfterDiscount = session('total_after_discount', $grandTotal);
    
       
        \Log::info('Checkout Data', [
            'cart' => $cart,
            'grandTotal' => $grandTotal,
            'discount' => $discount,
            'totalAfterDiscount' => $totalAfterDiscount
        ]);
    
        return view('checkout.index', compact('cart', 'grandTotal', 'discount', 'totalAfterDiscount'));
    }
    
    public function processCheckout(Request $request)
    {
        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->user()->id;
        } else {
            $customerId = null;
        }
    
        //  Validate Checkout Form
        $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
            'lastname' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
            'email' => 'required|email',
            'street' => 'required|string',
            'country' => 'required|string',
            'state' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/'],
            'zipcode' => ['required', 'numeric', 'regex:/^\d{6}(-\d{4})?$/'],
            'phone' => ['required', 'numeric', 'regex:/^\+?[0-9]{10,15}$/'],
            'shipping_method' => 'required|string',
        ], [
            'zipcode.regex' => 'The ZIP code must be a valid format (e.g., 123454 or 12345-6789).',
            'phone.regex' => 'The phone number must be a valid format (e.g., +1234567890).',
        ]);
       // Store the address in the addresses table
        $address = Address::create([
            'customer_id' => $customerId ?? null, // nullable for guest users
            'first_name' => $request->name,
            'last_name' => $request->lastname,
            'email' => $request->email,
            'street' => $request->street,
            'state' => $request->state,
            'country' => $request->country,
            'zipcode' => $request->zipcode,
            'type' => 'shipping', // Assuming this is a shipping address
        ]);
        //  Store shipping address in session
        session(['shipping_address' => $request->only([
            'name', 'lastname', 'email', 'street', 'country', 'state', 'zipcode', 'phone', 'shipping_method'
        ])]);
    
        // Fetch Cart Items
        if ($customerId) {
            // Fetch cart from database for logged-in customers
            $cart = Cart::where('customer_id', $customerId)->with('product')->get();
        } else {
            // Fetch cart from session for guests
            $cart = session('cart', []);
        }
    
        //  Check if Cart is Empty
        if ((is_array($cart) && empty($cart)) || (is_object($cart) && $cart->isEmpty())) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty!');
        }
    
        //  Calculate Total Price
        $grandTotal = 0;
        if ($customerId) {
            // Ensure correct total price calculation for customers
            $grandTotal = $cart->sum(fn ($item) => $item->product->price * $item->quantity);
        } else {
            // Ensure correct total price calculation for guests
            foreach ($cart as $item) {
                $grandTotal += $item['total_price']; // Using `total_price` from session cart
            }
        }
    
        //  Apply Discount
        $discount = session('discount', 0);
        $totalAfterDiscount = max(0, $grandTotal - $discount); // Ensure total is never negative
    
        //  Log Data for Debugging
        \Log::info('Final Checkout Totals', [
            'customer_id' => $customerId,
            'cart' => $cart,
            'grandTotal' => $grandTotal,
            'discount' => $discount,
            'totalAfterDiscount' => $totalAfterDiscount,
        ]);
    
        // Create Order
        $order = Order::create([
            'customer_id' => $customerId,
            'total' => $totalAfterDiscount,
            'status' => 'pending',
        ]);
        foreach ($cart as $item) {
            
            $product = Product::find($item['product_id']);
           
            OrderItems::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'total_price' => $product->price * $item['quantity'],
            ]);
        }
    
        //  Store Order ID in Session
        session(['order_id' => $order->id, 'total_after_discount' => $totalAfterDiscount]);
    
        return redirect()->route('checkout.payment',['cart'=>$cart]);
    }
    
    // public function processCheckout(Request $request)
    // {
    //     if (Auth::guard('customer')->check()) {
    //         $customerId = Auth::guard('customer')->user()->id;
    //     } else {
    //         $customerId = null;
    //     }
    
    //     // Validate Checkout Form
    //     $request->validate([
    //         'name' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
    //         'lastname' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
    //         'email' => 'required|email',
    //         'street' => 'required|string',
    //         'country' => 'required|string',
    //         'state' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/'],
    //         'zipcode' => ['required', 'numeric', 'regex:/^\d{6}(-\d{4})?$/'],
    //         'phone' => ['required', 'numeric', 'regex:/^\+?[0-9]{10,15}$/'],
    //         'shipping_method' => 'required|string',
    //     ], [
    //         'zipcode.regex' => 'The ZIP code must be a valid format (e.g., 123454 or 12345-6789).',
    //         'phone.regex' => 'The phone number must be a valid format (e.g., +1234567890).',
    //     ]);
    
    //     // Fetch Cart Items
    //     if ($customerId) {
    //         $cart = Cart::where('customer_id', $customerId)->with('product')->get();
    //     } else {
    //         $cart = session('cart', []);
    //     }
    
    //     // Check if Cart is Empty
    //     if ((is_array($cart) && empty($cart)) || (is_object($cart) && $cart->isEmpty())) {
    //         return redirect()->route('cart.show')->with('error', 'Your cart is empty!');
    //     }
    
    //     // Calculate Grand Total
    //     $grandTotal = 0;
    //     if ($customerId) {
    //         $grandTotal = $cart->sum(fn($item) => $item->product->price * $item->quantity);
    //     } else {
    //         foreach ($cart as $item) {
    //             $grandTotal += $item['total_price'];
    //         }
    //     }
    
    //     // Apply Discount
    //     $discount = session('discount', 0);
    //     $totalAfterDiscount = max(0, $grandTotal - $discount);
    
    //     // Create Order
    //     $order = Order::create([
    //         'customer_id' => $customerId,
    //         'total' => $totalAfterDiscount,
    //         'status' => 'pending',
    //     ]);
    
    //     // Store Shipping Address Linked to Order
    //     Address::create([
    //         'customer_id' => $customerId ?? null, // Nullable for guest users
    //         'order_id' => $order->id, // Link to the order
    //         'first_name' => $request->name,
    //         'last_name' => $request->lastname,
    //         'email' => $request->email,
    //         'street' => $request->street,
    //         'state' => $request->state,
    //         'country' => $request->country,
    //         'zipcode' => $request->zipcode,
    //         'type' => 'shipping',
    //     ]);
    
    //     // Store Order Items
    //     foreach ($cart as $item) {
    //         if ($customerId) {
    //             $product = $item->product; // Access product directly for logged-in customers
    //             $quantity = $item->quantity;
    //         } else {
    //             $product = Product::find($item['product_id']); // Fetch from database for guests
    //             $quantity = $item['quantity'];
    //         }
    
    //         OrderItems::create([
    //             'order_id' => $order->id,
    //             'product_id' => $product->id,
    //             'quantity' => $quantity,
    //             'price' => $product->price,
    //             'total_price' => $product->price * $quantity,
    //         ]);
    //     }
    
    //     // Store Order Details in Session
    //     session([
    //         'order_id' => $order->id,
    //         'total_after_discount' => $totalAfterDiscount,
    //         'shipping_address' => $request->only([
    //             'name', 'lastname', 'email', 'street', 'country', 'state', 'zipcode', 'phone', 'shipping_method'
    //         ]),
    //     ]);
    
    //     return redirect()->route('checkout.payment');
    // }
    

    public function calculateCartTotal($cart)
    {
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Apply discount if it exists in the session
        $discount = session('discount', 0);
        return $total - $discount;
    }
    // Display the payment page
    public function showPaymentPage()
    {
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;
    
        // Fetch Cart Items
        if ($customerId) {
            // Fetch cart from database for logged-in customers and transform it into a consistent structure
            $cart = Cart::where('customer_id', $customerId)
                ->with('product')
                ->get()
                ->map(function ($cartItem) {
                    return [
                        'product_id' => $cartItem->product->id ?? null,
                        'name' => $cartItem->product->name ?? 'Unknown Product',
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->price ?? 0,
                        'image' => $cartItem->product->image ?? 'default.jpg',
                        'total_price' => $cartItem->total_price,
                    ];
                });
        } else {
            // Fetch cart from session for guests
            $cart = session('cart', []);
        }
    
        // Retrieve order details
        $orderId = Session::get('order_id');
        $order = $orderId ? Order::find($orderId) : null;
    
        // If order is missing, redirect back
        if (!$order) {
            return redirect()->route('checkout')->with('error', 'Order not found. Please try again.');
        }
    
        // Retrieve shipping address
        $shippingAddress = session('shipping_address');
    
        // If no shipping address exists, redirect to checkout
        if (!$shippingAddress) {
            return redirect()->route('checkout')->with('error', 'Please provide a shipping address.');
        }
    
        // Retrieve total after discount (if available)
        $totalAfterDiscount = session('total_after_discount', $order->total ?? 0);
    
        return view('checkout.payment', compact('order', 'shippingAddress', 'totalAfterDiscount', 'cart'));
    }
    
   

    public function processBilling(Request $request)
    {
        // Validate the billing form data
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
            'lastname' => ['required', 'string', 'regex:/^[a-zA-Z]+$/'],
            'email' => ['required', 'email'],
            'street' => 'required|string',
            'country' => 'required|string',
            'state' => ['required', 'string', 'regex:/^[a-zA-Z\s]+$/'],
            'zipcode' => ['required', 'numeric', 'regex:/^\d{5,6}(-\d{4})?$/'],
            'phone' => ['required', 'numeric', 'regex:/^\+?[0-9]{10,15}$/']
        ], [
            'zipcode.regex' => 'The ZIP code must be a valid format (e.g., 12345 or 12345-6789).',
            'phone.regex' => 'The phone number must be a valid format (e.g., +1234567890).',
        ]);

        // Determine customer ID (null for guests)
        $customerId = Auth::guard('customer')->check() ? Auth::guard('customer')->user()->id : null;

        // Store billing address in the database
        $billingAddress = Address::create([
            'customer_id' => $customerId,
            'first_name' => $validatedData['name'],
            'last_name' => $validatedData['lastname'],
            'email' => $validatedData['email'],
            'street' => $request->street,
            'state' => $request->state,
            'country' => $request->country,
            'zipcode' => $request->zipcode,
            'type' => 'billing',
        ]);

        // Store billing address in session for later use
        session(['billing_address' => $billingAddress]);

        // Redirect to the success page after storing billing details
        return redirect()->route('show.charge');
    }
    public function addBillingAddress()
    {
        return view('checkout.billing');
    }
    public function showSuccessPage()
    {
        Session::forget('cart');
        return view('checkout.success');
     }
  


}
