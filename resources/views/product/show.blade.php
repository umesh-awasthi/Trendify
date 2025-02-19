@extends('layouts.app')

@section('title', 'View Product')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <h2 class="mb-0">Product Details</h2>
                @if($product->category)
                    <span class="mx-3">|</span>
                    <div class="d-flex align-items-center">
                        @if($product->category->image)
                            <img src="{{ asset('storage/'.$product->category->image) }}" 
                                 alt="{{ $product->category->name }}" 
                                 class="rounded-circle me-2"
                                 style="width: 30px; height: 30px; object-fit: cover;">
                        @endif
                        <a href="{{ route('category.show', $product->category->id) }}" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-folder"></i> {{ $product->category->name }}
                        </a>
                    </div>
                @endif
            </div>
            <div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-list"></i> All Products
                </a>
                @if($product->category)
                    <a href="{{ route('category.show', $product->category->id) }}" 
                       class="btn btn-primary btn-sm ms-2">
                        <i class="bi bi-arrow-left"></i> Back 
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" 
                             class="img-fluid rounded shadow" 
                             alt="{{ $product->name }}"
                             style="width: 100%; object-fit: cover;">
                    @else
                        <div class="bg-light p-5 text-center rounded">
                            <p class="text-muted">No image available</p>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="mb-3">
                        <strong>Name:</strong>
                        <p class="lead">{{ $product->name }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Short Notes:</strong>
                        <p>{{ $product->short_notes }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Description:</strong>
                        <p>{{ $product->description }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Price:</strong>
                        <p class="h4 text-primary">${{ number_format($product->price, 2) }}</p>
                    </div>
                    @if(Auth::guard('admin')->check())
                        <div class="btn-group">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 
