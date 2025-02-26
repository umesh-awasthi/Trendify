@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    /* Hide the dot before 'Global Configuration' */
    .nav-item::marker {
        content: "";
        /* display: none; */
    }
</style>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2>Admin Dashboard</h2>
                    </div>
                 
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="globalConfigDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                   Global Configuration
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="globalConfigDropdown">
                                <li><a class="dropdown-item" href="{{ route('admin.bridge_data') }}">Bridge Data</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.great_schools') }}">Great Schools</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.walkscore') }}">Walkscore</a></li>
                            </ul>
                        </li>
                
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Products</h5>
                                        <p class="card-text">Manage your products</p>
                                        <a href="{{ route('products.index') }}" class="btn btn-primary">View Products</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Property Categories</h5>
                                        <p class="card-text">Manage your categories</p>
                                        <a href="{{ route('category.index') }}" class="btn btn-primary">View Categories</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Customers</h5>
                                        <p class="card-text">Manage customer accounts</p>
                                        <a href="{{ route('admin.customers.index') }}" class="btn btn-primary">View
                                            Customers</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Agents</h5>
                                        <p class="card-text">Manage agent accounts</p>
                                        <a href="{{ route('admin.agents.create') }}" class="btn btn-primary mb-2">Create
                                            Agent</a>
                                        <a href="{{ route('admin.agents.getagent') }}"
                                            class="btn btn-primary mb-2">View/Edit Agents</a>
                                    </div>
                                </div>
                            </div>

                        </div>
        
        <!-- New Manage Property Card -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Manage Properties</h5>
                    <p class="card-text">Add, edit, or delete properties.</p>
                    <a href="{{ route('properties.index') }}" class="btn btn-primary">Manage Properties</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
