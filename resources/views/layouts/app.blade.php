<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Wajbati') }} - {{ __('Dashboard') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">

    <!-- Scripts & Styles -->
    @if(app()->getLocale() == 'ar')
        @vite(['resources/css/app-rtl.css', 'resources/js/app.js'])
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('styles')
</head>
<body class="bg-surface-0 text-main min-vh-100" x-data="{ sidebarOpen: false }">
    <div class="d-flex min-vh-100">
        <!-- Sidebar Navigation -->
        @include('layouts.navigation')

        <!-- Main Content Area -->
        <div class="flex-grow-1 d-flex flex-column min-vh-100" style="min-width: 0;">
            <!-- Top Bar -->
            <header class="navbar navbar-expand bg-surface-1 border-bottom border-subtle px-3 px-lg-4 py-2 sticky-top">
                <div class="d-flex align-items-center gap-3 w-100 justify-content-between">
                    <!-- Left: Mobile Toggle & Storefront Link -->
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" @click="sidebarOpen = !sidebarOpen" class="btn btn-sm btn-outline-primary d-lg-none" aria-label="Toggle navigation">
                            <i class="fas fa-bars"></i>
                        </button>

                        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-primary d-none d-sm-inline-flex align-items-center gap-2 rounded-pill px-3">
                            <i class="fas fa-store"></i>
                            <span>{{ __('View Site') }}</span>
                        </a>
                    </div>

                    <!-- Right: Locale & User Dropdown -->
                    <div class="d-flex align-items-center gap-3">
                        <!-- Language Switcher -->
                        <a href="{{ route('lang.switch', app()->getLocale() == 'ar' ? 'en' : 'ar') }}"
                           class="btn btn-sm bg-surface-2 text-secondary border border-subtle rounded-pill px-3 fw-semibold">
                            {{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}
                        </a>

                        <!-- User Profile Menu -->
                        <div class="dropdown">
                            <button class="btn btn-sm bg-surface-2 border border-subtle text-main rounded-pill px-3 py-1 d-flex align-items-center gap-2 dropdown-toggle"
                                    type="button" id="adminUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                     style="width: 28px; height: 28px; font-size: 12px;">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <span class="d-none d-md-inline small fw-semibold">{{ Auth::user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="adminUserDropdown">
                                <li class="px-3 py-2 border-bottom border-subtle">
                                    <div class="fw-semibold text-main">{{ Auth::user()->name }}</div>
                                    <div class="small text-secondary">{{ Auth::user()->email }}</div>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-cog small text-primary"></i>
                                        <span>{{ __('Profile') }}</span>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider border-subtle my-1"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-danger">
                                            <i class="fas fa-sign-out-alt small"></i>
                                            <span>{{ __('Log Out') }}</span>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Heading (if supplied) -->
            @isset($header)
                <div class="bg-surface-1 border-bottom border-subtle px-4 py-3">
                    <div class="container-fluid">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <!-- Page Content -->
            <main class="flex-grow-1 p-3 p-md-4 p-lg-5">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
