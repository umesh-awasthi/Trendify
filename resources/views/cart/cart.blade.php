@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
    <div class="container">
        <h2>Your Shopping Cart</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @php
            $cartEmpty = $cart->isEmpty() && session()->missing('cart');
            $discount = session('discount', 0);
            $grandTotal = 0;
        @endphp

        @if ($cartEmpty)
            <p>Your cart is empty.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if (Auth::guard('customer')->check())
                        @foreach ($cart as $item)
                            @php
                                $itemTotal = $item->product->price * $item->quantity;
                                $grandTotal += $itemTotal;
                            @endphp
                            <tr>
                                <td><img src="{{ asset('storage/' . $item->product->image) }}" alt="Product Image"
                                        width="50"></td>
                                <td>{{ $item->product->name }}</td>
                                <td>${{ number_format($item->product->price, 2) }}</td>
                                <td>
                                    <form class="updateQuantityForm" data-product-id="{{ $item->product->id }}">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                            class="form-control quantityInput" style="width: 80px;">
                                        <button type="submit" class="btn btn-primary mt-2">Update</button>
                                    </form>
                                </td>
                                <td class="itemTotal">${{ number_format($itemTotal, 2) }}</td>
                                <td>
                                    <form class="removeItemForm" data-product-id="{{ $item->product->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @if (session()->has('cart'))
                            @foreach (session('cart') as $item)
                                @php
                                    $itemTotal = $item['price'] * $item['quantity'];
                                    $grandTotal += $itemTotal;
                                @endphp
                                <tr>
                                    <td><img src="{{ asset('storage/' . ($item['image'] ?? 'default.jpg')) }}"
                                            alt="Product Image" width="50"></td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td>
                                        <form class="updateQuantityForm" data-product-id="{{ $item['product_id'] }}">
                                            @csrf
                                            <input type="number" name="quantity" value="{{ $item['quantity'] ?? 1 }}" min="1"
                                                class="form-control quantityInput" style="width: 80px;">
                                            <button type="submit" class="btn btn-primary mt-2">Update</button>
                                        </form>
                                    </td>
                                    <td class="itemTotal">${{ number_format($itemTotal, 2) }}</td>
                                    <td>
                                        <form class="removeItemForm" data-product-id="{{ $item['product_id'] }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td>
                                    {{-- <td>
                                        <form action="{{ route('cart.update', $productId) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <div class="input-group">
                                                <input type="number" name="quantity" value="{{ $quantity }}"
                                                    min="1" class="form-control"
                                                    style="width: 70px; margin-right: 10px;">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td>${{ number_format($price, 2) }}</td>
                                    <td>${{ number_format($itemTotal, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove', $productId) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @endforeach
                        @endif
                    @endif
                </tbody>
            </table>

            {{-- <h4>Total: <span id="grandTotal">${{ number_format($grandTotal, 2) }}</span></h4> --}}
            <h3>Total: ${{ number_format($totalAfterDiscount ?? ($grandTotal ?? 0), 2) }}</h3>
            <!-- ✅ Use correct variable -->


            <div class="form-group">
                <input type="checkbox" id="useCoupon"> Do you have a coupon code?
            </div>

            <div id="couponSection" style="display: none;">
                <form id="couponForm">
                    @csrf
                    <div class="form-group">
                        <input type="text" id="coupon_code" name="coupon_code" class="form-control"
                            placeholder="Enter Coupon Code">
                        <button type="submit" class="btn btn-success mt-2">Apply Coupon</button>
                    </div>
                </form>
                <div id="couponMessage"></div>
                <div>
                    @if (!$cartEmpty)
                        @if ($discount)
                            <h5>Discount Applied: <span id="discountAmount">${{ sprintf('%.2f', $discount) }}</span></h5>
                        @endif
                        <h4>Total After Discount: <span
                                id="totalAfterDiscount">${{ sprintf('%.2f', $grandTotal - $discount) }}</span></h4>
                    @endif

                </div>
            </div>

            <form action="{{ route('checkout') }}" method="get">
                <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
            </form>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle coupon section
            $('#useCoupon').change(function() {
                $('#couponSection').toggle(this.checked);
            });

            // Apply coupon
            $('#couponForm').submit(function(e) {
                e.preventDefault();
                let couponCode = $('#coupon_code').val();
                let csrfToken = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('cart.applyCoupon') }}",
                    method: "POST",
                    data: {
                        _token: csrfToken,
                        coupon_code: couponCode
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#couponMessage').html('<div class="alert alert-success">' +
                                response.message + '</div>');
                            $('#discountAmount').text('-$' + response.discount);
                            $('#grandTotal').text('$' + response.grandTotal.toFixed(2));
                            $('#totalAfterDiscount').text('$' + response.totalAfterDiscount
                                .toFixed(2));
                        } else {
                            $('#couponMessage').html('<div class="alert alert-danger">' +
                                response.message + '</div>');
                        }
                    }
                });
            });

            // Update quantity
            $('.updateQuantityForm').submit(function(e) {
                e.preventDefault();
                let productId = $(this).data('product-id');
                let quantity = $(this).find('.quantityInput').val();
                let csrfToken = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('cart.update', ['productId' => '__ID__']) }}".replace(
                        '__ID__', productId),
                    method: "POST",
                    data: {
                        _token: csrfToken,
                        quantity: quantity
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });

            // Remove item
            $('.removeItemForm').submit(function(e) {
                e.preventDefault();
                let productId = $(this).data('product-id');
                let csrfToken = $('input[name="_token"]').val();

                $.ajax({
                    url: "{{ route('cart.remove', ['productId' => '__ID__']) }}".replace('__ID__',
                        productId),
                    method: "POST",
                    data: {
                        _token: csrfToken,
                        _method: "DELETE"
                    },
                    success: function() {
                        location.reload();
                    }
                });
            });
        });
    </script>
@endsection
