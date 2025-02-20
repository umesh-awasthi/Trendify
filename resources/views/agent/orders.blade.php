@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    Orders Management
                </div>

                <div class="card-body">
                    @if ($orders->isEmpty())
                        <p>No orders found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Order #</th>
                                        <th class="px-4 py-2">Customer</th>
                                        <th class="px-4 py-2">Total</th>
                                        <th class="px-4 py-2">Status</th>
                                        <th class="px-4 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="border px-4 py-2">#{{ $order->id }}</td>
                                            <td class="border px-4 py-2">{{ $order->customer ? $order->customer->name : 'Guest' }}</td>
                                            <td class="border px-4 py-2">${{ number_format($order->total, 2) }}</td>
                                            <td class="border px-4 py-2">
                                                <span class="px-2 py-1 text-sm rounded-full 
                                                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($order->status === 'shipped' ? 'bg-blue-100 text-blue-800' : 
                                                       'bg-green-100 text-green-800') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <form action="{{ route('agent.orders.update-status', $order) }}" method="POST" class="inline">
                                                    @csrf
                                                    <select name="status" onchange="this.form.submit()" class="form-select">
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
                    @endif
                </div>
                <div class="mt-6 text-center">
                    <a href="{{ route('agent.dashboard') }}" class="bg-gray-500 text-black px-4 py-2 rounded-md hover:bg-gray-600">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
 
</div>
@endsection
