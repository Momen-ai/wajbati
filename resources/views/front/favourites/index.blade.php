@extends('layouts.frontend')

@section('content')
    <!-- MOBILE VIEW (<768px): Favourites -->
    <div class="d-md-none px-3 pt-3 pb-5 has-bottom-bar" style="background: #0a0a0a; min-height: 100vh;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="fw-bolder text-light mb-0" style="font-size: 26px; letter-spacing: -0.02em;">
                {{ __('My Favourites') }}
            </h1>
        </div>

        <!-- Favourite Meals Section -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="fw-bold text-light mb-0" style="font-size: 16px;">
                    <i class="fas fa-utensils text-primary me-1"></i> {{ __('Favourite Meals') }}
                </h2>
                <span class="badge rounded-pill px-2.5 py-1 bg-primary-soft text-primary fw-bold" style="font-size: 0.75rem;">
                    {{ $favouriteMeals->count() }}
                </span>
            </div>

            @if($favouriteMeals->isEmpty())
                <div class="p-4 rounded-4 border border-secondary border-opacity-10 text-center my-2" style="background: var(--surface-1);">
                    <i class="far fa-heart fa-2x text-secondary opacity-50 mb-2"></i>
                    <p class="text-secondary small mb-0">{{ __('No favourite meals yet') }}</p>
                </div>
            @else
                <div class="row g-2">
                    @foreach($favouriteMeals as $meal)
                        <div class="col-6">
                            <x-ui.meal-card :meal="$meal" :isFavorited="true" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Favourite Chefs Section -->
        <div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="fw-bold text-light mb-0" style="font-size: 16px;">
                    <i class="fas fa-hat-chef text-primary me-1"></i> {{ __('Favourite Chefs') }}
                </h2>
                <span class="badge rounded-pill px-2.5 py-1 bg-primary-soft text-primary fw-bold" style="font-size: 0.75rem;">
                    {{ $favouriteChefs->count() }}
                </span>
            </div>

            @if($favouriteChefs->isEmpty())
                <div class="p-4 rounded-4 border border-secondary border-opacity-10 text-center my-2" style="background: var(--surface-1);">
                    <i class="far fa-user fa-2x text-secondary opacity-50 mb-2"></i>
                    <p class="text-secondary small mb-0">{{ __('No favourite chefs yet') }}</p>
                </div>
            @else
                <div class="d-flex flex-column gap-2">
                    @foreach($favouriteChefs as $chef)
                        <x-ui.chef-card :chef="$chef" :isFavorited="true" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- DESKTOP VIEW (>=768px): Strictly Unchanged -->
    <div class="container py-5 d-none d-md-block">
    <x-ui.page-header
        :title="__('My Favourites')"
        :subtitle="__('Your saved meals and favorite kitchens.')">
        <x-slot name="actions">
            <a href="{{ route('meals.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="fas fa-search me-1"></i>{{ __('Discover Meals') }}
            </a>
        </x-slot>
    </x-ui.page-header>

    <!-- Favourite Meals Section -->
    <div class="mb-5 pb-3">
        <div class="d-flex align-items-center gap-2 mb-4">
            <div class="rounded-circle bg-primary-soft text-primary d-inline-flex align-items-center justify-content-center"
                 style="width: 36px; height: 36px;">
                <i class="fas fa-utensils"></i>
            </div>
            <h4 class="fw-bold text-main mb-0">{{ __('Favourite Meals') }}</h4>
            <span class="badge badge-primary">{{ $favouriteMeals->count() }}</span>
        </div>

        @if($favouriteMeals->isEmpty())
            <x-ui.empty-state
                icon="far fa-heart"
                :title="__('No favourite meals yet')"
                :description="__('Explore dishes and tap the heart icon to save them here.')"
                :actionText="__('Browse Meals')"
                :actionUrl="route('meals.index')" />
        @else
            <div class="row g-4">
                @foreach($favouriteMeals as $meal)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <x-ui.meal-card :meal="$meal" :isFavorited="true" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Favourite Chefs Section -->
    <div>
        <div class="d-flex align-items-center gap-2 mb-4">
            <div class="rounded-circle bg-primary-soft text-primary d-inline-flex align-items-center justify-content-center"
                 style="width: 36px; height: 36px;">
                <i class="fas fa-hat-chef"></i>
            </div>
            <h4 class="fw-bold text-main mb-0">{{ __('Favourite Chefs') }}</h4>
            <span class="badge badge-primary">{{ $favouriteChefs->count() }}</span>
        </div>

        @if($favouriteChefs->isEmpty())
            <x-ui.empty-state
                icon="far fa-user"
                :title="__('No favourite chefs yet')"
                :description="__('Follow your favorite local kitchens to easily view their menu.')"
                :actionText="__('Meet Chefs')"
                :actionUrl="route('chefs.index')" />
        @else
            <div class="row g-4">
                @foreach($favouriteChefs as $chef)
                    <div class="col-12 col-sm-6 col-lg-3">
                        <x-ui.chef-card :chef="$chef" :isFavorited="true" />
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
