@extends('layouts.frontend')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="position-relative mb-5">
            <h1 class="display-1 fw-900 opacity-25" style="letter-spacing: 20px;">404</h1>
            <div class="position-absolute top-50 start-50 translate-middle w-100">
                <i class="fas fa-search fa-4x text-primary-custom opacity-75"></i>
            </div>
        </div>
        <h2 class="fw-bold text-light mb-3">{{ __('Page Not Found') }}</h2>
        <p class="text-muted mb-5 mx-auto" style="max-width: 500px;">{{ __('The page you are looking for does not exist or has been moved.') }}</p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 py-2 grow-on-hover">
                <i class="fas fa-home me-2"></i>{{ __('Back to Home') }}
            </a>
            <a href="{{ route('meals.index') }}" class="btn btn-outline-primary rounded-pill px-5 py-2">
                {{ __('Browse Meals') }}
            </a>
        </div>
    </div>
</div>

<style>
    .grow-on-hover:hover { transform: scale(1.05); }
    .display-1 { font-size: 8rem; }
</style>
@endsection
