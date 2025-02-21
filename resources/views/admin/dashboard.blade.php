@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>Admin Dashboard</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Products</h5>
                                    <p class="card-text">Manage your products</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-primary">View Products</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Categories</h5>
                                    <p class="card-text">Manage your categories</p>
                                    <a href="{{ route('category.index') }}" class="btn btn-primary">View Categories</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Agents</h5>
                                    <p class="card-text">Manage agent accounts</p>
                                    <a href="{{ route('admin.agents.create') }}" class="btn btn-primary mb-2">Create Agent</a>
                                    <a href="{{ route('admin.agents.getagent') }}" class="btn btn-primary mb-2">View/Edit Agents</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
