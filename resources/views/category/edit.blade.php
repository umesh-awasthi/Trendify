@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Edit Category</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-4">
                    @if($category->image)
                        <div class="current-image mb-3">
                            <label class="form-label">Current Image:</label>
                            <img src="{{ asset('storage/'.$category->image) }}" 
                                 class="img-fluid rounded" 
                                 alt="Current Image">
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <form action="{{ route('category.update', $category->id) }}" 
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
                                   value="{{ $category->name }}" 
                                   required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3">{{ $category->description }}</textarea>
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
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">None (Top Level Category)</option>
                                @foreach($parentCategories as $parentCategory)
                                    @if($parentCategory->id != $category->id && !$parentCategory->ancestors->contains($category->id))
                                        <option value="{{ $parentCategory->id }}" 
                                                {{ $category->parent_id == $parentCategory->id ? 'selected' : '' }}>
                                            {{ $parentCategory->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small class="text-muted">Select a parent category or leave empty for top-level category</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                        <a href="{{ route('category.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 