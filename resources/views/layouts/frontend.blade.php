<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <meta name="description" content="Wajbati - The premier marketplace connecting independent chefs, kitchens, and culinary artisans with food lovers. Exceptional flavors, everywhere.">
    <meta property="og:title" content="Wajbati - Independent Chefs & Kitchens Platform">
    <meta property="og:description" content="Order fresh, delicious, and handcrafted meals from talented independent chefs and kitchens.">
    <meta property="og:image" content="{{ asset('assets/images/og-image.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">

    <title>{{ config('app.name', 'Wajbati') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    
    <!-- Link Favicons logic (standard practice) -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/logo.png') }}">

    <!-- Styles -->
    @if(app()->getLocale() == 'ar')
        @vite(['resources/css/app-rtl.css', 'resources/js/app.js'])
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @stack('styles')
</head>

<body>
    <!-- Solid color base layer below canvas (stacking floor) -->
    <div id="hero-bg-layer" class="hero-bg-layer"></div>

    <!-- Background Canvas & Scrim for Pages with Sequences -->
    @stack('background')

    <!-- Page Content Wrapper (position: relative; z-index: 1 sits above fixed canvas & scrim) -->
    <div class="page-content-wrapper position-relative has-bottom-bar" style="z-index: 1;">
        <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm py-2">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-dark me-2 shadow-sm"
                    style="width:34px;height:34px; color: #1b0e03 !important;">
                    <i class="fas fa-utensils" style="font-size: 15px;"></i>
                </div>
                <span class="brand-text fw-bold text-light" style="font-size: 1.25rem; letter-spacing: -0.02em;">{{ __('Wajbati') }}</span>
            </a>

            <!-- Mobile Quick Actions (<992px) as defined in spec -->
            <div class="d-flex align-items-center gap-2 d-lg-none">
                @auth
                <a href="{{ route('profile.edit') }}" class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle border border-subtle text-secondary"
                   style="width: 40px; height: 40px; background: #151515;" aria-label="{{ __('Notifications') }}">
                    <i class="fa-regular fa-bell" style="font-size: 16px;"></i>
                </a>
                @endauth
                <a href="{{ route('cart.index') }}" class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle border border-subtle text-secondary position-relative"
                   style="width: 40px; height: 40px; background: #151515;" aria-label="{{ __('Cart') }}">
                    <i class="fas fa-shopping-bag" style="font-size: 16px;"></i>
                    @if(count(session('cart', [])) > 0)
                        <span class="position-absolute top-0 end-0 translate-middle badge rounded-pill bg-primary border border-dark"
                              style="font-size: 10px; font-weight: 800; color: #1b0e03 !important; min-width: 18px; height: 18px; padding: 2px 4px;">
                            {{ count(session('cart', [])) }}
                        </span>
                    @endif
                </a>
                <button class="navbar-toggler border-0 p-2 shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon-custom"></span>
                </button>
            </div>

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
                        <div class="dropdown-menu dropdown-menu-end dropdown-notif shadow-lg mt-3 p-0 overflow-hidden" style="width: 320px; border-radius: 16px; background: var(--surface-1); border: 1px solid var(--border-subtle);">
                            <div class="p-3 border-bottom border-secondary border-opacity-10 d-flex justify-content-between align-items-center" style="background: var(--surface-2);">
                                <h6 class="m-0 fw-bold small text-uppercase text-light">{{ __('Notifications') }}</h6>
                                <form action="{{ route('notifications.mark-as-read') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 small text-decoration-none text-primary fw-semibold" style="font-size: 0.75rem">{{ __('Mark all read') }}</button>
                                </form>
                            </div>
                            <div class="notif-list" style="max-height: 350px; overflow-y: auto;">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ route('orders.show', $notification->data['order_id'] ?? '') }}" class="dropdown-item d-flex p-3 border-bottom border-secondary border-opacity-10 hover-opacity-75" style="background: var(--surface-1);">
                                        <div class="me-3">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 40px; height: 40px; background: var(--surface-2);">
                                                <i class="fas fa-shopping-bag"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-1 small text-light">{{ $notification->data['message'] }}</p>
                                            <span class="text-secondary" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-4 text-center">
                                        <i class="fas fa-bell-slash text-secondary mb-2 opacity-40 fa-2x"></i>
                                        <p class="text-secondary small m-0">{{ __('No new notifications') }}</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </li>
                    @endauth

                    <li class="nav-item me-3">
                        <a class="nav-link position-relative px-2" href="{{ route('cart.index') }}">
                            <i class="fa fa-shopping-bag fa-lg"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill cart-count border border-dark border-2" style="background: var(--primary) !important;">
                                {{ count(session('cart', [])) }}
                            </span>
                        </a>
                    </li>

                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-globe me-1"></i> {{ app()->getLocale() == 'ar' ? __('Arabic') : __('English') }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark border border-secondary border-opacity-10 shadow">
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">{{ __('English') }}</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">{{ __('Arabic') }}</a></li>
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
                                <li><a class="dropdown-item" href="{{ route('favourites.index') }}"><i class="fas fa-heart me-2 small opacity-75 text-danger"></i>{{ __('My Favourites') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-gear me-2 small opacity-75"></i>{{ __('Profile') }}</a></li>
                                @if (Auth::user()->role == 'admin')
                                    <li><a class="dropdown-item fw-bold text-warning" href="{{ route('dashboard') }}"><i class="fas fa-terminal me-2 small"></i>{{ __('Admin Panel') }}</a></li>
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
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted"><i class="fas fa-home me-1"></i>{{ __('Home') }}</a></li>
                    @if(request()->routeIs('meals.*'))
                        <li class="breadcrumb-item active text-light" aria-current="page">{{ __('Meals') }}</li>
                    @elseif(request()->routeIs('chefs.*'))
                        <li class="breadcrumb-item active text-light" aria-current="page">{{ __('Chefs') }}</li>
                    @elseif(request()->routeIs('contact'))
                        <li class="breadcrumb-item active text-light" aria-current="page">{{ __('Contact') }}</li>
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
    <footer class="text-light border-top border-secondary border-opacity-10 pt-5 pb-4 mt-5" style="background: var(--surface-1);">
        <div class="container">
            <div class="row g-4 g-lg-5 mb-5">
                <div class="col-lg-4">
                    <a class="navbar-brand d-flex align-items-center mb-3 text-light" href="{{ route('home') }}">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary border border-primary border-opacity-25 me-2"
                             style="width: 38px; height: 38px; background: var(--surface-2);">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <span class="fw-bold h4 m-0 text-light">{{ __('Wajbati') }}</span>
                    </a>
                    <p class="text-secondary small mb-4 lh-base">{{ __('Connecting food lovers with passionate chefs and independent kitchens. Experience authentic flavors, anytime, anywhere. Discover distinctive culinary creations.') }}</p>
                    <div class="social-links d-flex gap-2">
                        <a href="#" class="rounded-circle text-secondary p-2 border border-secondary border-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: var(--surface-2);"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="rounded-circle text-secondary p-2 border border-secondary border-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: var(--surface-2);"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="rounded-circle text-secondary p-2 border border-secondary border-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: var(--surface-2);"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="rounded-circle text-secondary p-2 border border-secondary border-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: var(--surface-2);"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="fw-bold mb-3 text-primary small text-uppercase" style="letter-spacing: 0.05em;">{{ __('Explore') }}</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><a href="{{ route('home') }}" class="text-secondary hover-text-primary">{{ __('Home') }}</a></li>
                        <li class="mb-2"><a href="{{ route('meals.index') }}" class="text-secondary hover-text-primary">{{ __('Browse Meals') }}</a></li>
                        <li class="mb-2"><a href="{{ route('chefs.index') }}" class="text-secondary hover-text-primary">{{ __('Meet Chefs') }}</a></li>
                        <li class="mb-2"><a href="{{ route('contact') }}" class="text-secondary hover-text-primary">{{ __('Contact Us') }}</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md-3 col-lg-2">
                    <h6 class="fw-bold mb-3 text-primary small text-uppercase" style="letter-spacing: 0.05em;">{{ __('Account') }}</h6>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><a href="{{ route('orders.index') }}" class="text-secondary hover-text-primary">{{ __('Track Orders') }}</a></li>
                        <li class="mb-2"><a href="{{ route('profile.edit') }}" class="text-secondary hover-text-primary">{{ __('Settings') }}</a></li>
                        <li class="mb-2"><a href="{{ route('privacy') }}" class="text-secondary hover-text-primary">{{ __('Privacy Policy') }}</a></li>
                        <li class="mb-2"><a href="{{ route('terms') }}" class="text-secondary hover-text-primary">{{ __('Terms of Service') }}</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold mb-3 text-primary small text-uppercase" style="letter-spacing: 0.05em;">{{ __('Stay Gourmet') }}</h6>
                    <p class="text-secondary small mb-3">{{ __('Join our newsletter to get weekly recipes and special offers.') }}</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="input-group mb-2">
                        @csrf
                        <input type="email" name="email" class="form-control text-light border-secondary border-opacity-25" style="background: var(--surface-2);" placeholder="{{ __('Email Address') }}" required>
                        <button class="btn btn-primary px-4 fw-bold" type="submit" data-magnetic><i class="fas fa-paper-plane"></i></button>
                    </form>
                    <p class="text-secondary small" style="font-size: 0.72rem;">{{ __('By subscribing, you agree to our Terms and Privacy Policy.') }}</p>
                </div>
            </div>
            <div class="border-top border-secondary border-opacity-10 pt-4 text-center small text-secondary">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="m-0">&copy; {{ date('Y') }} {{ __('Wajbati Inc. Built with passion for exceptional culinary craft.') }}</p>
                    <div class="d-flex gap-3 h5 m-0 opacity-40">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <i class="fab fa-cc-paypal"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Mobile Bottom Tab Bar (<768px) -->
    <x-ui.bottom-tab-bar />
    </div>
    <!-- Scripts -->
    @stack('scripts')
</body>

</html>
