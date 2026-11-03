@extends('layouts.frontend')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="text-center" style="max-width: 580px;">
        <div class="position-relative mb-4">
            <span class="display-1 fw-bold text-danger opacity-20 d-block" style="font-size: clamp(6rem, 15vw, 10rem); letter-spacing: -0.05em; line-height: 1;">500</span>
            <div class="position-absolute top-50 start-50 translate-middle">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-danger border border-danger border-opacity-30 shadow-lg"
                     style="width: 80px; height: 80px; background: var(--surface-1);">
                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                </div>
            </div>
        </div>
        <h1 class="h2 fw-bold text-light mb-3">{{ __('Internal Server Error') }}</h1>
        <p class="text-secondary mb-4 leading-relaxed">{{ __('Something went wrong on our end. Our team has been notified.') }}</p>
        <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-5 py-2 fw-semibold shadow-lg" data-magnetic>
                <i class="fas fa-home me-2"></i>{{ __('Back to Home') }}
            </a>
        </div>
    </div>
</div>
@endsection
