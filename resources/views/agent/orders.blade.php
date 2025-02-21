@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-3xl font-bold mb-8">Your Assigned Customers' Orders</h1>

                @if (session('success'))
                    <div class="bg-green-500 text-white p-4 mb-4 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-300 border-collapse">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order ID
                                </th>
                                <th class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 border border-gray-300 whitespace-nowrap">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 border border-gray-300 whitespace-nowrap">{{ $order->customer->name }}</td>
                                    <td class="px-6 py-4 border border-gray-300 whitespace-nowrap">${{ number_format($order->total, 2) }}</td>
                                    <td class="px-6 py-4 border border-gray-300 whitespace-nowrap">
                                        <span class="px-2 py-1 text-sm font-semibold rounded-full 
                                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 
                                               'bg-green-100 text-green-800') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 border border-gray-300 whitespace-nowrap text-sm font-medium">
                                        <form action="{{ route('agent.orders.update-status', $order->id) }}" method="POST" class="inline">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" class="ml-2 p-1 border rounded">
                                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($orders->isEmpty())
                    <div class="mt-4 p-4 bg-gray-100 text-gray-600 rounded">
                        No orders found for your assigned customers.
                    </div>
                @endif
                <div class="mt-6">
                    <a href="{{ route('agent.dashboard') }}" class="bg-yellow-500 text-black px-4 py-2 rounded-md hover:bg-gray-600">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
