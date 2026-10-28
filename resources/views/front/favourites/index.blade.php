@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <x-ui.page-header
        :title="__('My Favourites')"
        :subtitle="__('Your saved meals and favorite home kitchens.')">
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
