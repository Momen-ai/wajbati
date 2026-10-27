@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-4 p-md-5">
                <h2 class="fw-bold text-light mb-4">{{ __('Privacy Policy') }}</h2>

                <h5 class="fw-bold text-primary mb-2">1. {{ __('Information We Collect') }}</h5>
                <p class="text-muted mb-4 small leading-relaxed">
                    {{ __('We collect your name, email address, delivery addresses, and phone numbers when you register or place an order. For home chefs, kitchen details and menus are also collected to facilitate local commerce.') }}
                </p>

                <h5 class="fw-bold text-primary mb-2">2. {{ __('How We Use Your Information') }}</h5>
                <p class="text-muted mb-4 small leading-relaxed">
                    {{ __('Your information is used strictly to process orders, coordinate local delivery between kitchens and customers, and send status notifications about your meals.') }}
                </p>

                <h5 class="fw-bold text-primary mb-2">3. {{ __('Data Security & Retention') }}</h5>
                <p class="text-muted mb-4 small leading-relaxed">
                    {{ __('All passwords are encrypted using high-strength bcrypt hashes. We never store credit card numbers on our servers. You may request account deletion at any time via your Profile settings.') }}
                </p>

                <div class="pt-3 border-top border-secondary border-opacity-10">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4">
                        <i class="fas fa-arrow-left me-1"></i>{{ __('Back to Home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
