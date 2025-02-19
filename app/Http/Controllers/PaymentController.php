<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class PaymentController extends Controller
{
    // Show the Checkout Page with Total Amount after Discount
    public function showCharge()
    {
        // Clear any old session data related to pricing
        //session()->forget(['total_after_discount', 'cart_total']);
    
        $discount = session('discount');
        $cartItems = [];
    
        // Check if user is logged in
        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();
            $cartItems = Cart::where('customer_id', $customerId)
                ->with('product')
                ->get();
              
        } else {
            // For guests, use session cart data
            $cartItems = session('cart', []);
        }
    
        // Calculate total price safely
        $grandTotal = collect($cartItems)->sum(fn($item) => (float) $item['total_price'] ?? 0);
    //   print_r($grandTotal);
     
        // Apply discount
        $totalAfterDiscount = max(0, $grandTotal - $discount);
        // print_r($totalAfterDiscount);
        // die('ccc');
    
        // Store the correct amount in session
        session()->put('total_after_discount', $totalAfterDiscount);
        
        return view('checkout.charge', compact('cartItems', 'totalAfterDiscount'));
    }
    

    // Process the Payment
    public function processPayment(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'stripeToken' => 'required',
    ]);

    try {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // Recalculate to ensure correct values
        $discount = session('discount', 0);
        $cartItems = [];

        if (Auth::guard('customer')->check()) {
            $customerId = Auth::guard('customer')->id();
            $cartItems = Cart::where('customer_id', $customerId)
                ->with('product')
                ->get();
        } else {
            $cartItems = session('cart', []);
        }

        // Calculate total price
        $grandTotal = collect($cartItems)->sum(fn($item) => (float) $item['total_price'] ?? 0);
        $totalAfterDiscount = max(1, $grandTotal - $discount); // Prevent zero or negative payments

        // Update session with correct amount
        session()->put('total_after_discount', $totalAfterDiscount);

        // Create a Stripe customer
        $customer = \Stripe\Customer::create([
            'email' => $request->email,
            'description' => 'Customer for payment',
            'source' => $request->stripeToken,
        ]);

        // Process the charge
        $charge = \Stripe\Charge::create([
            "amount" => $totalAfterDiscount * 100, // Convert to cents
            "currency" => "usd",
            "customer" => $customer->id,
            "description" => "Payment from " . $request->email,
            "receipt_email" => $request->email,
        ]);

        //Clear session and DB cart after successful payment
        if (Auth::guard('customer')->check()) {
            Cart::where('customer_id', $customerId)->delete();
        } else {
            session()->forget(['cart', 'discount', 'total_after_discount']);
        }

        return redirect()->route('checkout.success')->with('success', 'Payment successful!');
    } catch (\Exception $e) {
        \Log::error('Stripe Payment Error: ' . $e->getMessage());
        return back()->with('error', 'Payment failed: ' . $e->getMessage());
    }
}
  }

