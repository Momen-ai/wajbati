@extends('layouts.frontend')

@section('content')
    <div class="bg-darker py-5 mb-5 border-bottom border-secondary border-opacity-10">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-light">{{ __('Home') }}</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('chefs.index') }}"
                            class="text-decoration-none text-light">{{ __('Chefs') }}</a></li>
                    <li class="breadcrumb-item active text-light" aria-current="page">{{ $chef->name }}</li>
                </ol>
            </nav>

            <div class="row align-items-center">
                <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                    <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center shadow p-3 border border-secondary border-opacity-10"
                        style="width:150px; height:150px;">
                        <i class="fas fa-utensils fa-4x text-light"></i>
                    </div>
                </div>
                <div class="col-md-9 text-center text-md-start">
                    <h1 class="fw-bold mb-2 text-light">{{ $chef->kitchen_name ?? $chef->name }}</h1>
                    <p class="text-light lead mb-3"><i class="fas fa-map-marker-alt me-2 text-primary"></i>
                        {{ $chef->address ?? __('Home Chef') }}</p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1"></i>
                            <span class="fw-bold text-light">5.0</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-utensils text-primary me-1"></i>
                            <span class="fw-bold text-light">{{ $chef->meals->count() }} {{ __('Meals') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0 text-light">{{ __('Menu') }}</h3>
        </div>

        <div class="row g-4">
            @forelse($chef->meals as $meal)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-card border border-secondary border-opacity-10 h-100 meal-card">
                        <div class="meal-card-img-wrapper">
                            <a href="{{ route('meals.show', $meal->id) }}">
                                @if ($meal->image)
                                    <img src="{{ asset('storage/' . $meal->image->image_path) }}" alt="{{ $meal->name }}">
                                @else
                                    <img src="{{ asset('assets/images/placeholder.svg') }}" alt="{{ $meal->name }}">
                                @endif
                            </a>
                            <span class="card-badge text-primary-custom">{{ $meal->category->name ?? __('Special') }}</span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0 text-truncate"><a href="{{ route('meals.show', $meal->id) }}"
                                        class="text-light text-decoration-none hover-text-primary">{{ $meal->name }}</a></h5>
                                <span class="fw-bold text-primary">{{ money($meal->price) }}</span>
                            </div>
                            <p class="text-light small mb-3 text-truncate">{{ Str::limit($meal->description, 60) }}</p>

                            <div class="d-flex align-items-center justify-content-between border-top border-secondary pt-3 mt-auto">
                                <form action="{{ route('cart.add') }}" method="POST" class="w-100">
                                    @csrf
                                    <input type="hidden" name="meal_id" value="{{ $meal->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                                        {{ __('Add to Cart') }} <i class="fas fa-plus ms-1"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <p class="text-light">{{ __("This chef hasn't added any meals yet.") }}</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
