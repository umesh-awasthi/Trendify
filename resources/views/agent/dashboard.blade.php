@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-3xl font-bold">Agent Dashboard</h1>
                    <p class="text-gray-600">Account created: {{ auth()->user()->created_at->format('M d, Y') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @if(in_array('manage_orders', json_decode(auth()->user()->permissions)))
                    <h2 class="text-xl font-semibold mb-2">Orders</h2>
                    <a href="{{ route('agent.orders') }}" class="p-6 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <p class="text-gray-600">Manage customer orders</p>
                    </a>
                    @endif

                    @if(in_array('manage_products', json_decode(auth()->user()->permissions)))
                    <h2 class="text-xl font-semibold mb-2">Products</h2>

                    <a href="{{ route('agent.products') }}" class="p-6 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <p class="text-gray-600">Manage product inventory</p>
                    </a>
                    @endif

                    @if(in_array('manage_customers', json_decode(auth()->user()->permissions)))
                    <h2 class="text-xl font-semibold mb-2">Your Customers</h2>

                    <a href="{{ route('agent.my-customers') }}" class="p-6 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <p class="text-gray-600">View your assigned customers</p>
                    </a>
                    @endif

                    @if(in_array('view_reports', json_decode(auth()->user()->permissions)))
                    <h2 class="text-xl font-semibold mb-2">Reports</h2>

                    <a href="{{ route('agent.reports') }}" class="p-6 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <p class="text-gray-600">View sales and performance reports</p>
                    </a>
                    @endif

                    @if(in_array('admin_tasks', json_decode(auth()->user()->permissions)))
                    <h2 class="text-xl font-semibold mb-2">Admin Tasks</h2>

                    <a href="{{ route('agent.admin-tasks') }}" class="p-6 bg-white rounded-lg shadow hover:shadow-md transition-shadow">
                        <p class="text-gray-600">Manage administrative tasks</p>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
