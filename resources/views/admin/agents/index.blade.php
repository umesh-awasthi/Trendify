@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800">Manage Agents</h2>
                    @if (session('success'))
                        <div class="bg-green-500 text-white text-center p-3 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('admin.agents.create') }}" class="text-black">
                           
                            <button class="mb-2"> Create New Agent </button>
                        </a>
                    </div>

                    <div class="mt-8">
                        <div class="overflow-x-auto">
                            <table class="min-w-full border border-gray-300 border-collapse">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Permissions
                                        </th>
                                        <th class="px-6 py-3 border border-gray-300 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    @foreach ($agents as $agent)
                                        <tr>
                                            <td class="px-6 py-4 border border-gray-300 whitespace-nowrap">{{ $agent->name }}</td>
                                            <td class="px-6 py-4 border border-gray-300 whitespace-nowrap">{{ $agent->email }}</td>
                                            <td class="px-6 py-4 border border-gray-300 whitespace-nowrap">
                                                @if ($agent->permissions)
                                                    @foreach (json_decode($agent->permissions) as $permission)
                                                        <span class="px-2 py-1 text-xs font-semibold text-indigo-800 bg-indigo-100 rounded-full">
                                                            {{ $permission }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-500">No permissions assigned</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 border border-gray-300 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.agents.edit', $agent->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-4"><button class="mb-2">Edit</button></a>
                                                <a href="{{ route('admin.agents.assign-customers', $agent->id) }}" class="text-green-600 hover:text-green-900 mr-4"> <button class="mb-2">Assign Customers</button></a>
                                                <a href="{{ route('admin.agents.assigned-customers', $agent->id) }}" class="text-blue-600 hover:text-blue-900 mr-4"><button class="mb-2">View Customers</button></a>
                                                <form action="{{ route('admin.agents.destroy', $agent->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to delete this agent?')">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="ml-2 px-4 py-2 bg-gray-500 text-black rounded hover:bg-gray-600">
              <button class="mt-2">  Back to dashboard?</button>
            </a>
        </div>
    </div>
@endsection
