@extends('layouts.frontend')

@section('content')
    <!-- Hero Section -->
    <div class="container my-5">
        <div class="hero-section text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <span class="badge bg-warning text-light mb-3 px-3 py-2 rounded-pill fw-bold"><i
                            class="fas fa-star me-1"></i> {{ __('#1 Homemade Food App') }}</span>
                    <h1 class="hero-title pt-2">{{ __('Delicious Homemade Meals') }},<br><span class="text-primary-custom">{{ __('brought to your doorstep.') }}</span></h1>
                    <p class="lead text-light mb-4">{{ __("Order healthy, fresh, and authentic food from local home kitchens.") }}</p>
                    
                    <form action="{{ route('meals.index') }}" method="GET" class="mb-4">
                        <div class="input-group input-group-lg bg-darker rounded-pill p-1 shadow-lg border border-secondary border-opacity-10" style="max-width: 500px;">
                            <span class="input-group-text bg-transparent border-0 text-secondary ps-4">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control bg-transparent border-0 text-light shadow-none" placeholder="{{ __('Search for meals, kitchens...') }}">
                            <button class="btn btn-primary rounded-pill px-4 fw-bold" type="submit">{{ __('Search') }}</button>
                        </div>
                    </form>

                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                        <a href="{{ route('meals.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                             {{ __('Browse All') }}
                        </a>
                        <a href="{{ route('register', ['role' => 'chef']) }}" class="btn btn-link text-light text-decoration-none d-flex align-items-center">
                            {{ __('Join as a Chef') }} <i class="fas fa-chevron-right ms-2 small"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="position-relative d-inline-block">
                        <div class="position-absolute top-50 start-50 translate-middle bg-primary-custom rounded-circle"
                            style="width:300px;height:300px;opacity:0.1;z-index:0;filter:blur(40px)"></div>
                        <img src="{{ asset('assets/images/hero-plate.png') }}"
                            onerror="this.src='{{ asset('assets/images/placeholder.svg') }}'" alt="Delicious Food"
                            class="position-relative"
                            style="z-index:1;max-width:100%;height:auto;filter:drop-shadow(0 20px 30px rgba(0,0,0,0.15))">
                    </div>
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
