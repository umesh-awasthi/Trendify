@extends('layouts.app')

@section('title', 'Checkout Success')

@section('content')
<div class="container mx-auto max-w-lg mt-8 p-6 bg-white shadow-lg rounded-lg text-center">
    <h1 class="text-2xl font-bold text-green-600">Payment Successful!</h1>
    <p class="text-gray-700 mt-2">Thank you for your order. Your order ID is {{ session('order_id') }}.</p>

    @if(session('billing_address'))
        <h3 class="text-lg font-semibold mt-4">Billing Details</h3>
        <p><strong>First_name:</strong> {{ session('billing_address')->first_name }}</p>
        <p><strong>Last_name:</strong> {{ session('billing_address')->last_name }}</p>
        <p><strong>Street:</strong> {{ session('billing_address')->street }}</p>
        <p><strong>State:</strong> {{ session('billing_address')->state }}</p>
        <p><strong>Zipcode:</strong> {{ session('billing_address')->zipcode }}</p>
        <p><strong>Country:</strong> {{ session('billing_address')->country }}</p>
    @else
        <p class="text-gray-500 mt-4">No billing details provided.</p>
    @endif
</div>
@endsection

