@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800">Welcome, Agent!</h2>
                <p class="mt-2 text-gray-600">You're logged in as an agent. Manage your tasks efficiently.</p>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Product Management Card -->
            <div class="bg-white border-2 border-dashed border-gray-500">
              
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Product Management</h3>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('agent.products.create') }}" >
                            <button class=" text-black ">Add New Product</button>
                        </a>
                        <a href="{{ route('agent.products') }}" >
                           <button class=" text-black "> View Products </button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Orders Management Card -->
            <div class="bg-white border-2 border-dashed border-gray-500">
                
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Orders Management</h3>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('agent.orders') }}" >
                            <button class=" text-black text-center"> View Orders</button>
                            
                        </a>
                    </div>
                </div>
            </div>

            <!-- Customers Management Card -->
            <div class="bg-white border-2 border-dashed border-gray-500">
                
                    <h3 class="text-lg font-medium text-gray-900">Customers Management</h3>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('agent.customers') }}">
                            <button class=" text-black">   View Customers</button>
                          
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sales Management Card -->
            <div class="bg-white border-2 border-dashed border-gray-500"><div class="bg-white overflow-hidden shadow-sm sm:rounded-lg transform transition duration-500 hover:scale-105">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Sales Management</h3>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('agent.reports') }}" >
                            <button class=" text-black text-center "> View Reports</button>
                    
                        </a>
                    </div>
                </div>
            </div>

            <!-- Admin Assistance Tasks Card -->
            <div class="bg-white border-2 border-dashed border-gray-500">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Admin Assistance Tasks</h3>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('agent.admin-tasks') }}" >
                            <button class=" text-black text-center ">   View Tasks</button>
                          
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection