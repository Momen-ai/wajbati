<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Wajbati - The ultimate platform connecting home-based chefs with food lovers. Taste the home, everywhere.">
    <meta property="og:title" content="Wajbati - Homemade Meals Platform">
    <meta property="og:description" content="Order fresh, delicious, and homemade meals from talented local chefs.">
    <meta property="og:image" content="{{ asset('assets/images/og-image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <title>{{ config('app.name', 'Wajbati') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    
    <!-- Link Favicons logic (standard practice) -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/logo.png') }}">

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
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <div class="rounded-circle bg-primary-custom d-flex align-items-center justify-content-center text-light me-2 animate-pulse"
                    style="width:42px;height:42px">
                    <i class="fas fa-utensils"></i>
                </div>
                <span class="brand-text">Wajbati</span>
            </a>
            
            <button class="navbar-toggler border-0 p-2 shadow-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon-custom"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Home') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('meals.*') ? 'active' : '' }}" href="{{ route('meals.index') }}">{{ __('Meals') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('chefs.*') ? 'active' : '' }}" href="{{ route('chefs.index') }}">{{ __('Chefs') }}</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">{{ __('Contact') }}</a></li>
                </ul>

                <ul class="navbar-nav align-items-lg-center">
                    <!-- Notifications for Logged Users -->
                    @auth
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link position-relative px-2 dropdown-toggle no-caret" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-bell fa-lg"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary-custom border border-dark border-2 small" style="font-size: 0.6rem">
                                    {{ auth()->user()->unreadNotifications->count() }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-notif border-0 shadow-lg mt-3 p-0 overflow-hidden" style="width: 320px; border-radius: 15px;">
                            <div class="p-3 bg-dark border-bottom border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                                <h6 class="m-0 fw-700 small uppercase tracking-wider text-light">Notifications</h6>
                                <form action="{{ route('notifications.mark-as-read') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 small text-decoration-none text-primary-custom" style="font-size: 0.75rem">Mark all read</button>
                                </form>
                            </div>
                            <div class="notif-list" style="max-height: 350px; overflow-y: auto;">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ route('orders.show', $notification->data['order_id'] ?? '') }}" class="dropdown-item d-flex p-3 border-bottom border-secondary border-opacity-10 bg-dark hover-opacity-75">
                                        <div class="me-3">
                                            <div class="rounded-circle bg-primary-custom bg-opacity-10 text-primary-custom d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-shopping-bag"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1 small text-light">{{ $notification->data['message'] }}</p>
                                            <span class="text-muted" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center">
                                        <i class="fas fa-bell-slash text-muted mb-2 opacity-25 fa-2x"></i>
                                        <p class="text-muted small m-0">No new notifications</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </li>
                    @endauth

                    <li class="nav-item me-3">
                        <a class="nav-link position-relative px-2" href="{{ route('cart.index') }}">
                            <i class="fa fa-shopping-bag fa-lg"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-count border border-dark border-2">
                                {{ count(session('cart', [])) }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe me-1"></i> {{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark border border-secondary border-opacity-10 shadow">
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
                        </ul>
                    </li>

                    @guest
                        <li class="nav-item d-flex gap-2 ms-lg-2 mt-2 mt-lg-0">
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm px-3 rounded-pill">{{ __('Login') }}</a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm px-3 rounded-pill shadow">{{ __('Register') }}</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="avatar-initial me-2 bg-primary-custom" style="width: 24px; height: 24px; font-size: 0.7rem; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end border border-secondary border-opacity-10 shadow mt-2">
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-box me-2 small opacity-75"></i>{{ __('My Orders') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-gear me-2 small opacity-75"></i>{{ __('Profile') }}</a></li>
                                @if (Auth::user()->role == 'admin')
                                    <li><a class="dropdown-item fw-bold text-warning" href="{{ route('dashboard') }}"><i class="fas fa-terminal me-2 small"></i>Admin Panel</a></li>
                                @endif
                                @if (Auth::user()->role == 'chef')
                                    <li><a class="dropdown-item fw-bold text-primary-custom" href="{{ route('chef.dashboard') }}"><i class="fas fa-kitchen-set me-2 small"></i>{{ __('Chef Dashboard') }}</a></li>
                                @endif
                                <li><hr class="dropdown-divider border-secondary border-opacity-50"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2 small opacity-75"></i>{{ __('Logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Breadcrumbs (Simplified) -->
    @if(!request()->routeIs('home'))
    <div class="breadcrumb-container bg-dark bg-opacity-25 border-bottom border-secondary border-opacity-10 py-2">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i>Home</a></li>
                    @if(request()->routeIs('meals.*'))
                        <li class="breadcrumb-item active text-light" aria-current="page">Meals</li>
                    @elseif(request()->routeIs('chefs.*'))
                        <li class="breadcrumb-item active text-light" aria-current="page">Chefs</li>
                    @elseif(request()->routeIs('contact'))
                        <li class="breadcrumb-item active text-light" aria-current="page">Contact</li>
                    @endif
                </ol>
            </nav>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="container mt-4 animate-fade-in">
            <div class="alert alert-success border-0 shadow-sm rounded-12 d-flex align-items-center" role="alert">
                <i class="fas fa-check-circle me-3 fa-lg"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="container mt-4 animate-fade-in">
            <div class="alert alert-danger border-0 shadow-sm rounded-12 d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <!-- Content -->
    <main class="min-vh-100">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-darker text-light border-top border-secondary border-opacity-10 pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row g-5 mb-5">
                <div class="col-lg-4">
                    <a class="navbar-brand d-flex align-items-center mb-4 text-light" href="#">
                        <i class="fas fa-utensils text-primary-custom me-2 fa-lg"></i>
                        <span class="fw-bold h4 m-0">Wajbati</span>
                    </a>
                    <p class="text-muted small mb-4">Connecting food lovers with home chefs. Experience the taste of home, anytime, anywhere. Support local families and enjoy fresh meals.</p>
                    <div class="social-links d-flex gap-3">
                        <a href="#" class="rounded-circle bg-secondary bg-opacity-10 text-muted p-2 hover-lift border border-secondary border-opacity-10" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="rounded-circle bg-secondary bg-opacity-10 text-muted p-2 hover-lift border border-secondary border-opacity-10" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="rounded-circle bg-secondary bg-opacity-10 text-muted p-2 hover-lift border border-secondary border-opacity-10" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="rounded-circle bg-secondary bg-opacity-10 text-muted p-2 hover-lift border border-secondary border-opacity-10" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="fw-700 mb-4 text-primary-custom small text-uppercase tracking-wider">Explore</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-3"><a href="{{ route('home') }}" class="text-decoration-none text-muted hover-text-primary">Home</a></li>
                        <li class="mb-3"><a href="{{ route('meals.index') }}" class="text-decoration-none text-muted hover-text-primary">Browse Meals</a></li>
                        <li class="mb-3"><a href="{{ route('chefs.index') }}" class="text-decoration-none text-muted hover-text-primary">Meet Chefs</a></li>
                        <li class="mb-3"><a href="{{ route('contact') }}" class="text-decoration-none text-muted hover-text-primary">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="fw-700 mb-4 text-primary-custom small text-uppercase tracking-wider">Account</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-3"><a href="{{ route('orders.index') }}" class="text-decoration-none text-muted hover-text-primary">Track Orders</a></li>
                        <li class="mb-3"><a href="{{ route('profile.edit') }}" class="text-decoration-none text-muted hover-text-primary">Settings</a></li>
                        <li class="mb-3"><a href="#" class="text-decoration-none text-muted hover-text-primary">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-700 mb-4 text-primary-custom small text-uppercase tracking-wider">Stay Gourmet</h6>
                    <p class="text-muted small mb-4">Join our newsletter to get weekly recipes and special offers.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="input-group mb-3">
                        @csrf
                        <input type="email" name="email" class="form-control bg-secondary bg-opacity-10 border-secondary border-opacity-10 text-light py-2 px-3" placeholder="Email address" required>
                        <button class="btn btn-primary px-3" type="submit"><i class="fas fa-paper-plane"></i></button>
                    </form>
                    <p class="text-muted" style="font-size: 0.65rem;">By subscribing, you agree to our Terms and Privacy Policy.</p>
                </div>
            </div>
            <div class="border-top border-secondary border-opacity-10 pt-4 text-center small text-muted">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="m-0">&copy; {{ date('Y') }} Wajbati Inc. Built with love for home food.</p>
                    <div class="payment-icons opacity-50 d-flex gap-3 h5 m-0">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-paypal"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
</body>

</html>
