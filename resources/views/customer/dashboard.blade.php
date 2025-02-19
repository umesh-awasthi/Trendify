@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Customer Dashboard</h2>
        </div>
        <div class="card-body">
            <h3>Welcome,{{ Auth::guard('customer')->user()->name }} !</h3>
            {{-- {{ Auth::guard('customer')->user()->name }} --}}
            <div class="mt-4">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 