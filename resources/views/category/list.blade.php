@extends('layouts.app')

@section('title', 'Main Categories')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Main Categories</h1>
        @if(Auth::guard('admin')->check())
            <a href="{{ route('category.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add New Category
            </a>
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table category-table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px">Image</th>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th style="width: 120px" class="text-center">Subcategories</th>
                            <th style="width: 200px" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset('storage/'.$category->image) }}" 
                                             class="rounded category-image" 
                                             alt="{{ $category->name }}">
                                    @else
                                        <div class="placeholder-icon">
                                            <i class="bi bi-collection"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                </td>
                                <td>
                                    {{ Str::limit($category->description, 100) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        {{ $category->children->count() }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group float-end">
                                        <a href="{{ route('category.show', $category->id) }}" 
                                           class="btn btn-primary btn-sm mx-1">
                                            View
                                        </a>
                                        @if(Auth::guard('admin')->check())
                                            <a href="{{ route('category.edit', $category->id) }}" 
                                               class="btn btn-warning btn-sm mx-1">
                                                Edit
                                            </a>
                                            <form action="{{ route('category.destroy', $category->id) }}" 
                                                  method="POST" 
                                                  class="btn-sm mx-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm mx-1" 
                                                        onclick="return confirm('Are you sure?')">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="category-empty-state">
                                        <i class="bi bi-inbox"></i>
                                        <p class="mb-2">No categories found</p>
                                        @if(Auth::guard('admin')->check() || Auth::guard('customer')->check())
                                            <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">
                                                Create First Category
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="ml-2 px-4 py-2 mt-2 btn btn-secondary">
        Back to dashboard?
    </a>
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} categories
        </div>
        <div>
            {{ $categories->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection 