<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Wajbati') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">

        <!-- Scripts & Styles -->
        @if(app()->getLocale() == 'ar')
            @vite(['resources/css/app-rtl.css', 'resources/js/app.js'])
        @else
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-surface-0 text-main antialiased" style="background-color: var(--surface-0); color: var(--text-main); min-height: 100vh;">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5 px-3">
            <div class="mb-4">
                <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-primary border border-primary border-opacity-30"
                         style="width: 48px; height: 48px; background: var(--surface-1);">
                        <i class="fas fa-utensils fa-lg"></i>
                    </div>
                    <span class="h4 fw-bold mb-0 text-light">{{ config('app.name', 'Wajbati') }}</span>
                </a>
            </div>

            <div class="w-100 rounded-4 border border-secondary border-opacity-10 p-4 p-md-5 shadow-lg" style="max-width: 480px; background: var(--surface-1);">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
