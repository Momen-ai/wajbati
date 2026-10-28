@extends('layouts.frontend')

@section('content')
    <!-- Chef Profile Header -->
    <div class="py-5 mb-5 border-bottom border-secondary border-opacity-10 position-relative overflow-hidden" style="background: var(--surface-1);">
        <div class="position-absolute top-0 start-50 translate-middle-x w-100 h-100" style="background: radial-gradient(circle at 50% 20%, var(--primary-soft) 0%, transparent 60%); pointer-events: none;"></div>
        
        <div class="container position-relative" style="z-index: 1;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-secondary hover-text-primary">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('chefs.index') }}" class="text-secondary hover-text-primary">{{ __('Chefs') }}</a></li>
                    <li class="breadcrumb-item active text-light" aria-current="page">{{ $chef->kitchen_name ?? $chef->name }}</li>
                </ol>
            </nav>

            <div class="row align-items-center g-4">
                <div class="col-md-auto text-center text-md-start">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg border border-primary border-opacity-30 position-relative"
                        style="width: 120px; height: 120px; background: var(--surface-2);">
                        <i class="fas fa-hat-chef fa-3x text-primary"></i>
                    </div>
                </div>
                <div class="col-md text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2 flex-wrap">
                        <h1 class="h2 fw-bold text-light mb-0">{{ $chef->kitchen_name ?? $chef->name }}</h1>
                        <span class="badge rounded-pill px-3 py-1 bg-success bg-opacity-10 text-success border border-success border-opacity-20 small">
                            <i class="fas fa-check-circle me-1"></i> {{ __('Verified Chef') }}
                        </span>
                    </div>

                    <p class="text-secondary mb-3">
                        <i class="fas fa-map-marker-alt me-1 text-primary"></i>
                        {{ $chef->address ?? __('Home Chef') }}
                    </p>

                    <div class="d-flex justify-content-center justify-content-md-start align-items-center gap-3">
                        <div class="d-flex align-items-center px-3 py-1 rounded-pill border border-secondary border-opacity-10" style="background: var(--surface-2);">
                            <i class="fas fa-star text-warning me-1"></i>
                            <span class="fw-bold text-light small">5.0</span>
                        </div>
                        <div class="d-flex align-items-center px-3 py-1 rounded-pill border border-secondary border-opacity-10" style="background: var(--surface-2);">
                            <i class="fas fa-utensils text-primary me-1"></i>
                            <span class="fw-bold text-light small">{{ $chef->meals->count() }} {{ __('Meals') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chef Menu Grid -->
    <div class="container mb-5 pb-5">
        <x-ui.section-heading
            :title="__('Menu')"
            :subtitle="__('Explore all fresh homemade meals by this chef')" />

        <div class="row g-4" data-reveal-stagger>
            @forelse($chef->meals as $meal)
                <div class="col-12 col-sm-6 col-lg-4">
                    <x-ui.meal-card :meal="$meal" />
                </div>
            @empty
                <div class="col-12">
                    <x-ui.empty-state
                        icon="fa-utensils"
                        :title="__('No Meals Yet')"
                        :message="__('This chef hasn\'t added any meals yet.')" />
                </div>
            @endforelse
        </div>
    </div>
@endsection
@endsection
