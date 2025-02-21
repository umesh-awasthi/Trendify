@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold text-gray-800">Create New Agent</h2>
                    @if (session('success'))
                        <div class="bg-green-500 text-white text-center p-3 mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.agents.store') }}" method="POST">
                        @csrf

                        <!-- Name Field -->
                        <div class="mt-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Email Field -->
                        <div class="mt-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Password Field -->
                        <div class="mt-4">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <!-- Role Field (Hidden, default to agent) -->
                        <input type="hidden" name="role" value="agent">

                        <!-- Permissions Section -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900">Permissions</h3>

                            <div class="mt-4 space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="manage_products" id="manage_products"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="manage_products" class="ml-3 block text-sm font-medium text-gray-700">
                                        Product Management
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="manage_orders" id="manage_orders"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="manage_orders" class="ml-3 block text-sm font-medium text-gray-700">
                                        Order Management
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="manage_customers"
                                        id="manage_customers"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="manage_customers" class="ml-3 block text-sm font-medium text-gray-700">
                                        Customer Management
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="view_reports" id="view_reports"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="view_reports" class="ml-3 block text-sm font-medium text-gray-700">
                                        Sales Management
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="permissions[]" value="admin_tasks" id="admin_tasks"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="admin_tasks" class="ml-3 block text-sm font-medium text-gray-700">
                                        Admin Assistance Tasks
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-6">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-black">
                                Create Agent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
