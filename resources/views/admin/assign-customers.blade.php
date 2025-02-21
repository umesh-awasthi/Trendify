@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-100">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Assign Customers to Agent: {{ $agent->name }}</h2>

                <form action="{{ route('admin.agents.assign-customers.store', $agent) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        @foreach($customers as $customer)
                            <div class="flex items-center">
                                <input type="checkbox" name="customers[]" value="{{ $customer->id }}" 
                                    id="customer_{{ $customer->id }}" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    @if($agent->customers->contains($customer->id)) checked @endif>
                                <label for="customer_{{ $customer->id }}" class="ml-2 text-gray-700">
                                    {{ $customer->name }} ({{ $customer->email }})
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-black rounded hover:bg-indigo-700">
                            Assign Selected Customers
                        </button>
                        <a href="{{ route('admin.agents.getagent') }}" class="ml-2 px-4 py-2 bg-gray-500 text-black rounded hover:bg-gray-600">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
