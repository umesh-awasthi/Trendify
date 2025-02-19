@extends('layouts.app')

@section('title', 'Products List')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Products</h1>
        @if(Auth::guard('admin')->check())
        {{-- <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a> --}}
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
        
        @endif
    </div>
    <div>
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    </div>

    <div class="row">
        @forelse($products as $product)
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
                        <p class="card-text"><strong>Price: ${{ number_format($product->price, 2) }}</strong></p>
                        
                        @if($product->category)
                            <div class="d-flex align-items-center mb-3">
                                @if($product->category->image)
                                    <img src="{{ asset('storage/'.$product->category->image) }}" 
                                         alt="{{ $product->category->name }}" 
                                         class="category-badge-icon">
                                @endif
                                <a href="{{ route('category.show', $product->category->id) }}" 
                                   class="badge bg-secondary text-decoration-none category-badge">
                                    {{ $product->category->name }}
                                </a>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary btn-sm compare-btn" 
                                    data-product-id="{{ $product->id }}">
                                <i class="bi bi-plus-square"></i> 
                                <span class="compare-text">Add to Compare</span>
                            </button>
                        </div>
                        
                        @if (!Auth::guard('admin')->check()) 
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <form action="{{ route('cart.add', ['productId' => $product->id]) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-primary fw-bold py-2 px-4 rounded">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        @endif
                            <div class="btn-group">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">View</a>
                                @if(Auth::guard('admin')->check())
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
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
                <div class="alert alert-info text-center">
                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                    No products found
                    {{-- <div class="mt-3">
                        @if(Auth::guard('admin')->check() || Auth::guard('customer')->check())
                            <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
                                Add New Product
                            </a>
                        @endif
                    </div> --}}
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
        </div>
        <div>
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <div class="comparison-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="comparison-items">
                    <strong>Selected for comparison:</strong>
                    <div id="comparisonProducts" class="d-flex gap-3">
                        <!-- Products will be added here dynamically -->
                    </div>
                </div>
                <div class="comparison-actions">
                    <a href="{{ route('products.compare') }}" class="btn btn-primary" id="compareButton" style="display: none;">
                        Compare Products (<span id="compareCount">0</span>)
                    </a>
                </div>
                <div>
                  
                </div>
            </div>
        </div>
    </div>
    <div class="comparison-bar-spacer"></div>
@endsection 