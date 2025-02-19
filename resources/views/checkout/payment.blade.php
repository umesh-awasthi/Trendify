{{-- @extends('layouts.app')

@section('title', 'Checkout - Shipping Details')

@section('content')
    <div class="container mx-auto max-w-lg mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Shipping Details</h2>

        <!-- Display Order ID and Total Price -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Order ID</label>
            <p class="text-gray-900">{{ session('order_id', 'N/A') }}</p>
        </div>

        <!-- Cart Details -->
        <h3 class="text-lg font-semibold mt-8">Order Details</h3>

        @if ($cart && count($cart) > 0)
            <div class="mb-4">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left border border-gray-300">Product</th>
                            <th class="px-4 py-2 text-left border border-gray-300">Price</th>
                            <th class="px-4 py-2 text-left border border-gray-300">Quantity</th>
                            <th class="px-4 py-2 text-left border border-gray-300">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $item)
                            <tr>
                                <td class="px-4 py-2 border border-gray-300">{{ $item['name'] }}</td>
                                <td class="px-4 py-2 border border-gray-300">${{ number_format($item['price'], 2) }}</td>
                                <td class="px-4 py-2 border border-gray-300">{{ $item['quantity'] }}</td>
                                <td class="px-4 py-2 border border-gray-300">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-red-500">Your cart is empty!</p>
        @endif

        <!-- Display Total Price -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Total Price</label>
            @php
                $discount = session('discount', 0);
                $totalAfterDiscount = session('total_after_discount', 0);
            @endphp

            @if ($discount > 0)
                <p class="text-gray-900">
                    Original Total: <span class="line-through">${{ number_format($totalAfterDiscount + $discount, 2) }}</span><br>
                    Discount: <span class="text-red-500">-${{ number_format($discount, 2) }}</span><br>
                    <strong>Total After Discount: ${{ number_format($totalAfterDiscount, 2) }}</strong>
                </p>
            @else
                <p class="text-gray-900">${{ number_format($totalAfterDiscount, 2) }}</p>
            @endif
        </div>

        <!--  Display Shipping Address -->
        @if (session()->has('shipping_address'))
            @php
                $shippingAddress = session('shipping_address');
            @endphp

            <h3 class="text-lg font-semibold mt-4">Shipping Address</h3>
            <p>
                {{ $shippingAddress['street'] }},
                {{ $shippingAddress['state'] }}, {{ $shippingAddress['zipcode'] }}<br>
                {{ $shippingAddress['country'] }}<br>
                {{ $shippingAddress['phone'] }}
            </p>

            <h3 class="text-lg font-semibold mt-4">Shipping Method</h3>
            <p>{{ $shippingAddress['shipping_method'] }}</p>

        @elseif (session()->has('guest_shipping'))
            @php
                $guestShipping = session('guest_shipping');
            @endphp

            <h3 class="text-lg font-semibold mt-4">Shipping Address</h3>
            <p>
                {{ $guestShipping['street'] }},
                {{ $guestShipping['state'] }}, {{ $guestShipping['zipcode'] }}<br>
                {{ $guestShipping['country'] }}<br>
                {{ $guestShipping['phone'] }}
            </p>

            <h3 class="text-lg font-semibold mt-4">Shipping Method</h3>
            <p>{{ $guestShipping['shipping_method'] }}</p>

        @else
            <p class="text-red-500">No shipping address found. Please add your address.</p>
        @endif

        <!--  Add Billing Address Button -->
        <div class="mt-2 mb-4">
            <form action="{{ route('checkout.addBillingAddress') }}" method="get">
                @csrf
                <button type="submit" class="bg-blue-900 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded-lg">
                    Add Your Billing Address
                </button>
            </form>
        </div>

        <!-- Payment Method Selection -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Payment Information</h2>

            <form action="{{ route('show.charge') }}" method="get">
                @csrf

                <!-- Proceed to Payment Button -->
                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-blue-900 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded-lg">
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection --}}

@extends('layouts.app')

@section('title', 'Checkout - Shipping Details')

@section('content')
    <div class="container mx-auto max-w-lg mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Shipping Details</h2>

        <!-- Display Order ID and Total Price -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Order ID</label>
            <p class="text-gray-900">{{ session('order_id', 'N/A') }}</p>
        </div>

        <!-- Cart Details -->
        <h3 class="text-lg font-semibold mt-8">Order Details</h3>

        @php
            use Illuminate\Support\Collection;

            // Check if cart is a Collection (logged-in user) or a session array (guest)
            $isCollection = $cart instanceof Collection;
            $cartItems = $isCollection ? $cart : session('cart', []);
        @endphp
    {{-- {{dd($cartItems);}} --}}
        @if ($cartItems && count($cartItems) > 0)
            <div class="mb-4">
                <table class="w-full table-auto border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="px-4 py-2 text-left border border-gray-300">Product</th>
                            <th class="px-4 py-2 text-left border border-gray-300">Price</th>
                            <th class="px-4 py-2 text-left border border-gray-300">Quantity</th>
                            <th class="px-4 py-2 text-left border border-gray-300">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr>
                                <td class="px-4 py-2 border border-gray-300">{{ $item['name'] }}</td>
                                <td class="px-4 py-2 border border-gray-300">
                                    ${{ number_format($item['price'], 2) }}
                                </td>
                                <td class="px-4 py-2 border border-gray-300">{{ $item['quantity'] }}</td>
                                <td class="px-4 py-2 border border-gray-300">
                                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                </td>
                                
                            </tr>


                            {{-- <tbody>
                                @foreach ($cartItems as $item)
                                <td class="px-4 py-2 border border-gray-300">{{ $item['name'] }}</td>
                                <td class="px-4 py-2 border border-gray-300">
                                    ${{ number_format($item['price'], 2) }}
                                </td>
                                <td class="px-4 py-2 border border-gray-300">{{ $item['quantity'] }}</td>
                                <td class="px-4 py-2 border border-gray-300">
                                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                </td>
                                
                                @endforeach
                            </tbody> --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-red-500">Your cart is empty!</p>
        @endif

        <!-- Display Total Price -->
        <div class="mb-4">
            <label class="block text-gray-700 font-medium">Total Price</label>
            @php
                $discount = session('discount', 0);
                $totalAfterDiscount = session('total_after_discount', 0);
            @endphp

            @if ($discount > 0)
                <p class="text-gray-900">
                    Original Total: <span class="line-through">${{ number_format($totalAfterDiscount + $discount, 2) }}</span><br>
                    Discount: <span class="text-red-500">-${{ number_format($discount, 2) }}</span><br>
                    <strong>Total After Discount: ${{ number_format($totalAfterDiscount, 2) }}</strong>
                </p>
            @else
                <p class="text-gray-900">${{ number_format($totalAfterDiscount, 2) }}</p>
            @endif
        </div>

        <!-- Display Shipping Address -->
        @if (session()->has('shipping_address'))
            @php
                $shippingAddress = session('shipping_address');
            @endphp

            <h3 class="text-lg font-semibold mt-4">Shipping Address</h3>
            <p>
                {{ $shippingAddress['street'] }},
                {{ $shippingAddress['state'] }}, {{ $shippingAddress['zipcode'] }}<br>
                {{ $shippingAddress['country'] }}<br>
                {{ $shippingAddress['phone'] }}
            </p>

            <h3 class="text-lg font-semibold mt-4">Shipping Method</h3>
            <p>{{ $shippingAddress['shipping_method'] }}</p>

        @elseif (session()->has('guest_shipping'))
            @php
                $guestShipping = session('guest_shipping');
            @endphp

            <h3 class="text-lg font-semibold mt-4">Shipping Address</h3>
            <p>
                {{ $guestShipping['street'] }},
                {{ $guestShipping['state'] }}, {{ $guestShipping['zipcode'] }}<br>
                {{ $guestShipping['country'] }}<br>
                {{ $guestShipping['phone'] }}
            </p>

            <h3 class="text-lg font-semibold mt-4">Shipping Method</h3>
            <p>{{ $guestShipping['shipping_method'] }}</p>

        @else
            <p class="text-red-500">No shipping address found. Please add your address.</p>
        @endif

        <!-- Add Billing Address Button -->
        <div class="mt-2 mb-4">
            <form action="{{ route('checkout.addBillingAddress') }}" method="get">
                @csrf
                <button type="submit" class="bg-blue-900 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded-lg">
                    Add Your Billing Address
                </button>
            </form>
        </div>

        <!-- Payment Method Selection -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Payment Information</h2>

            <form action="{{ route('show.charge') }}" method="get">
                @csrf

                <!-- Proceed to Payment Button -->
                <div class="flex justify-end mt-8">
                    <button type="submit" class="bg-blue-900 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded-lg">
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

