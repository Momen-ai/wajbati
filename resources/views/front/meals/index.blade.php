@extends('layouts.frontend')

@section('content')
    <div class="container py-4">
<div class="container py-5">
    <!-- Header/Search Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="fw-800 tracking-tight mb-2"><span class="text-primary-custom">Wajbati</span> Explorer</h1>
            <p class="text-muted mb-0">Discover delicious home-made meals in your neighborhood.</p>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <form action="{{ route('meals.index') }}" method="GET" class="search-box glass-panel p-2 shadow-sm d-flex gap-2 rounded-pill">
                <input type="text" name="search" class="form-control border-0 bg-transparent flex-grow-1 px-4 shadow-none" placeholder="What are you craving today?" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary rounded-pill px-4 text-nowrap"><i class="fas fa-search me-2"></i>Find</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="glass-panel p-4 mb-4">
                <form action="{{ route('meals.index') }}" method="GET" id="filterForm">
                    <h6 class="fw-700 mb-4 pb-2 border-bottom border-secondary border-opacity-10 text-light"><i class="fas fa-sliders-h me-2"></i>Filters</h6>
                    
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-600 text-muted opacity-50 text-uppercase mb-3">Category</label>
                        <select name="category" class="form-select bg-dark border-secondary border-opacity-10 py-2" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-600 text-muted opacity-50 text-uppercase mb-3">Price Range ($)</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="number" name="min_price" class="form-control bg-dark border-secondary border-opacity-10 py-2 px-3" placeholder="Min" value="{{ request('min_price') }}">
                            <span class="text-muted">-</span>
                            <input type="number" name="max_price" class="form-control bg-dark border-secondary border-opacity-10 py-2 px-3" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-4">
                        <label class="form-label small fw-600 text-muted opacity-50 text-uppercase mb-3">Sort By</label>
                        <select name="sort" class="form-select bg-dark border-secondary border-opacity-10 py-2" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrival</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="top_rated" {{ request('sort') == 'top_rated' ? 'selected' : '' }}>Top Rated First</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-outline-primary w-100 rounded-pill mt-2">Apply Filters</button>
                    <a href="{{ route('meals.index') }}" class="btn btn-link w-100 text-muted small mt-2">Clear All</a>
                </form>
            </div>
            
            <!-- Quick Category Badges (Optional/Visual) -->
            <div class="glass-panel p-4 d-none d-lg-block">
                <h6 class="fw-700 mb-3 text-light">Quick Tags</h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($categories->take(8) as $cat)
                        <a href="{{ route('meals.index', ['category' => $cat->id]) }}" class="badge rounded-pill bg-secondary bg-opacity-10 text-muted border border-secondary border-opacity-10 text-decoration-none px-3 py-2 hover-lift">{{ $cat->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Meals Grid -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="text-muted mb-0">Showing <span class="text-light fw-600">{{ $meals->total() }}</span> meals</p>
                <div class="d-none d-md-block opacity-50">
                    <button class="btn btn-sm btn-dark mx-1 p-2 active"><i class="fas fa-th"></i></button>
                    <button class="btn btn-sm btn-dark mx-1 p-2"><i class="fas fa-list"></i></button>
                </div>
            </div>

            <div class="row g-4">
                @forelse($meals as $meal)
                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card shadow-card border border-secondary border-opacity-10 h-100 meal-card hover-lift">
                            <div class="meal-card-img-wrapper">
                                @if ($meal->image)
                                    <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="card-img-top">
                                @else
                                    <img src="https://placehold.co/600x400?text=No+Image" alt="{{ $meal->name }}">
                                @endif
                                <span class="card-badge">{{ $meal->category->name ?? 'Meal' }}</span>
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold text-light m-0">{{ $meal->name }}</h5>
                                    <span class="text-primary-custom fw-800">${{ $meal->price }}</span>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-warning small me-2">
                                        @php $avg = $meal->ratings_avg_star ?: 0; @endphp
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $avg ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">({{ $meal->ratings_count ?: 0 }})</span>
                                </div>

                                <p class="text-muted small mb-4 line-clamp-2">
                                    <i class="fas fa-user-circle me-1 text-primary-custom opacity-50"></i> Chef {{ $meal->chef->name ?? 'Home Cook' }}
                                </p>

                                <a href="{{ route('meals.show', $meal->id) }}" class="btn btn-primary mt-auto rounded-pill py-2">
                                    View Details <i class="fas fa-arrow-right ms-2 scale-75"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="glass-panel py-5 px-4 w-100">
                            <i class="fas fa-search-minus fa-3x text-muted mb-4 opacity-50"></i>
                            <h4 class="text-light">No meals found!</h4>
                            <p class="text-muted">Try adjusting your filters or search terms.</p>
                            <a href="{{ route('meals.index') }}" class="btn btn-outline-primary rounded-pill mt-3">Reset All Filters</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-5 pt-4 d-flex justify-content-center">
                {{ $meals->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
    </div>
@endsection
