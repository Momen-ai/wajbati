@extends('layouts.frontend')

@push('background')
    <!-- Fixed Background Canvas & Scrim for Whole Home Page (Natural Scroll, No Pinning) -->
    <canvas id="hero-canvas" class="hero-canvas-fixed"></canvas>
    <div id="hero-scrim" class="hero-scrim-fixed"></div>
@endpush

@section('content')
    @php
        $activeCategories = $categories->where('meals_count', '>', 0);
    @endphp

    <!-- MOBILE VIEW (<768px): Matching Reference Screen 1 -->
    <div class="d-md-none px-3 pt-3 pb-4">
        <!-- Large Greeting & Subtitle (Ref 1) -->
        <div class="mb-3">
            <h1 class="fw-bolder text-light mb-1" style="font-size: 28px; letter-spacing: -0.02em;">
                {{ __('أهلاً بيك') }}
            </h1>
            <p class="text-secondary mb-0" style="font-size: 14px;">
                {{ __('شو بدك تاكل اليوم؟') }}
            </p>
        </div>

        <!-- Search Bar with Soft Orange Filter Button (Ref 1) -->
        <div class="d-flex align-items-center gap-2 mb-4">
            <button type="button"
                    class="btn d-flex align-items-center justify-content-center rounded-4 border-0 flex-shrink-0"
                    style="width: 48px; height: 48px; background: rgba(255, 122, 24, 0.16); color: #ff7a18;"
                    data-bs-toggle="offcanvas" data-bs-target="#mobileFilterSheet"
                    aria-label="{{ __('Filter meals') }}">
                <i class="fas fa-sliders-h" style="font-size: 17px;"></i>
            </button>

            <form action="{{ route('meals.index') }}" method="GET" class="flex-grow-1 m-0">
                <div class="position-relative d-flex align-items-center">
                    <input type="text"
                           name="search"
                           class="form-control rounded-4 text-light border-0 py-2.5 px-4 shadow-none"
                           style="background: #1c1c1c; font-size: 15px;"
                           placeholder="{{ __('دوّر على وجبة أو مطبخ') }}"
                           aria-label="{{ __('Search meals') }}">
                    <button type="submit" class="btn position-absolute end-0 top-50 translate-middle-y text-secondary border-0 p-0 pe-3" aria-label="{{ __('Search') }}">
                        <i class="fas fa-search" style="font-size: 15px;"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Categories Section (Category Circles Snap Row) (Ref 1) -->
        @if($activeCategories->isNotEmpty())
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="fw-bold text-light mb-0" style="font-size: 17px;">{{ __('التصنيفات') }}</h2>
                    <a href="{{ route('meals.index') }}" class="text-decoration-none fw-bold small" style="color: #ff7a18; font-size: 12px;">
                        {{ __('عرض الكل') }}
                    </a>
                </div>

                <div class="d-flex gap-3 overflow-x-auto pb-2 scroll-snap-x no-scrollbar" style="scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;">
                    @foreach ($activeCategories as $category)
                        <x-ui.category-circle :category="$category" />
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Popular Meals Section: 2-Column Mobile Grid (Ref 1) -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold text-light mb-0" style="font-size: 17px;">{{ __('الأكثر طلباً') }}</h2>
                <a href="{{ route('meals.index') }}" class="text-decoration-none fw-bold small" style="color: #ff7a18; font-size: 12px;">
                    {{ __('عرض الكل') }}
                </a>
            </div>

            <div class="row g-2">
                @foreach ($popularMeals as $meal)
                    <div class="col-6">
                        <x-ui.meal-card :meal="$meal" />
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Featured Chefs Section: Compact Mobile Preview -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold text-light mb-0" style="font-size: 17px;">{{ __('طهاة متميزون') }}</h2>
                <a href="{{ route('chefs.index') }}" class="text-decoration-none fw-bold small" style="color: #ff7a18; font-size: 12px;">
                    {{ __('عرض الكل') }}
                </a>
            </div>

            <div class="d-flex flex-column gap-2">
                @foreach ($chefs->take(3) as $chef)
                    <a href="{{ route('chefs.show', $chef->id) }}"
                       class="text-decoration-none d-flex align-items-center justify-content-between p-3 rounded-4"
                       style="background: #151515; border: 1px solid rgba(255, 255, 255, 0.08);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center text-primary"
                                 style="width: 44px; height: 44px; background: #1c1c1c; border: 1px solid rgba(255, 255, 255, 0.08);">
                                @if($chef->image)
                                    <img src="{{ asset('storage/' . $chef->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $chef->name }}">
                                @else
                                    <i class="fas fa-hat-chef"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold text-light mb-0" style="font-size: 14px;">{{ $chef->kitchen_name ?? $chef->name }}</h6>
                                <small class="text-secondary" style="font-size: 11px;">
                                    <i class="fas fa-star text-warning me-1"></i>5.0 • {{ $chef->meals_count ?? $chef->meals->count() }} {{ __('وجبات') }}
                                </small>
                            </div>
                        </div>
                        <i class="fas fa-chevron-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} text-secondary" style="font-size: 12px;"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Desktop View (>=768px): Unchanged -->
    <div class="d-none d-md-block">
    <!-- Hero Section (Natural flow ~100vh, content scrolls away smoothly) -->
    <div id="hero-section" class="hero-section mb-5" data-base-path="{{ asset('assets/hero/') }}/" data-autoplay="false">
        <div class="container hero-content-wrapper min-vh-100 d-flex flex-column justify-content-center align-items-center text-center py-5">
            <div class="hero-copy-panel px-3 py-4 text-center">
                <!-- Solid Pill Badge -->
                <div class="mb-3">
                    <span class="hero-solid-pill badge px-3 py-2 rounded-pill fw-bold">
                        <i class="fas fa-star me-1 text-warning"></i> {{ __('#1 Food & Kitchens Platform') }}
                    </span>
                </div>

                <!-- 3 Crossfading Beats in Place (Synchronized with 1.5vh Scroll or Free Interaction) -->
                <div class="hero-beats-stage mb-4">
                    <!-- Beat 1: frames 1-12 -->
                    <div id="hero-beat-1" class="hero-copy-beat active">
                        <h1 class="hero-heading-display text-gradient-primary mb-2">
                            {{ __('Authentic handcrafted food') }}
                        </h1>
                        <p class="hero-sub-display mb-0">
                            {{ __('Order healthy, fresh, and authentic food from independent kitchens and chefs.') }}
                        </p>
                    </div>

                    <!-- Beat 2: frames 13-28 -->
                    <div id="hero-beat-2" class="hero-copy-beat" style="opacity: 0; pointer-events: none;">
                        <h2 class="hero-heading-display mb-2">
                            {{ __("From a chef's kitchen near you") }}
                        </h2>
                        <p class="hero-sub-display mb-0">
                            {{ __('Prepared with passion and fresh local ingredients every single day.') }}
                        </p>
                    </div>

                    <!-- Beat 3: frames 29-49 -->
                    <div id="hero-beat-3" class="hero-copy-beat" style="opacity: 0; pointer-events: none;">
                        <h2 class="hero-heading-display mb-2">
                            {{ __('Every bite made by someone you can trust') }}
                        </h2>
                        <p class="hero-sub-display mb-0">
                            {{ __('Direct from passionate chefs to your table with culinary excellence and authenticity.') }}
                        </p>
                    </div>
                </div>

                <!-- Interactive Search & Action Panel -->
                <div class="hero-action-panel mt-3">
                    <!-- Search Form -->
                    <form action="{{ route('meals.index') }}" method="GET" class="mb-3">
                        <div class="hero-search-bar input-group shadow-lg">
                            <span class="input-group-text bg-transparent border-0 text-secondary ps-3">
                                <i class="fas fa-search text-primary"></i>
                            </span>
                            <input type="text" name="search" class="form-control bg-transparent border-0 text-light shadow-none"
                                placeholder="{{ __('Search for meals, kitchens...') }}" aria-label="{{ __('Search meals') }}">
                            <button class="btn btn-primary rounded-pill px-4 fw-bold" type="submit" data-magnetic>{{ __('Search') }}</button>
                        </div>
                    </form>

                    <!-- CTAs -->
                    <div class="d-flex gap-3 justify-content-center align-items-center flex-wrap">
                        <a href="{{ route('meals.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 fw-bold shadow-sm" data-magnetic>
                            <i class="fas fa-utensils me-2"></i> {{ __('Browse Meals') }}
                        </a>
                        <a href="{{ route('register', ['role' => 'chef']) }}" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-semibold border-secondary border-opacity-30" data-magnetic>
                            {{ __('Join as a Chef') }} <i class="fas fa-arrow-right ms-2 small"></i>
                        </a>
                    </div>
                </div>

                <!-- Subtle Scroll Hint -->
                <div class="hero-scroll-hint mt-4 pt-1 text-secondary small">
                    <span class="d-inline-flex align-items-center gap-2 opacity-75">
                        <i class="fas fa-chevron-down animate-bounce"></i>
                        <span>{{ __('Scroll to explore our kitchens') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section (Enhanced Contrast & 160px auto-fit Grid) -->
    @php
        $activeCategories = $categories->where('meals_count', '>', 0);
    @endphp
    @if($activeCategories->isNotEmpty())
        <div class="container mb-5">
            <div class="section-heading-panel p-3 px-4 rounded-4 mb-4 border border-subtle d-flex justify-content-between align-items-center flex-wrap gap-2"
                 style="background: rgba(18, 18, 18, 0.82); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);">
                <div>
                    <h2 class="h3 fw-bold text-main mb-1">{{ __('Explore Categories') }}</h2>
                    <p class="text-secondary small mb-0">{{ __('Browse by dish categories and signature specialties') }}</p>
                </div>
            </div>

            <div class="categories-auto-grid">
                @foreach ($activeCategories as $category)
                    <x-ui.category-card :category="$category" />
                @endforeach
            </div>
        </div>
    @endif

    <!-- Popular Meals Section (Enhanced Contrast & Balanced Grid) -->
    <div class="container mb-5">
        <div class="section-heading-panel p-3 px-4 rounded-4 mb-4 border border-subtle d-flex justify-content-between align-items-center flex-wrap gap-3"
             style="background: rgba(18, 18, 18, 0.82); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);">
            <div>
                <h2 class="h3 fw-bold text-main mb-1">{{ __('Popular Meals') }}</h2>
                <p class="text-secondary small mb-0">{{ __('Our customers\' favorites this week') }}</p>
            </div>
            <a href="{{ route('meals.index') }}" class="btn btn-link text-primary text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 p-0">
                <span>{{ __('View All') }}</span>
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} small"></i>
            </a>
        </div>

        <div class="row g-3 g-md-4" data-reveal-stagger>
            @foreach ($popularMeals as $meal)
                <div class="col-12 col-sm-6 col-lg-4">
                    <x-ui.meal-card :meal="$meal" />
                </div>
            @endforeach

            <!-- Promo card to prevent orphan if popular meals count is not a multiple of 3 -->
            @if($popularMeals->count() % 3 !== 0)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="spotlight-card rounded-4 p-4 h-100 d-flex flex-column justify-content-between border border-subtle text-center" style="background: linear-gradient(135deg, var(--surface-1), rgba(255, 122, 24, 0.08));" data-spotlight>
                        <div>
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 bg-surface-2 text-primary shadow-sm" style="width: 58px; height: 58px;">
                                <i class="fas fa-utensils fa-lg"></i>
                            </div>
                            <h5 class="fw-bold text-main mb-2">{{ __("Discover More Dishes") }}</h5>
                            <p class="text-secondary small mb-0">{{ __('Explore dozens of authentic recipes prepared fresh daily by passionate independent chefs.') }}</p>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('meals.index') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold shadow-sm" data-magnetic>
                                {{ __('Browse All Meals') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Featured Chefs Section (Enhanced Contrast & Auto-fit Grid) -->
    <div class="container mb-5 pb-5">
        <div class="section-heading-panel p-3 px-4 rounded-4 mb-4 border border-subtle d-flex justify-content-between align-items-center flex-wrap gap-3"
             style="background: rgba(18, 18, 18, 0.82); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);">
            <div>
                <h2 class="h3 fw-bold text-main mb-1">{{ __('Meet Our Talented Chefs') }}</h2>
                <p class="text-secondary small mb-0">{{ __('Local families sharing their culinary heritage') }}</p>
            </div>
            <a href="{{ route('chefs.index') }}" class="btn btn-link text-primary text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 p-0">
                <span>{{ __('View All') }}</span>
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} small"></i>
            </a>
        </div>

        <div class="chefs-auto-grid" data-reveal-stagger>
            @foreach ($chefs as $chef)
                <x-ui.chef-card :chef="$chef" />
            @endforeach
        </div>
    </div>
    </div> <!-- /d-none d-md-block -->

    <!-- Mobile Filter Bottom Sheet (Ref Screen 3) -->
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
                        <span class="fw-bold" style="color: #ff7a18; font-size: 16px;" id="priceValDisplayHome">₪{{ request('max_price', 60) }}</span>
                    </div>
                    <input type="range" name="max_price" class="form-range custom-orange-range" min="10" max="100" step="5"
                           value="{{ request('max_price', 60) }}" id="maxPriceSliderHome"
                           oninput="document.getElementById('priceValDisplayHome').innerText = '₪' + this.value">
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
                        <input class="form-check-input ms-0 custom-switch" type="checkbox" role="switch" id="verifiedSwitchHome" style="width: 48px; height: 26px;">
                    </div>
                </div>

                <!-- Sticky Primary Button (Ref 3) -->
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bolder shadow-sm" style="font-size: 16px; color: #1b0e03 !important;">
                    {{ __('عرض 7 وجبة') }}
                </button>
            </form>
        </div>
    </div>
@endsection
