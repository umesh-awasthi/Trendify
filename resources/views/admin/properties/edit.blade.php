@extends('layouts.app')

@section('title', 'Edit Property')

@section('content')
<div class="container">
    <h1>Edit Property</h1>
   
    <form action="{{ route('properties.update', $property) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Property Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $property->name }}" required>
        </div>
        <div class="form-group">
            <label for="type">Property Type</label>
            <input type="text" class="form-control" id="type" name="type" value="{{ $property->type }}" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ $property->price }}" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="image">Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            @if($property->image)
                <img src="{{ asset('storage/' . $property->image) }}" alt="{{ $property->name }}" width="100">
            @endif
        </div>
        <div class="form-group">
            <label for="amenities">Amenities</label>
            <textarea class="form-control" id="amenities" name="amenities">{{ $property->amenities }}</textarea>
        </div>
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" value="{{ $property->location }}" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Update Property</button>
    </form>
</div>
@endsection
