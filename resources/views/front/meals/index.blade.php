@extends('layouts.frontend')

@section('content')
    <!-- MOBILE VIEW HEADER (<768px): Matching Reference Screen 2 -->
    <div class="d-md-none px-3 pt-3 pb-2">
        <!-- Title and Count Badge (Ref 2) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="fw-bolder text-light mb-0" style="font-size: 28px; letter-spacing: -0.02em;">
                {{ __('الوجبات') }}
            </h1>
            <span class="text-secondary fw-semibold" style="font-size: 14px;">
                {{ $meals->total() }} {{ __('وجبة') }}
            </span>
        </div>

        <!-- Mobile Search Bar (Ref 2) -->
        <form action="{{ route('meals.index') }}" method="GET" class="mb-3">
            <div class="position-relative d-flex align-items-center">
                <input type="text"
                       name="search"
                       class="form-control rounded-4 text-light border-0 py-2.5 px-4 shadow-none"
                       style="background: #1c1c1c; font-size: 15px;"
                       placeholder="{{ __('ابحث عن وجبة أو مطبخ') }}"
                       value="{{ request('search') }}"
                       aria-label="{{ __('Search meals') }}">
                <button type="submit" class="btn position-absolute end-0 top-50 translate-middle-y text-secondary border-0 p-0 pe-3" aria-label="{{ __('Search') }}">
                    <i class="fas fa-search" style="font-size: 15px;"></i>
                </button>
            </div>
        </form>

        <!-- Horizontal Chips Row: Filters Button First, then All/Categories (Ref 2) -->
        <div class="d-flex gap-2 overflow-x-auto pb-2 scroll-snap-x no-scrollbar" style="scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;">
            <!-- Filters Button (First chip) -->
            <button type="button"
                    class="btn rounded-pill px-3 py-1.5 flex-shrink-0 d-inline-flex align-items-center gap-1.5 border-0"
                    style="background: #1c1c1c; color: #ff7a18; border: 1px solid rgba(255, 122, 24, 0.3) !important; font-size: 13px; font-weight: 700;"
                    data-bs-toggle="offcanvas" data-bs-target="#mobileFilterSheet">
                <i class="fas fa-sliders-h small"></i>
                <span>{{ __('الفلاتر') }}</span>
                @if(request('category') || request('min_price') || request('max_price') || (request('sort') && request('sort') !== 'newest'))
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 6px; height: 6px; background: #ff7a18;"></span>
                @endif
            </button>

            <!-- "All" Chip -->
            <a href="{{ route('meals.index') }}"
               class="btn rounded-pill px-3.5 py-1.5 flex-shrink-0 text-decoration-none border-0"
               style="font-size: 13px; font-weight: 700; {{ !request('category') ? 'background: #ff7a18; color: #1b0e03 !important;' : 'background: #1c1c1c; color: #f2f2f2; border: 1px solid rgba(255,255,255,0.08) !important;' }}">
                {{ __('الكل') }}
            </a>

            <!-- Category Chips -->
            @foreach($categories as $cat)
                <a href="{{ route('meals.index', array_merge(request()->query(), ['category' => $cat->id])) }}"
                   class="btn rounded-pill px-3.5 py-1.5 flex-shrink-0 text-decoration-none border-0"
                   style="font-size: 13px; font-weight: 600; {{ request('category') == $cat->id ? 'background: #ff7a18; color: #1b0e03 !important;' : 'background: #1c1c1c; color: #f2f2f2; border: 1px solid rgba(255,255,255,0.08) !important;' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- 2-Column Mobile Grid for Meals (Ref 2) -->
        <div class="row g-2 mt-2">
            @forelse($meals as $meal)
                <div class="col-6">
                    <x-ui.meal-card :meal="$meal" />
                </div>
            @empty
                <div class="col-12 py-5">
                    <x-ui.empty-state
                        icon="fa-utensils"
                        :title="__('No meals found')"
                        :message="__('Try clearing filters or search with another keyword.')"
                        :actionUrl="route('meals.index')"
                        :actionText="__('Clear Filters')" />
                </div>
            @endforelse
        </div>

        @if($meals->hasPages())
            <div class="mt-4 mb-3 d-flex justify-content-center">
                {{ $meals->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- DESKTOP VIEW (>=768px): Strictly Unchanged -->
    <div class="d-none d-md-block">
    <!-- Inner Page Banner (Frame f_001) -->
    <x-ui.page-banner
        :image="asset('assets/hero/f_001.webp')"
        :badge="__('Fresh & Authentic')"
        :title="__('Wajbati Explorer')"
        :subtitle="__('Discover delicious handcrafted meals in your neighborhood, cooked fresh with passion and mastery.')">
        <x-slot name="stats">
            <span class="badge rounded-pill bg-surface-2 text-main border border-subtle px-3 py-1.5 fw-semibold small">
                <i class="fas fa-utensils text-primary me-1"></i> {{ $meals->total() }} {{ __('Meals') }}
            </span>
            <span class="badge rounded-pill bg-surface-2 text-main border border-subtle px-3 py-1.5 fw-semibold small">
                <i class="fas fa-fire text-warning me-1"></i> {{ __('Freshly Made Today') }}
            </span>
        </x-slot>

        <x-slot name="actions">
            <form action="{{ route('meals.index') }}" method="GET" class="search-box p-2 shadow-sm d-flex gap-2 rounded-pill" style="background: var(--surface-1); border: 1px solid var(--border-medium);">
                <input type="text" name="search" class="form-control border-0 bg-transparent text-main flex-grow-1 px-4 shadow-none" placeholder="{{ __('Search for meals, kitchens...') }}" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary rounded-pill px-4 text-nowrap fw-semibold">
                    <i class="fas fa-search me-1"></i> {{ __('Find') }}
                </button>
            </form>
        </x-slot>
    </x-ui.page-banner>

    <div class="container py-5 ambient-glow-page">
        <div class="row g-4">
            <!-- Desktop Sidebar Filters (>=992px, Sticky) -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sticky-filter-sidebar" style="position: sticky; top: 88px;">
                    <div class="spotlight-card rounded-4 p-4 mb-4 border border-subtle" data-spotlight>
                        <form action="{{ route('meals.index') }}" method="GET" id="filterForm">
                            <h6 class="fw-bold mb-4 pb-2 border-bottom border-subtle text-main d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-sliders-h me-2 text-primary"></i>{{ __('Filters') }}</span>
                                @if(request('category') || request('min_price') || request('max_price') || request('sort'))
                                    <a href="{{ route('meals.index') }}" class="text-secondary small text-decoration-none hover-text-primary">{{ __('Reset') }}</a>
                                @endif
                            </h6>
                            
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif

                            <!-- Category Filter -->
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-secondary text-uppercase mb-2">{{ __('Category') }}</label>
                                <select name="category" class="form-select bg-surface-2 text-main border-subtle py-2" onchange="this.form.submit()">
                                    <option value="">{{ __('All Categories') }}</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price Filter -->
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-secondary text-uppercase mb-2">{{ __('Price Range') }}</label>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="number" name="min_price" class="form-control bg-surface-2 text-main border-subtle py-2 px-3" placeholder="{{ __('Min') }}" value="{{ request('min_price') }}">
                                    <span class="text-secondary">-</span>
                                    <input type="number" name="max_price" class="form-control bg-surface-2 text-main border-subtle py-2 px-3" placeholder="{{ __('Max') }}" value="{{ request('max_price') }}">
                                </div>
                            </div>

                            <!-- Sort -->
                            <div class="mb-4">
                                <label class="form-label small fw-semibold text-secondary text-uppercase mb-2">{{ __('Sort By') }}</label>
                                <select name="sort" class="form-select bg-surface-2 text-main border-subtle py-2" onchange="this.form.submit()">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>{{ __('Newest Arrival') }}</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>{{ __('Price: Low to High') }}</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>{{ __('Price: High to Low') }}</option>
                                    <option value="top_rated" {{ request('sort') == 'top_rated' ? 'selected' : '' }}>{{ __('Top Rated First') }}</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-outline-primary w-100 rounded-pill mt-2 fw-semibold">{{ __('Apply Filters') }}</button>
                            <a href="{{ route('meals.index') }}" class="btn btn-link w-100 text-secondary small mt-2 text-decoration-none">{{ __('Clear All') }}</a>
                        </form>
                    </div>
                    
                    <!-- Quick Category Badges -->
                    <div class="spotlight-card rounded-4 p-4 border border-subtle" data-spotlight>
                        <h6 class="fw-bold mb-3 text-main">{{ __('Quick Tags') }}</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($categories->take(8) as $cat)
                                <a href="{{ route('meals.index', ['category' => $cat->id]) }}" class="badge rounded-pill bg-surface-2 text-secondary border border-subtle text-decoration-none px-3 py-2 hover-border-primary">{{ $cat->name }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meals Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="text-secondary mb-0">
                        {{ __('Showing :count meals', ['count' => $meals->total()]) }}
                    </p>
                </div>

                <div class="row g-4" data-reveal-stagger>
                    @forelse($meals as $meal)
                        <div class="col-12 col-sm-6 col-xl-4 mb-4">
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

                    <!-- Promo Card in Grid to Prevent Trailing Orphan Gaps -->
                    @if($meals->isNotEmpty())
                        <div class="col-12 col-sm-6 col-xl-4 mb-4">
                            <div class="spotlight-card rounded-4 p-4 h-100 d-flex flex-column justify-content-between border border-subtle text-center" style="background: linear-gradient(135deg, var(--surface-1), rgba(255, 122, 24, 0.08));" data-spotlight>
                                <div>
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 bg-surface-2 text-primary shadow-sm" style="width: 58px; height: 58px;">
                                        <i class="fas fa-kitchen-set fa-lg"></i>
                                    </div>
                                    <h5 class="fw-bold text-main mb-2">{{ __("Can't find your dish?") }}</h5>
                                    <p class="text-secondary small mb-0">{{ __('Explore custom catering menus and request special signature dishes directly from our chefs.') }}</p>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('chefs.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-semibold" data-magnetic>
                                        {{ __('Browse Chefs') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($meals->hasPages())
                    <div class="mt-5 pt-4 d-flex justify-content-center">
                        {{ $meals->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div> <!-- /d-none d-md-block -->

    <!-- Mobile Filter Bottom Sheet Offcanvas (<768px): Matching Reference Screen 3 Exactly -->
    <div class="offcanvas offcanvas-bottom mobile-bottom-sheet d-md-none" tabindex="-1" id="mobileFilterSheet" aria-labelledby="mobileFilterLabel">
        <div class="mobile-drag-pill"></div>
        <div class="offcanvas-header pt-0 px-4 pb-2 border-0 d-flex justify-content-between align-items-center">
            <button type="button" class="btn p-0 rounded-circle d-flex align-items-center justify-content-center text-secondary border-0"
                    data-bs-dismiss="offcanvas" aria-label="Close" style="width: 36px; height: 36px; background: #1c1c1c;">
                <i class="fas fa-times" style="font-size: 14px;"></i>
            </button>
            <h5 class="offcanvas-title fw-bolder text-light mb-0" id="mobileFilterLabel" style="font-size: 20px;">
                {{ __('الفلاتر') }}
            </h5>
            <a href="{{ route('meals.index') }}" class="text-decoration-none fw-bold" style="color: #ff7a18; font-size: 14px;">
                {{ __('مسح الكل') }}
            </a>
        </div>
        <div class="offcanvas-body p-4 pt-2">
            <form action="{{ route('meals.index') }}" method="GET">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                <!-- Categories Multi-select Chips (Ref 3) -->
                <div class="mb-4">
                    <label class="form-label text-light fw-bold mb-3" style="font-size: 14px;">{{ __('التصنيف') }}</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($categories as $cat)
                            <label class="btn rounded-pill px-3 py-1.5 border-0 d-inline-flex align-items-center gap-1.5 cursor-pointer"
                                   style="font-size: 13px; font-weight: 600; {{ request('category') == $cat->id ? 'background: #ff7a18; color: #1b0e03 !important;' : 'background: #1c1c1c; color: #f2f2f2; border: 1px solid rgba(255,255,255,0.08) !important;' }}">
                                <input type="radio" name="category" value="{{ $cat->id }}" class="d-none" {{ request('category') == $cat->id ? 'checked' : '' }} onchange="this.form.submit()">
                                <span>{{ $cat->name }}</span>
                                @if(request('category') == $cat->id)
                                    <i class="fas fa-check small"></i>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range Slider (Ref 3) -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label text-light fw-bold mb-0" style="font-size: 14px;">{{ __('السعر الأقصى') }}</label>
                        <span class="fw-bold" style="color: #ff7a18; font-size: 16px;" id="priceValDisplayMeals">₪{{ request('max_price', 60) }}</span>
                    </div>
                    <input type="range" name="max_price" class="form-range custom-orange-range" min="10" max="100" step="5"
                           value="{{ request('max_price', 60) }}" id="maxPriceSliderMeals"
                           oninput="document.getElementById('priceValDisplayMeals').innerText = '₪' + this.value">
                    <div class="d-flex justify-content-between text-secondary" style="font-size: 11px;">
                        <span>₪10</span>
                        <span>₪100</span>
                    </div>
                </div>

                <!-- Sort Segmented Control (Ref 3) -->
                <div class="mb-4">
                    <label class="form-label text-light fw-bold mb-3" style="font-size: 14px;">{{ __('الترتيب') }}</label>
                    <div class="d-flex p-1 rounded-4" style="background: #1c1c1c; border: 1px solid rgba(255, 255, 255, 0.08);">
                        <label class="flex-grow-1 text-center py-2 rounded-3 cursor-pointer mb-0 {{ request('sort', 'newest') == 'newest' ? 'bg-primary text-dark fw-bold' : 'text-secondary' }}" style="font-size: 12px; color: {{ request('sort', 'newest') == 'newest' ? '#1b0e03 !important;' : '' }}">
                            <input type="radio" name="sort" value="newest" class="d-none" {{ request('sort', 'newest') == 'newest' ? 'checked' : '' }}>
                            {{ __('الأحدث') }}
                        </label>
                        <label class="flex-grow-1 text-center py-2 rounded-3 cursor-pointer mb-0 {{ request('sort') == 'top_rated' ? 'bg-primary text-dark fw-bold' : 'text-secondary' }}" style="font-size: 12px; color: {{ request('sort') == 'top_rated' ? '#1b0e03 !important;' : '' }}">
                            <input type="radio" name="sort" value="top_rated" class="d-none" {{ request('sort') == 'top_rated' ? 'checked' : '' }}>
                            {{ __('الأعلى تقييماً') }}
                        </label>
                        <label class="flex-grow-1 text-center py-2 rounded-3 cursor-pointer mb-0 {{ request('sort') == 'price_low' ? 'bg-primary text-dark fw-bold' : 'text-secondary' }}" style="font-size: 12px; color: {{ request('sort') == 'price_low' ? '#1b0e03 !important;' : '' }}">
                            <input type="radio" name="sort" value="price_low" class="d-none" {{ request('sort') == 'price_low' ? 'checked' : '' }}>
                            {{ __('الأقل سعراً') }}
                        </label>
                    </div>
                </div>

                <!-- Verified Kitchens Switch (Ref 3) -->
                <div class="d-flex justify-content-between align-items-center p-3 rounded-4 mb-4" style="background: #1c1c1c; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div>
                        <h6 class="fw-bold text-light mb-0" style="font-size: 14px;">{{ __('مطابخ موثقة فقط') }}</h6>
                        <small class="text-secondary" style="font-size: 11px;">{{ __('شيفات تم التحقق من مطابخهم') }}</small>
                    </div>
                    <div class="form-check form-switch m-0 p-0">
                        <input class="form-check-input ms-0 custom-switch" type="checkbox" role="switch" id="verifiedSwitchMeals" style="width: 48px; height: 26px;">
                    </div>
                </div>

                <!-- Sticky Primary Button (Ref 3) -->
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bolder shadow-sm" style="font-size: 16px; color: #1b0e03 !important;">
                    {{ __('عرض :count وجبة', ['count' => $meals->total()]) }}
                </button>
            </form>
        </div>
    </div>
@endsection
