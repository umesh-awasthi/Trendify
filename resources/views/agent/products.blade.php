@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <span>Product Management</span>
                   
                </div>

                <div class="card-body">
                    {{-- <!-- Add Product Form -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Add New Product</h3>
                        <form action="{{ route('agent.products.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                                <input type="text" name="name" id="name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            
                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                                <textarea name="description" id="description" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                                <input type="number" step="0.01" name="price" id="price" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                Add Product
                            </button>
                        </form>
                    </div> --}}

                    <!-- Product List -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Existing Products</h3>
                        @if ($products->isEmpty())
                            <p>No products found.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2">Name</th>
                                            <th class="px-4 py-2">Description</th>
                                            <th class="px-4 py-2">Price</th>
                                            <th class="px-4 py-2">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td class="border px-4 py-2">{{ $product->name }}</td>
                                                <td class="border px-4 py-2">{{ $product->description }}</td>
                                                <td class="border px-4 py-2">${{ number_format($product->price, 2) }}</td>
                                                <td class="border px-4 py-2">
                                                    <a href=" {{ route('agent.products.edit', $product->id) }}"
                                                        class="text-blue-500 hover:text-blue-700">
                                                        Edit
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
                <a href="{{ route('agent.dashboard') }}" class="bg-gray-500 text-black px-4 py-2 rounded-md hover:bg-gray-600">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
{{-- <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Edit Product</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                &times;
            </button>
        </div>
        <form id="editForm" method="POST" class="edit-product-form">
            <input type="hidden" name="_method" value="PUT">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="edit_name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" name="name" id="edit_name" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            
            <div class="mb-4">
                <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="edit_description" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
            </div>
            
            <div class="mb-4">
                <label for="edit_price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" step="0.01" name="price" id="edit_price" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            
            <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded-md hover:bg-blue-600">
                Update Product
            </button>
        </form>
        
           
       
    </div>
   
</div> --}}

@endsection
