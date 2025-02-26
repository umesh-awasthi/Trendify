@extends('layouts.app')

@section('title', 'Property Details')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <a href="{{ route('properties.index') }}" class="btn btn-secondary mb-3">⬅ Back</a>
            <div class="row">
                <div class="col-md-6">
                    <img src="{{ asset('storage/' . $property->image) }}" class="img-fluid rounded" alt="Property Image">
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bold">{{ $property->name }}</h2>
                    <p><strong>Location:</strong> {{ $property->location }}</p>
                    <p><strong>Property Type:</strong> {{ $property->type }}</p>
                    <p><strong>Price:</strong> <span class="text-primary fw-bold">${{ number_format($property->price, 2) }}</span></p>
                    <p><strong>Amenities:</strong> {{ $property->amenities }}</p>
                    {{-- <a href="#" class="btn btn-warning">Edit</a>
                    <a href="#" class="btn btn-danger">Delete</a> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
