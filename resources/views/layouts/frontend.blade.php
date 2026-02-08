<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Wajbati') }}</title>

    <!-- Styles -->
    @if(app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
        <style>body { font-family: 'Cairo', sans-serif; }</style>
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <div class="rounded-circle bg-primary-custom d-flex align-items-center justify-content-center text-white me-2"
                    style="width:40px;height:40px">
                    <i class="fas fa-utensils"></i>
                </div>
                <span class="brand-text">Wajbati</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('meals.*') ? 'active' : '' }}"
                            href="{{ route('meals.index') }}">{{ __('Meals') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('chefs.*') ? 'active' : '' }}"
                            href="{{ route('chefs.index') }}">{{ __('Chefs') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}"
                            href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
                </ul>

                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative px-2" href="{{ route('cart.index') }}">
                            <i class="fa fa-shopping-bag fa-lg"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count border border-white">
                                {{ count(session('cart', [])) }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe me-1"></i> {{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm">
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
                        </ul>
                    </li>

                    @guest
                        <li class="nav-item d-flex gap-2">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3">{{ __('Login') }}</a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 shadow-sm">{{ __('Register') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm">
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">{{ __('My Orders') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                                @if (Auth::user()->role == 'admin')
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                @endif
                                @if (Auth::user()->role == 'chef')
                                    <li><a class="dropdown-item fw-bold text-primary" href="{{ route('chef.dashboard') }}"><i class="fas fa-chart-line me-2"></i>{{ __('Chef Dashboard') }}</a></li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">{{ __('Logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest

                    {{-- <li class="nav-item ms-lg-3 mt-2 mt-lg-0">
                        <button class="btn btn-light btn-sm border fw-bold text-muted" id="langToggle">AR</button>
                    </li> --}}
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-top pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <a class="navbar-brand d-flex align-items-center mb-3 text-dark" href="#">
                        <i class="fas fa-utensils text-primary-custom me-2 fa-lg"></i>
                        <span class="fw-bold h4 m-0">Wajbati</span>
                    </a>
                    <p class="text-muted small">Connecting food lovers with home chefs. Experience the taste of home
                        anywhere.</p>
                </div>
                <div class="col-md-2 col-6">
                    <h6 class="fw-bold mb-3">Links</h6>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><a href="{{ route('home') }}"
                                class="text-decoration-none text-muted">Home</a></li>
                        <li class="mb-2"><a href="{{ route('meals.index') }}"
                                class="text-decoration-none text-muted">Meals</a></li>
                        <li class="mb-2"><a href="{{ route('chefs.index') }}"
                                class="text-decoration-none text-muted">Chefs</a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-6">
                    <h6 class="fw-bold mb-3">Legal</h6>
                    <ul class="list-unstyled small text-muted">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Privacy
                                Policy</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Terms of
                                Service</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Stay Updated</h6>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Email address">
                        <button class="btn btn-primary" type="button"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
            <div class="border-top pt-3 text-center small text-muted">
                &copy; {{ date('Y') }} Wajbati. All rights reserved. | Contact: contact@wajbati.com
            </div>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>

</html>
