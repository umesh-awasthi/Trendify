@extends('layouts.app')

@section('title', 'Edit Agent Permissions')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h2>Edit Agent Permissions</h2>
                    </div>
                    @if (session('success'))
                        <div class="bg-green-500 text-white text-center p-3 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card-body">
                        <form action="{{ route('admin.agents.update', $agent->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Agent Name</label>
                                <input type="text" class="form-control" value="{{ $agent->name }}" readonly>
                            </div>

                            <div class="form-group">
                                <label>Permissions</label>
                                <div class="permissions-list">
                                    @foreach ($permissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                value="{{ $permission }}"
                                                @if (in_array($permission, json_decode($agent->permissions))) checked @endif>
                                            <label class="form-check-label">
                                                {{ ucfirst(str_replace('_', ' ', $permission)) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">Update Permissions</button>
                                <a href="{{ route('admin.agents.getagent') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
