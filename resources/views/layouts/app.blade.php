<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Trendify- @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/comparison.css') }}" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            {{-- <div>
            <a class="navbar-brand" href="{{ route('products.index') }}">Trendify</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
          </div> --}}
          <div>
            <a class="navbar-brand flex items-center" href="{{ route('products.index') }}">
                <img src="{{ asset('storage/logo/logo2.png') }}" alt="Logo" width="60" height="60">
                Trendify
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('category.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.compare') }}" id="navCompareLink">
                            Compare <span class="bg-warning text-dark" id="navCompareCount">0</span>
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @if (!Auth::guard('admin')->check())
                    <li class="nav-item">
                        {{-- <a class="nav-link flex items-center position-relative" href="{{ route('cart.show') }}"> --}}
                            <a class="nav-link d-flex align-items-center position-relative" href="{{ route('cart.show') }}">
 
                            <!-- Cart Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="28" height="28" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                              </svg>
                              
                
                            <!-- Cart Count -->
                            @php
                                $cartCount = 0;
                                
                                if (Auth::guard('customer')->check()) {
                                    $customer = Auth::guard('customer')->user();
                                    $cartCount = \App\Models\Cart::where('customer_id', $customer->id)->sum('quantity');
                                } else {
                                    $cartCount = session('cart') ? collect(session('cart'))->sum('quantity') : 0;
                                }
                            @endphp
                
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">
                                {{ $cartCount }}
                            </span>
                        </a>
                    </li>
                @endif
                
                    @if (Auth::guard('admin')->check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @elseif(Auth::guard('customer')->check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customer.dashboard') }}">My Account</a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                        @elseif(Auth::guard('web')->check())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('agent.dashboard') }}">Agent Dashboard</a>
                        </li>
                       
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
    
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/comparison.js') }}"></script>
    @stack('scripts')

    <script>
        function updateCartTotal() {
            $.ajax({
                url: "{{ route('cart.total') }}",
                method: "GET",
                success: function (data) {
                    $('#grandTotal').text('$' + data.grandTotal);
                    $('#discountAmount').text('-$' + data.discount);
                    $('#totalAfterDiscount').text('$' + data.totalAfterDiscount);
                }
            });
        }

        $(document).ready(function () {
            updateCartTotal(); // Call it on page load

            // Refresh the total when an item is added, removed, or quantity is updated
            $(document).on('click', '.btn-danger, .btn-primary', function () {
                setTimeout(updateCartTotal, 500);
            });
        });
    </script>
</body>

