@extends('layouts.app')

@section('title', 'Checkout - Shipping Details')

@section('content')


    <div class="container mx-auto max-w-lg mt-8 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Shipping Details</h2>

        <form action="{{ url('checkout') }}" method="POST">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium">First Name</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Last Name -->
            <div class="mb-4">
                <label for="lastname" class="block text-gray-700 font-medium">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="{{ old('lastname') }}" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('lastname')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Street Address -->
            <div class="mb-4">
                <label for="street" class="block text-gray-700 font-medium">Street Address</label>
                <input type="text" id="street" name="street" value="{{ old('street') }}" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('street')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Country -->
            <div class="mb-4">
                <label for="country" class="block text-gray-700 font-medium">Country</label>
                <select id="country" name="country" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="" disabled selected>Select Country</option>
                    <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>USA</option>
                    <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                    <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>UK</option>
                    <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                    <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                </select>
                @error('country')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- State -->
            <div class="mb-4">
                <label for="state" class="block text-gray-700 font-medium">State</label>
                <input type="text" id="state" name="state" value="{{ old('state') }}" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('state')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Zip Code -->
            <div class="mb-4">
                <label for="zipcode" class="block text-gray-700 font-medium">ZIP Code</label>
                <input type="text" id="zipcode" name="zipcode" value="{{ old('zipcode') }}" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('zipcode')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone Number -->
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-medium">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                    class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <!-- Shipping Methods -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Shipping Methods</h3>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="shipping_method" value="flat_rate" class="mr-2" required>
                        <span>Flat Rate - $5.00</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="shipping_method" value="table_rate" class="mr-2" required>
                        <span>Table Rate - $15.00</span>
                    </label>

                </div>
                <!-- Hidden Fields for Discount and Total -->
                <div> 
                    <input type="hidden" name="discount" value="{{ session('discount', 0) }}">
                    <input type="hidden" name="total_after_discount"
                        value="{{ session('total_after_discount', $grandTotal) }}">
                </div>
            </div>

            <!-- Next Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-900 hover:bg-blue-700 text-blue font-bold py-2 px-4 rounded-lg">
                    Next
                </button>
            </div>

        </form>
    </div>
@endsection
