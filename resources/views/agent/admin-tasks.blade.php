@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <span>Admin Assistance Tasks</span>
                
                </div>

                <div class="card-body">
                    <div class="text-center">
                        <h3 class="text-lg font-semibold mb-4">Admin Assistance</h3>
                        <p class="text-gray-600">This section is for tasks assigned by administrators.</p>
                        <p class="text-gray-600 mt-2">Check back later for assigned tasks.</p>
                    </div>
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
