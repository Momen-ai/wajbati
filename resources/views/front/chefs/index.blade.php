@extends('layouts.frontend')

@section('content')
    <!-- MOBILE VIEW (<768px): Chefs Mobile Explorer -->
    <div class="d-md-none px-3 pt-3 pb-5 has-bottom-bar" style="background: #0a0a0a; min-height: 100vh;">
        <!-- Mobile Header (Title + Count) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="fw-bolder text-light mb-0" style="font-size: 26px; letter-spacing: -0.02em;">
                {{ __('Chefs') }}
            </h1>
            <span class="badge rounded-pill px-3 py-1 bg-primary-soft text-primary fw-bold" style="font-size: 0.8rem;">
                {{ $chefs->total() }} {{ __('Kitchens') }}
            </span>
        </div>

        <!-- Search Bar -->
        <form action="{{ route('chefs.index') }}" method="GET" class="mb-3">
            <div class="position-relative d-flex align-items-center">
                <input type="text"
                       name="search"
                       class="form-control rounded-4 text-light border-0 py-2.5 px-4 shadow-none"
                       style="background: #1c1c1c; font-size: 15px;"
                       placeholder="{{ __('Search chefs, kitchens, area...') }}"
                       value="{{ request('search') }}"
                       aria-label="{{ __('Search chefs') }}">
                <button type="submit" class="btn position-absolute end-0 top-50 translate-middle-y text-secondary border-0 p-0 pe-3" aria-label="{{ __('Search') }}">
                    <i class="fas fa-search" style="font-size: 15px;"></i>
                </button>
            </div>
        </form>

        @if(request('search'))
            <div class="d-flex justify-content-between align-items-center mb-3 p-2 rounded-3" style="background: #151515;">
                <span class="text-secondary small">"{{ request('search') }}"</span>
                <a href="{{ route('chefs.index') }}" class="text-primary text-decoration-none small fw-bold">
                    {{ __('Clear Search') }}
                </a>
            </div>
        @endif

        <!-- 1-Column Mobile Chefs Cards List -->
        <div class="d-flex flex-column gap-3">
            @forelse($chefs as $chef)
                <x-ui.chef-card :chef="$chef" />
            @empty
                <div class="py-5 text-center">
                    <x-ui.empty-state
                        icon="fas fa-hat-chef"
                        :title="__('No kitchens found.')"
                        :description="__('Try adjusting your search keywords or browse all chefs.')"
                        :actionText="__('View All Chefs')"
                        :actionUrl="route('chefs.index')" />
                </div>
            @endforelse
        </div>

        @if($chefs->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $chefs->links('pagination::bootstrap-5') }}
            </div>
        @endif

        <!-- Compact Mobile "Join as Chef" Card -->
        <div class="mt-4 p-4 rounded-4 border border-secondary border-opacity-10 text-center" style="background: linear-gradient(135deg, #151515 0%, rgba(255, 122, 24, 0.1) 100%);">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-primary mb-2" style="width: 44px; height: 44px; background: #1c1c1c;">
                <i class="fas fa-hat-chef"></i>
            </div>
            <h6 class="fw-bold text-light mb-1">{{ __('Passionate chef or kitchen? Partner with Wajbati') }}</h6>
            <p class="text-secondary small mb-3">{{ __('Turn your kitchen passion into a sustainable business.') }}</p>
            <a href="{{ route('chef.apply') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
                {{ __('Join as a Chef') }}
            </a>
        </div>
    </div>

    <!-- DESKTOP VIEW (>=768px): Strictly Unchanged -->
    <div class="d-none d-md-block">
    <!-- Inner Page Banner (Frame f_040) -->
    <x-ui.page-banner
        :image="asset('assets/hero/f_040.webp')"
        :badge="__('Local Kitchens')"
        :title="__('Meet Our Talented Chefs')"
        :subtitle="__('Discover the passionate culinary talents and kitchens behind our signature dishes. Each chef has a unique identity, a signature recipe, and a story.')">
        <x-slot name="stats">
            <span class="badge rounded-pill bg-surface-2 text-main border border-subtle px-3 py-1.5 fw-semibold small">
                <i class="fas fa-hat-chef text-primary me-1"></i> {{ $totalKitchens ?? $chefs->total() }} {{ __('Kitchens') }}
            </span>
            <span class="badge rounded-pill bg-surface-2 text-main border border-subtle px-3 py-1.5 fw-semibold small">
                <i class="fas fa-utensils text-primary me-1"></i> {{ $totalDishes ?? 14 }} {{ __('Dishes') }}
            </span>
            <span class="badge rounded-pill bg-surface-2 text-main border border-subtle px-3 py-1.5 fw-semibold small">
                <i class="fas fa-check-circle text-success me-1"></i> {{ __('100% Authentic & Crafted') }}
            </span>
        </x-slot>

        <x-slot name="actions">
            <form action="{{ route('chefs.index') }}" method="GET" class="search-box p-2 shadow-sm d-flex gap-2 rounded-pill" style="background: var(--surface-1); border: 1px solid var(--border-medium);">
                <input type="text" name="search" class="form-control border-0 bg-transparent text-main flex-grow-1 px-4 shadow-none" placeholder="{{ __('Search chefs, kitchens, area...') }}" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary rounded-pill px-4 text-nowrap fw-semibold">
                    <i class="fas fa-search me-1"></i> {{ __('Find') }}
                </button>
            </form>
        </x-slot>
    </x-ui.page-banner>

    <!-- Chefs Grid Container -->
    <div class="container py-5 ambient-glow-page">
        @if(request('search'))
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-secondary mb-0">
                    {{ __('Showing results for') }}: <strong class="text-main">"{{ request('search') }}"</strong>
                </p>
                <a href="{{ route('chefs.index') }}" class="btn btn-outline-subtle btn-sm rounded-pill text-secondary">
                    {{ __('Clear Search') }}
                </a>
            </div>
        @endif

        @if($chefs->count() > 0)
            <div class="chefs-auto-grid" data-reveal-stagger>
                @foreach($chefs as $chef)
                    <x-ui.chef-card :chef="$chef" />
                @endforeach
            </div>

            @if($chefs->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $chefs->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="py-5">
                <x-ui.empty-state
                    icon="fas fa-hat-chef"
                    :title="__('No kitchens found.')"
                    :description="__('Try adjusting your search keywords or browse all chefs.')"
                    :actionText="__('View All Chefs')"
                    :actionUrl="route('chefs.index')" />
            </div>
        @endif

        <!-- 3-Step "How It Works" Section -->
        <div class="mt-5 pt-5 border-top border-subtle">
            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-1 bg-surface-2 text-primary border border-subtle small fw-bold mb-2">
                    {{ __('Simple Process') }}
                </span>
                <h3 class="fw-bold text-main mb-2">{{ __('How Wajbati Works') }}</h3>
                <p class="text-secondary small mb-0">{{ __('Three simple steps to enjoy healthy, fresh, handcrafted food.') }}</p>
            </div>

            <div class="row g-4 justify-content-center text-center">
                <div class="col-12 col-md-4">
                    <div class="spotlight-card rounded-4 p-4 h-100 d-flex flex-column align-items-center border border-subtle" data-spotlight>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 bg-surface-2 text-primary shadow-sm" style="width: 58px; height: 58px;">
                            <i class="fas fa-store fa-lg"></i>
                        </div>
                        <h5 class="fw-bold text-main mb-2">1. {{ __('Choose a Kitchen') }}</h5>
                        <p class="text-secondary small mb-0">{{ __('Browse verified chefs and independent kitchens, view their menus, customer ratings, and culinary story.') }}</p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="spotlight-card rounded-4 p-4 h-100 d-flex flex-column align-items-center border border-subtle" data-spotlight>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 bg-surface-2 text-primary shadow-sm" style="width: 58px; height: 58px;">
                            <i class="fas fa-fire-burner fa-lg"></i>
                        </div>
                        <h5 class="fw-bold text-main mb-2">2. {{ __('Chef Cooks Fresh') }}</h5>
                        <p class="text-secondary small mb-0">{{ __('Each dish is prepared fresh on-demand with premium local olive oil and hand-selected ingredients.') }}</p>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="spotlight-card rounded-4 p-4 h-100 d-flex flex-column align-items-center border border-subtle" data-spotlight>
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-3 bg-surface-2 text-primary shadow-sm" style="width: 58px; height: 58px;">
                            <i class="fas fa-truck-fast fa-lg"></i>
                        </div>
                        <h5 class="fw-bold text-main mb-2">3. {{ __('Delivered to You') }}</h5>
                        <p class="text-secondary small mb-0">{{ __('Fast and carefully packaged delivery straight from the kitchen to your doorstep.') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- "Cook at Home? Join as a Chef" CTA Band -->
        <div class="mt-5 pt-3">
            <div class="spotlight-card rounded-4 p-4 p-md-5 border border-subtle position-relative overflow-hidden" data-spotlight style="background: linear-gradient(135deg, var(--surface-1) 0%, rgba(255, 122, 24, 0.08) 100%);">
                <div class="row align-items-center justify-content-between g-4 position-relative" style="z-index: 1;">
                    <div class="col-lg-8">
                        <span class="badge rounded-pill px-3 py-1 bg-primary text-white small fw-bold mb-3">
                            {{ __('Chef Opportunities') }}
                        </span>
                        <h3 class="display-6 fw-bold text-main mb-2">
                            {{ __('Passionate chef or kitchen? Partner with Wajbati') }}
                        </h3>
                        <p class="text-secondary lead fs-6 mb-0">
                            {{ __('Turn your culinary passion into a thriving business. Reach wider audiences and share your culinary creations with food lovers.') }}
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="{{ route('chef.apply') }}" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-lg" data-magnetic>
                            <i class="fas fa-hat-chef me-2"></i> {{ __('Join as a Chef') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
