@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <span>Customers List</span>
                   
                </div>

                <div class="card-body">
                    @if ($customers->isEmpty())
                        <p>No customers found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Name</th>
                                        <th class="px-4 py-2">Email</th>
                                        <th class="px-4 py-2">Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $customer)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $customer->name }}</td>
                                            <td class="border px-4 py-2">{{ $customer->email }}</td>
                                            <td class="border px-4 py-2">{{ $customer->created_at->format('M d, Y') }}</td>
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
