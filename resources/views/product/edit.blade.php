@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Edit Product</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-4">
                    @if($product->image)
                        <div class="current-image mb-3">
                            <label class="form-label">Current Image:</label>
                            <img src="{{ asset('storage/'.$product->image) }}" 
                                 class="img-fluid rounded" 
                                 alt="Current Image">
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <form action="{{ route('products.update', $product->id) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   value="{{ $product->name }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="short_notes" class="form-label">Short Notes</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="short_notes" 
                                   name="short_notes" 
                                   value="{{ $product->short_notes }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ $product->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" 
                                   step="0.01" 
                                   class="form-control" 
                                   id="price" 
                                   name="price" 
                                   value="{{ $product->price }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Category::all() as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Update Image</label>
                            <input type="file" 
                                   class="form-control" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 