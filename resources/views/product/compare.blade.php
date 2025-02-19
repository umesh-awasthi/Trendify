@extends('layouts.app')

@section('title', 'Compare Products')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Product Comparison</h2>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>
    <div class="card-body">
        @if($products->isEmpty())
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h3>No products to compare</h3>
                <p>Add some products to compare them</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Browse Products</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered comparison-table">
                    <thead>
                        <tr>
                            <th>Feature</th>
                            @foreach($products as $product)
                                <th class="text-center">
                                    <button class="btn btn-sm btn-danger remove-compare" 
                                            data-product-id="{{ $product->id }}">
                                        <i class="bi bi-x"></i> Remove
                                    </button>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @include('product.partials.comparison-rows', ['products' => $products])
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.comparison-table th {
    background-color: #f8f9fa;
}

.comparison-table td {
    min-width: 200px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle remove from comparison
    document.querySelectorAll('.remove-compare').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            fetch(`/compare/remove/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            });
        });
    });
});
</script>
@endpush 