@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <!-- Header/Search Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="fw-800 tracking-tight mb-2"><span class="text-primary-custom">{{ __('Wajbati') }}</span> {{ __('Explorer') }}</h1>
            <p class="text-muted mb-0">{{ __('Discover delicious home-made meals in your neighborhood.') }}</p>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <form action="{{ route('meals.index') }}" method="GET" class="search-box glass-panel p-2 shadow-sm d-flex gap-2 rounded-pill">
                <input type="text" name="search" class="form-control border-0 bg-transparent flex-grow-1 px-4 shadow-none" placeholder="{{ __('What are you craving today?') }}" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary rounded-pill px-4 text-nowrap"><i class="fas fa-search me-2"></i>{{ __('Find') }}</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="glass-panel p-4 mb-4">
                <form action="{{ route('meals.index') }}" method="GET" id="filterForm">
                    <h6 class="fw-700 mb-4 pb-2 border-bottom border-secondary border-opacity-10 text-light"><i class="fas fa-sliders-h me-2"></i>{{ __('Filters') }}</h6>
                    
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <!-- Category Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-600 text-muted opacity-50 text-uppercase mb-3">{{ __('Category') }}</label>
                        <select name="category" class="form-select bg-dark border-secondary border-opacity-10 py-2" onchange="this.form.submit()">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-4">
                        <label class="form-label small fw-600 text-muted opacity-50 text-uppercase mb-3">{{ __('Price Range') }}</label>
                        <div class="d-flex gap-2 align-items-center">
                            <input type="number" name="min_price" class="form-control bg-dark border-secondary border-opacity-10 py-2 px-3" placeholder="{{ __('Min') }}" value="{{ request('min_price') }}">
                            <span class="text-muted">-</span>
                            <input type="number" name="max_price" class="form-control bg-dark border-secondary border-opacity-10 py-2 px-3" placeholder="{{ __('Max') }}" value="{{ request('max_price') }}">
                        </div>
                    </div>

                    <!-- Sort -->
                    <div class="mb-4">
                        <label class="form-label small fw-600 text-muted opacity-50 text-uppercase mb-3">{{ __('Sort By') }}</label>
                        <select name="sort" class="form-select bg-dark border-secondary border-opacity-10 py-2" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest Arrival') }}</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                            <option value="top_rated" {{ request('sort') == 'top_rated' ? 'selected' : '' }}>{{ __('Top Rated First') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-outline-primary w-100 rounded-pill mt-2">{{ __('Apply Filters') }}</button>
                    <a href="{{ route('meals.index') }}" class="btn btn-link w-100 text-muted small mt-2">{{ __('Clear All') }}</a>
                </form>
            </div>
            
            <!-- Quick Category Badges (Optional/Visual) -->
            <div class="glass-panel p-4 d-none d-lg-block">
                <h6 class="fw-700 mb-3 text-light">{{ __('Quick Tags') }}</h6>
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
                <p class="text-muted mb-0">{{ __('Showing :count meals', ['count' => $meals->total()]) }}</p>
            </div>

            <div class="row g-4">
                @forelse($meals as $meal)
                    <div class="col-md-6 col-xl-4 mb-4">
                        <x-ui.meal-card :meal="$meal" />
                    </div>
                @empty
                    <div class="col-12 py-5">
                        <x-ui.empty-state
                            icon="fas fa-search-minus"
                            :title="__('No meals found!')"
                            :description="__('Try adjusting your filters or search terms.')"
                            :actionText="__('Reset All Filters')"
                            :actionUrl="route('meals.index')" />
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
@endsection
