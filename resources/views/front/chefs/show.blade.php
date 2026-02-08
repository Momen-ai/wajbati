@extends('layouts.frontend')

@section('content')
    <div class="bg-light py-5 mb-5 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('chefs.index') }}"
                            class="text-decoration-none text-muted">Chefs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $chef->name }}</li>
                </ol>
            </nav>

            <div class="row align-items-center">
                <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                    <div class="rounded-circle bg-white d-inline-flex align-items-center justify-content-center shadow p-3"
                        style="width:150px; height:150px;">
                        <i class="fas fa-user-chef fa-4x text-secondary"></i>
                    </div>
                </div>
                <div class="col-md-9 text-center text-md-start">
                    <h1 class="fw-bold mb-2">{{ $chef->kitchen_name ?? $chef->name }}'s Kitchen</h1>
                    <p class="text-muted lead mb-3"><i class="fas fa-map-marker-alt me-2 text-primary"></i>
                        {{ $chef->address ?? 'Home Chef' }}</p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-star text-warning me-1"></i>
                            <span class="fw-bold">4.8</span> <span class="text-muted small ms-1">(150 Reviews)</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-utensils text-primary me-1"></i>
                            <span class="fw-bold">{{ $chef->meals->count() }} Meals</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">Menu</h3>
            <!-- Optional: Filter for chef's menu -->
        </div>

        <div class="row g-4">
            @forelse($chef->meals as $meal)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-card border-0 h-100 meal-card">
                        <div class="meal-card-img-wrapper">
                            <a href="{{ route('meals.show', $meal->id) }}">
                                @if ($meal->image)
                                    <img src="{{ asset('storage/' . $meal->image->image_path) }}" alt="{{ $meal->name }}">
                                @else
                                    <img src="https://placehold.co/600x400?text=No+Image" alt="{{ $meal->name }}">
                                @endif
                            </a>
                            <span class="card-badge text-primary-custom">{{ $meal->category->name ?? 'Special' }}</span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0 text-truncate"><a href="{{ route('meals.show', $meal->id) }}"
                                        class="text-dark">{{ $meal->name }}</a></h5>
                                <span class="fw-bold text-primary">${{ $meal->price }}</span>
                            </div>
                            <p class="text-muted small mb-3 text-truncate">{{ Str::limit($meal->description, 60) }}</p>

                            <div class="d-flex align-items-center justify-content-between border-top pt-3 mt-auto">
                                <form action="{{ route('cart.add') }}" method="POST" class="w-100">
                                    @csrf
                                    <input type="hidden" name="meal_id" value="{{ $meal->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary w-100 rounded-pill">
                                        Add to Cart <i class="fas fa-plus ms-1"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 py-5 text-center">
                    <p class="text-muted">This chef hasn't added any meals yet.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
