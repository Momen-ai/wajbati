@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ __('My Favourites') }}</h2>
            <p class="text-muted small mb-0">{{ __('Your saved meals and favorite home kitchens.') }}</p>
        </div>
        <a href="{{ route('meals.index') }}" class="btn btn-outline-primary btn-sm rounded-pill">
            <i class="fas fa-search me-1"></i>{{ __('Discover Meals') }}
        </a>
    </div>

    <!-- Favourite Meals Section -->
    <div class="mb-5">
        <h4 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fas fa-utensils text-primary"></i>
            {{ __('Favourite Meals') }}
            <span class="badge bg-primary rounded-pill fs-6">{{ $favouriteMeals->count() }}</span>
        </h4>

        @if($favouriteMeals->isEmpty())
            <div class="card bg-dark border border-secondary border-opacity-10 rounded-4 p-4 text-center">
                <i class="far fa-heart fa-3x text-muted mb-3 opacity-50"></i>
                <h5 class="fw-bold">{{ __('No favourite meals yet') }}</h5>
                <p class="text-muted small mb-3">{{ __('Explore dishes and tap the heart icon to save them here.') }}</p>
                <div>
                    <a href="{{ route('meals.index') }}" class="btn btn-primary rounded-pill btn-sm px-4">
                        {{ __('Browse Meals') }}
                    </a>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach($favouriteMeals as $meal)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm">
                            <div class="position-relative" style="height: 180px;">
                                <img src="{{ $meal->image ? asset('storage/' . $meal->image->url) : asset('assets/images/placeholder.svg') }}"
                                     alt="{{ $meal->name }}"
                                     class="w-100 h-100 object-fit-cover">
                                <form action="{{ route('favourites.meal.toggle', $meal) }}" method="POST" class="position-absolute top-0 end-0 m-3">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light rounded-circle shadow-sm text-danger" title="{{ __('Remove') }}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold mb-0">
                                        <a href="{{ route('meals.show', $meal->id) }}" class="text-decoration-none text-light">
                                            {{ $meal->name }}
                                        </a>
                                    </h5>
                                    <span class="text-primary fw-bold">{{ money($meal->price) }}</span>
                                </div>
                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($meal->description, 70) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-10">
                                    <small class="text-muted">
                                        <i class="fas fa-user-circle me-1"></i>{{ $meal->chef->name ?? __('Chef') }}
                                    </small>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="meal_id" value="{{ $meal->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">
                                            <i class="fas fa-plus me-1"></i>{{ __('Add to Cart') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Favourite Chefs Section -->
    <div>
        <h4 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="fas fa-hat-chef text-primary"></i>
            {{ __('Favourite Chefs') }}
            <span class="badge bg-primary rounded-pill fs-6">{{ $favouriteChefs->count() }}</span>
        </h4>

        @if($favouriteChefs->isEmpty())
            <div class="card bg-dark border border-secondary border-opacity-10 rounded-4 p-4 text-center">
                <i class="far fa-user fa-3x text-muted mb-3 opacity-50"></i>
                <h5 class="fw-bold">{{ __('No favourite chefs yet') }}</h5>
                <p class="text-muted small mb-3">{{ __('Follow your favorite local kitchens to easily view their menu.') }}</p>
                <div>
                    <a href="{{ route('chefs.index') }}" class="btn btn-primary rounded-pill btn-sm px-4">
                        {{ __('Meet Chefs') }}
                    </a>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach($favouriteChefs as $chef)
                    <div class="col-md-6 col-lg-3">
                        <div class="card text-center h-100 border border-secondary border-opacity-10 rounded-4 p-3 shadow-sm">
                            <div class="position-relative d-inline-block mx-auto mb-3">
                                <img src="{{ $chef->image ? asset('storage/' . $chef->image->url) : asset('assets/images/placeholder.svg') }}"
                                     alt="{{ $chef->name }}"
                                     class="rounded-circle object-fit-cover shadow-sm"
                                     style="width: 80px; height: 80px;">
                                <form action="{{ route('favourites.chef.toggle', $chef) }}" method="POST" class="position-absolute top-0 end-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-light rounded-circle shadow-sm text-danger p-1" style="width: 26px; height: 26px; font-size: 0.75rem;" title="{{ __('Remove') }}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </form>
                            </div>
                            <h5 class="fw-bold mb-1">{{ $chef->name }}</h5>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-utensils me-1"></i>{{ $chef->meals->count() }} {{ __('Meals') }}
                            </p>
                            <a href="{{ route('chefs.show', $chef->id) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100">
                                {{ __('View Kitchen') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
