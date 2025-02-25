@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Agent Login</h2>
    <form method="POST" action="{{ route('agent.login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
        <div class="mt-2">
            <a href="{{ route('agent.password.request') }}">Forgot Password?</a>
        </div>
    </form>
</div>
@endsection
