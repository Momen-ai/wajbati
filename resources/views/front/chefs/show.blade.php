@extends('layouts.frontend')

@section('content')
    <!-- MOBILE VIEW (<768px): Chef Detail Page -->
    <div class="d-md-none has-bottom-bar" style="background: #0a0a0a; min-height: 100vh;">
        <!-- Top Nav Bar with Back & Share -->
        <div class="px-3 pt-3 pb-2 d-flex align-items-center justify-content-between">
            <a href="{{ route('chefs.index') }}"
               class="btn d-inline-flex align-items-center justify-content-center rounded-circle border border-secondary border-opacity-25"
               style="width: 40px; height: 40px; background: rgba(21, 21, 21, 0.75); color: #f2f2f2;">
                <i class="fas fa-chevron-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
            </a>
            <span class="fw-bold text-light small">{{ $chef->kitchen_name ?? $chef->name }}</span>
            <button type="button"
                    class="btn d-inline-flex align-items-center justify-content-center rounded-circle border border-secondary border-opacity-25"
                    style="width: 40px; height: 40px; background: rgba(21, 21, 21, 0.75); color: #f2f2f2;"
                    onclick="navigator.share ? navigator.share({title: '{{ $chef->kitchen_name ?? $chef->name }}', url: window.location.href}) : null">
                <i class="fas fa-share-alt"></i>
            </button>
        </div>

        <!-- Kitchen Info Card -->
        <div class="px-3 pt-2 pb-3">
            <div class="p-4 rounded-4 border border-secondary border-opacity-10 text-center shadow-lg" style="background: var(--surface-1);">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow border border-primary border-opacity-30 position-relative"
                     style="width: 80px; height: 80px; background: var(--surface-2);">
                    @if ($chef->relationLoaded('image') && $chef->image)
                        <img src="{{ asset('storage/' . $chef->image->image_path) }}" class="w-100 h-100 rounded-circle object-fit-cover" alt="{{ $chef->name }}">
                    @else
                        <i class="fas fa-hat-chef fa-2x text-primary"></i>
                    @endif
                    <span class="position-absolute bottom-0 end-0 bg-primary text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                          style="width: 22px; height: 22px; font-size: 10px; border: 2px solid var(--surface-1);">
                        <i class="fas fa-check"></i>
                    </span>
                </div>

                <h1 class="h5 fw-bold text-light mb-1">{{ $chef->kitchen_name ?? $chef->name }}</h1>
                <p class="text-secondary small mb-3">
                    <i class="fas fa-map-marker-alt text-primary me-1"></i>
                    {{ $chef->address ?? __('Local Chef') }}
                </p>

                <!-- Chips Row -->
                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                    <span class="badge px-3 py-1.5 rounded-pill border border-secondary border-opacity-10 d-inline-flex align-items-center gap-1"
                          style="background: var(--surface-2); color: #f5b045; font-size: 0.8rem;">
                        <i class="fas fa-star"></i> 5.0
                    </span>
                    <span class="badge px-3 py-1.5 rounded-pill border border-secondary border-opacity-10 d-inline-flex align-items-center gap-1"
                          style="background: var(--surface-2); color: var(--text-main); font-size: 0.8rem;">
                        <i class="fas fa-utensils text-primary"></i> {{ $chef->meals->count() }} {{ __('Meals') }}
                    </span>
                    <span class="badge px-3 py-1.5 rounded-pill border border-success border-opacity-20 d-inline-flex align-items-center gap-1 text-success"
                          style="background: rgba(74, 222, 128, 0.1); font-size: 0.8rem;">
                        <i class="fas fa-check-circle"></i> {{ __('Verified Chef') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Meals Section (2-Column Grid) -->
        <div class="px-3 pb-5">
            <h2 class="fw-bold text-light mb-3" style="font-size: 18px;">
                {{ __('Menu') }} ({{ $chef->meals->count() }})
            </h2>

            <div class="row g-2">
                @forelse($chef->meals as $meal)
                    <div class="col-6">
                        <x-ui.meal-card :meal="$meal" />
                    </div>
                @empty
                    <div class="col-12 py-4 text-center">
                        <x-ui.empty-state
                            icon="fa-utensils"
                            :title="__('No Meals Yet')"
                            :message="__('This chef hasn\'t added any meals yet.')" />
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- DESKTOP VIEW (>=768px): Strictly Unchanged -->
    <div class="d-none d-md-block">
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
                        {{ $chef->address ?? __('Chef') }}
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
            :subtitle="__('Explore all fresh signature meals by this chef')" />

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
    </div>
@endsection
