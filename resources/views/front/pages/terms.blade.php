@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4 p-md-5 rounded-4 border border-secondary border-opacity-10 shadow-lg" style="background: var(--surface-1);">
                <h1 class="h2 fw-bold text-light mb-4">{{ __('Terms of Service') }}</h1>

                <h5 class="fw-bold text-primary mb-2">1. {{ __('Acceptance of Terms') }}</h5>
                <p class="text-secondary mb-4 small lh-lg">
                    {{ __('By accessing or using Wajbati, you agree to comply with our terms, community standards, and applicable local food preparation regulations.') }}
                </p>

                <h5 class="fw-bold text-primary mb-2">2. {{ __('Kitchen Quality & Hygiene Standards') }}</h5>
                <p class="text-secondary mb-4 small lh-lg">
                    {{ __('All participating chefs and kitchens commit to maintaining strict hygiene, fresh ingredients, and accurate allergy descriptions for every dish on the menu.') }}
                </p>

                <h5 class="fw-bold text-primary mb-2">3. {{ __('Orders & Cancellations') }}</h5>
                <p class="text-secondary mb-4 small lh-lg">
                    {{ __('Customers may cancel orders while they remain in Pending status. Once a chef starts preparing food, orders cannot be cancelled.') }}
                </p>

                <div class="pt-3 border-top border-secondary border-opacity-10">
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4" data-magnetic>
                        <i class="fas fa-arrow-left me-1"></i>{{ __('Back to Home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
