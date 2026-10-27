@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-4 p-md-5">
                <h2 class="fw-bold text-light mb-4">{{ __('Terms of Service') }}</h2>

                <h5 class="fw-bold text-primary mb-2">1. {{ __('Acceptance of Terms') }}</h5>
                <p class="text-muted mb-4 small leading-relaxed">
                    {{ __('By accessing or using Wajbati, you agree to comply with our terms, community standards, and applicable local food preparation regulations.') }}
                </p>

                <h5 class="fw-bold text-primary mb-2">2. {{ __('Home Kitchen Quality & Hygiene') }}</h5>
                <p class="text-muted mb-4 small leading-relaxed">
                    {{ __('All participating home chefs commit to maintaining strict hygiene, fresh ingredients, and accurate allergy descriptions for every dish on the menu.') }}
                </p>

                <h5 class="fw-bold text-primary mb-2">3. {{ __('Orders & Cancellations') }}</h5>
                <p class="text-muted mb-4 small leading-relaxed">
                    {{ __('Customers may cancel orders while they remain in Pending status. Once a chef starts preparing food, orders cannot be cancelled.') }}
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
