@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header flex justify-between items-center">
                    <span>Sales Reports</span>
                   
                </div>

                <div class="card-body">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-4">Sales Overview</h3>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <canvas id="salesChart" width="400" height="200"></canvas>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">Detailed Sales Data</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">Date</th>
                                        <th class="px-4 py-2">Total Sales</th>
                                        {{-- <th class="px-4 py-2">Number of Orders</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salesData as $data)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $data->date }}</td>
                                            <td class="border px-4 py-2">${{ number_format($data->total, 2) }}</td>
                                            {{-- <td class="border px-4 py-2">{{ $data->order_count }}</td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('agent.dashboard') }}" class="bg-yellow-500 text-black px-4 py-2 rounded-md hover:bg-gray-600">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div id="chartLabels" style="display: none;">{!! json_encode($salesData->pluck('date')) !!}</div>
<div id="chartData" style="display: none;">{!! json_encode($salesData->pluck('total')) !!}</div>
@push('scripts')
<script src="{{ asset('js/agent/reports.js') }}"></script>
@endpush
@endsection
