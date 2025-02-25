@extends('layouts.app')

@section('title', 'Global Configuration')

@section('content')
    <div class="container">
        <h2>Bridge Data</h2>
        <form action="{{ route('admin.bridge_data.save') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="api_url">API URL</label>
                <input type="url" class="form-control" id="api_url" name="api_url" required>
            </div>
            <div class="form-group">
                <label for="selection">Select This </label><br>
                <input type="checkbox" id="bridge_data" name="selection" value="Bridge Data" required>
                <label for="bridge_data">Bridge Data</label><br>
            </div>
            <button type="submit" class="btn btn-primary">Save Configuration</button>
            {{-- <div class="mt-3">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    Back To Dashboard
            </div> --}}
        </form>
        
    </div>
@endsection
