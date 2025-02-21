@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Assigned Customers for Agent: {{ $agent->name }}</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                {{-- <th>Phone</th>
                                <th>Address</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    {{-- <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->address }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $customers->links() }}

                    <div class="mt-3">
                        <a href="{{ route('admin.agents.getagent') }}" class="btn btn-secondary">
                            Back to Agents List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
