@extends('layouts.app')

@section('title', $category->name)

@push('styles')
    <link href="{{ asset('css/category.css') }}" rel="stylesheet">
@endpush

@section('content')
    {{-- Breadcrumb Navigation --}}
    <nav aria-label="breadcrumb" class="category-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('category.index') }}">Property Categories</a>
            </li>
            @if($category->parent)
                <li class="breadcrumb-item">
                    <a href="{{ route('category.show', $category->parent->id) }}">
                        {{ $category->parent->name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    {{-- Category Header --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                @if($category->image)
                    <div class="col-md-3">
                        <img src="{{ asset('storage/'.$category->image) }}" 
                             class="img-fluid rounded shadow category-image" 
                             alt="{{ $category->name }}">
                    </div>
                @endif
                <div class="col">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">{{ $category->name }}</h2>
                            <p class="text-muted mb-2">{{ $category->description }}</p>
                            @if($showProducts)
                                <span class="badge bg-primary">{{ $category->products->count() }} Products</span>
                            @else
                                <span class="badge bg-info">{{ $category->children->count() }} Subcategories</span>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('category.index') }}" class="btn btn-secondary">Back</a>
                            @if(Auth::guard('admin')->check())
                                <div class="btn-group">
                                    <a href="{{ route('category.edit', $category->id) }}" class="btn btn-warning">Edit</a>
                                    <form action="{{ route('category.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>

    {{-- Show Products or Subcategories --}}
    @if($showSubcategories && $category->children->count() > 0)
        <div class="subcategory-grid">
            @foreach($category->children as $child)
                <div class="card subcategory-card">
                    @if($child->image)
                        <img src="{{ asset('storage/'.$child->image) }}" 
                             class="card-img-top" 
                             alt="{{ $child->name }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $child->name }}</h5>
                        <p class="card-text">{{ Str::limit($child->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-secondary">
                                {{ $child->products->count() }} Products
                            </span>
                            <a href="{{ route('category.show', $child->id) }}" 
                               class="btn btn-primary btn-sm">
                                View Products
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Show Products --}}
    @if($showProducts)
        <div class="row">
            @forelse($category->products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card product-card h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->short_notes }}</p>
                            <p class="card-text">
                                <strong class="text-primary">${{ number_format($product->price, 2) }}</strong>
                            </p>
                            <div class="btn-group">
                                <a href="{{ route('products.show', $product->id) }}" 
                                   class="btn btn-info btn-sm">View</a>
                                @if(Auth::guard('admin')->check())
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="category-empty-state">
                        <i class="bi bi-inbox"></i>
                        <p>No products found in this category.</p>
                        @if(Auth::guard('admin')->check() || Auth::guard('customer')->check())
                            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                                Add New Product
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    @endif
@endsection 