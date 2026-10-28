@extends('layouts.frontend')

@section('content')
    <!-- Hero Canvas Sequence Section -->
    <div id="hero-sequence-container" class="hero-sequence-container mb-5" data-base-path="{{ asset('assets/hero/') }}/">
        <canvas id="hero-canvas" class="hero-canvas"></canvas>
        <div class="hero-vignette"></div>

        <div class="hero-copy-container">
            <!-- Beat 1: frames 1-12 -->
            <div id="hero-beat-1" class="hero-copy-beat">
                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 mb-3 px-3 py-2 rounded-pill fw-bold">
                    <i class="fas fa-star me-1 text-warning"></i> {{ __('#1 Homemade Food App') }}
                </span>
                <h1 class="hero-heading-display text-gradient-primary">
                    {{ __('Real home-cooked food') }}
                </h1>
                <p class="hero-sub-display">
                    {{ __('Order healthy, fresh, and authentic food from local home kitchens.') }}
                </p>
            </div>

            <!-- Beat 2: frames 13-28 -->
            <div id="hero-beat-2" class="hero-copy-beat" style="opacity: 0;">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 mb-3 px-3 py-2 rounded-pill fw-bold">
                    <i class="fas fa-map-marker-alt me-1"></i> {{ __('Near You') }}
                </span>
                <h2 class="hero-heading-display">
                    {{ __("From a chef's kitchen near you") }}
                </h2>
                <p class="hero-sub-display">
                    {{ __('Prepared with passion and fresh local ingredients every single day.') }}
                </p>
            </div>

            <!-- Beat 3: frames 29-49 -->
            <div id="hero-beat-3" class="hero-copy-beat" style="opacity: 0;">
                <h2 class="hero-heading-display mb-3">
                    {{ __('Every bite made by someone you can trust') }}
                </h2>
                <p class="hero-sub-display mb-4">
                    {{ __('Direct from home chefs to your table with love and authenticity.') }}
                </p>

                <!-- Search Form -->
                <div class="interactive mb-4">
                    <form action="{{ route('meals.index') }}" method="GET">
                        <div class="hero-search-bar input-group">
                            <span class="input-group-text bg-transparent border-0 text-secondary ps-3">
                                <i class="fas fa-search text-primary"></i>
                            </span>
                            <input type="text" name="search" class="form-control bg-transparent border-0 text-light shadow-none" placeholder="{{ __('Search for meals, kitchens...') }}">
                            <button class="btn btn-primary rounded-pill px-4 fw-bold" type="submit" data-magnetic>{{ __('Search') }}</button>
                        </div>
                    </form>
                </div>

                <!-- CTAs -->
                <div class="interactive d-flex gap-3 justify-content-center align-items-center flex-wrap">
                    <a href="{{ route('meals.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-sm" data-magnetic>
                        {{ __('Browse Meals') }}
                    </a>
                    <a href="{{ route('register', ['role' => 'chef']) }}" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-semibold" data-magnetic>
                        {{ __('Join as a Chef') }} <i class="fas fa-arrow-right ms-2 small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="container mb-5">
        <x-ui.section-heading
            :title="__('Explore Categories')"
            :subtitle="__('Browse by dish categories and homemade specialties')" />

        <div class="row justify-content-center g-3 g-md-4">
            @foreach ($categories as $category)
                <div class="col-6 col-md-3">
                    <x-ui.category-card :category="$category" />
                </div>
            @endforeach
        </div>
    </div>

    <!-- Popular Meals Section -->
    <div class="container mb-5">
        <x-ui.section-heading
            :title="__('Popular Meals')"
            :subtitle="__('Our customers\' favorites this week')"
            :viewAllUrl="route('meals.index')" />

        <div class="row g-3 g-md-4">
            @foreach ($popularMeals as $meal)
                <div class="col-12 col-sm-6 col-lg-4">
                    <x-ui.meal-card :meal="$meal" />
                </div>
            @endforeach
        </div>
    </div>

    <!-- Featured Chefs Section -->
    <div class="container mb-5 pb-5">
        <x-ui.section-heading
            :title="__('Meet Our Talented Chefs')"
            :subtitle="__('Local families sharing their culinary heritage')"
            :viewAllUrl="route('chefs.index')" />

        <div class="row g-3 g-md-4">
            @foreach ($chefs as $chef)
                <div class="col-6 col-md-3">
                    <x-ui.chef-card :chef="$chef" />
                </div>
            @endforeach
        </div>
    </div>
@endsection
