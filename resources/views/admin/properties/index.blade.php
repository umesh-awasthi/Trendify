@extends('layouts.app')

@section('title', 'Manage Properties')

@section('content')
    <div class="container">
        <h1>Manage Properties</h1>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <a href="{{ route('properties.create') }}" class="btn btn-primary">Add New Property</a>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($properties as $property)
                    <tr>
                        <td>{{ $property->name }}</td>
                        <td>{{ $property->type }}</td>
                        <td>${{ number_format($property->price, 2) }}</td>
                        <td>
                            @if ($property->image)
                                <img src="{{ asset('storage/' . $property->image) }}" alt="{{ $property->name }}"
                                    width="100">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('properties.show', $property) }}" class="btn btn-info">View</a>
                            <a href="{{ route('properties.edit', $property) }}" class="btn btn-warning">Edit</a>

                            <form action="{{ route('properties.destroy', $property) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mb-3">⬅ Back</a>
    </div>
@endsection
