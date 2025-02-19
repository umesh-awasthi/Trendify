@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
    <div class="card">
        <div class="card-header">
            <h2>Add New Category</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf 
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Category Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <div class="mb-3">
                    <label for="parent_id" class="form-label">Parent Category</label>
                    <select class="form-control" id="parent_id" name="parent_id">
                        <option value="">None (Top Level Category)</option>
                        @foreach(\App\Models\Category::getParentCategories() as $parentCategory)
                            <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Category</button>
                <a href="{{ route('category.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection 