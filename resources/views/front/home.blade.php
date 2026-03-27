@extends('layouts.frontend')

@section('content')
    <!-- Hero Section -->
    <div class="container my-5">
        <div class="hero-section text-center text-lg-start">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <span class="badge bg-warning text-light mb-3 px-3 py-2 rounded-pill fw-bold"><i
                            class="fas fa-star me-1"></i> #1 Homemade Food App</span>
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
                            onerror="this.src='https://placehold.co/500x400/png?text=Delicious+Food'" alt="Delicious Food"
                            class="position-relative"
                            style="z-index:1;max-width:100%;height:auto;filter:drop-shadow(0 20px 30px rgba(0,0,0,0.15))">

                        {{-- <div class="bg-white p-3 rounded-4 shadow-sm position-absolute bottom-0 start-0 mb-4 d-none d-md-block"
                            style="z-index:2; min-width: 160px; text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-success rounded-circle p-1 {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"><i
                                        class="fas fa-check text-white small"></i></div>
                                <small class="fw-bold">{{ __('Fresh Daily') }}</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-warning rounded-circle p-1 {{ app()->getLocale() == 'ar' ? 'ms-2' : 'me-2' }}"><i class="fas fa-star text-white small"></i>
                                </div>
                                <small class="fw-bold">{{ __('4.9/5 Rating') }}</small>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="container mb-5">
        <h3 class="fw-bold mb-4 text-center">{{ __('Explore Categories') }}</h3>
        <div class="row justify-content-center g-4">
            @foreach ($categories as $category)
                <div class="col-6 col-md-3">
                    <a href="{{ route('meals.index', ['category' => $category->id]) }}" class="text-decoration-none">
                        <div class="card shadow-card bg-secondary text-center p-4 h-100 hover-lift border border-secondary border-opacity-10">
                            <div class="category-icon text-primary mb-3">
                                <i class="fas fa-utensils fa-2x"></i> <!-- Or category icon if exists -->
                            </div>
                            <h6 class="fw-bold text-light">{{ $category->name }}</h6>
                            <small class="text-light opacity-75">{{ $category->meals_count }} Meals</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Popular Meals -->
    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="mb-1">{{ __('Popular Meals') }}</h2>
                <p class="text-light mb-0">{{ __("Our customers' favorites this week") }}</p>
            </div>
            <a href="{{ route('meals.index') }}" class="btn btn-link text-decoration-none fw-bold">{{ __('View All') }} <i
                    class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            @foreach ($popularMeals as $meal)
                <div class="col-6 col-lg-4">
                    <div class="card shadow-card border border-secondary border-opacity-10 h-100 meal-card">
                        <div class="meal-card-img-wrapper">
                            @if ($meal->image)
                                <img src="{{ asset('storage/' . $meal->image->image_path) }}" alt="{{ $meal->name }}">
                            @else
                                <img src="https://placehold.co/600x400?text=No+Image" alt="{{ $meal->name }}">
                            @endif
                            <span class="card-badge text-primary-custom">{{ $meal->category->name ?? 'General' }}</span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="fw-bold mb-0 text-truncate">{{ $meal->name }}</h5>
                                <span class="fw-bold text-primary">${{ $meal->price }}</span>
                            </div>
                            <p class="text-light small mb-1 text-truncate">{{ Str::limit($meal->description, 60) }}</p>
                            <div class="mb-3 text-warning small">
                                @php $avg = $meal->averageRating(); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $avg ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                                <span class="text-light ms-1" style="font-size: 0.7rem;">({{ $meal->ratings_count ?? $meal->ratings->count() }})</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between border-top border-secondary border-secondary pt-3 mt-auto">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle fa-lg text-light me-2"></i>
                                    <small class="fw-bold text-light">{{ $meal->chef->name ?? 'Chef' }}</small>
                                </div>
                                <a href="{{ route('meals.show', $meal->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-3">View</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Featured Chefs -->
    <div class="container mb-5 pb-5">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h2 class="mb-1">{{ __('Meet Our Talented Chefs') }}</h2>
                <p class="text-light mb-0">{{ __('Local families sharing their culinary heritage') }}</p>
            </div>
            <a href="{{ route('chefs.index') }}" class="btn btn-link text-decoration-none fw-bold">{{ __('View All') }} <i
                    class="fas fa-arrow-right ms-1"></i></a>
        </div>

        <div class="row g-4">
            @foreach ($chefs as $chef)
                <div class="col-6 col-md-3">
                    <div class="card shadow-card border border-secondary border-opacity-10 text-center p-4 h-100 hover-lift">
                        <div class="position-relative mx-auto mb-3">
                            @if($chef->image)
                                <img src="{{ asset('storage/' . $chef->image->image_path) }}" class="rounded-circle shadow object-fit-cover" style="width:100px; height:100px;" alt="{{ $chef->name }}">
                            @else
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto shadow" style="width:100px; height:100px;">
                                    <i class="fas fa-user-chef fa-2x text-light"></i>
                                </div>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-1 text-light">{{ $chef->kitchen_name ?? $chef->name }}</h6>
                        <small class="text-light d-block mb-3">{{ $chef->address ?? 'Local Chef' }}</small>
                        <a href="{{ route('chefs.show', $chef->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">View Kitchen</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
