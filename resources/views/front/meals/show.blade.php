@extends('layouts.frontend')

@section('content')
    {{-- ================= MOBILE VIEW (<768px, Reference Screen 4) ================= --}}
    <div class="d-md-none has-bottom-bar" style="background: #0a0a0a; min-height: 100vh;">
        <!-- Top Media Hero (~340px) with Back, Fav & Share floating buttons -->
        <div class="position-relative w-100" style="height: 340px; background: var(--surface-1);">
            @php $meal->loadMissing('image'); @endphp
            @if ($meal->relationLoaded('image') && $meal->image)
                <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $meal->name }}">
            @else
                <img src="{{ asset('assets/images/placeholder.svg') }}" class="w-100 h-100 object-fit-cover" alt="{{ $meal->name }}">
            @endif

            <!-- Bottom Gradient scrim fading into content -->
            <div class="position-absolute inset-0 pe-none"
                 style="top:0; left:0; right:0; bottom:0; background: linear-gradient(180deg, rgba(10,10,10,0.55) 0%, rgba(10,10,10,0) 40%, rgba(10,10,10,0.85) 85%, #0a0a0a 100%);"></div>

            <!-- Floating Top Action Bar -->
            <div class="position-absolute top-0 start-0 end-0 p-3 d-flex align-items-center justify-content-between" style="z-index: 10;">
                <a href="{{ url()->previous() != url()->current() ? url()->previous() : route('meals.index') }}"
                   class="btn d-inline-flex align-items-center justify-content-center rounded-circle border border-secondary border-opacity-25"
                   style="width: 42px; height: 42px; background: rgba(21, 21, 21, 0.75); backdrop-filter: blur(8px); color: #f2f2f2;">
                    <i class="fas fa-chevron-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
                </a>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn d-inline-flex align-items-center justify-content-center rounded-circle border border-secondary border-opacity-25"
                            style="width: 42px; height: 42px; background: rgba(21, 21, 21, 0.75); backdrop-filter: blur(8px); color: #f2f2f2;"
                            onclick="navigator.share ? navigator.share({title: '{{ $meal->name }}', url: window.location.href}) : null">
                        <i class="fas fa-share-alt"></i>
                    </button>
                    <button type="button" class="btn d-inline-flex align-items-center justify-content-center rounded-circle border border-secondary border-opacity-25"
                            style="width: 42px; height: 42px; background: rgba(21, 21, 21, 0.75); backdrop-filter: blur(8px); color: #ff7a18;">
                        <i class="far fa-heart"></i>
                    </button>
                </div>
            </div>

            @if(! $meal->is_available)
                <div class="position-absolute bottom-0 start-0 m-3" style="z-index: 10;">
                    <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold shadow-sm">
                        <i class="fas fa-ban me-1"></i> {{ __('Currently Unavailable') }}
                    </span>
                </div>
            @endif
        </div>

        <!-- Overlapping Details Card -->
        <div class="position-relative px-3 pt-2 pb-5" style="z-index: 5; margin-top: -24px;">
            <div class="p-4 rounded-4 border border-secondary border-opacity-10 shadow-lg mb-3" style="background: var(--surface-1);">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <h1 class="h3 fw-bold text-light mb-0" style="letter-spacing: -0.02em;">{{ $meal->name }}</h1>
                    <div class="text-end flex-shrink-0">
                        <span class="h4 fw-bold text-primary mb-0 d-block">{{ money($meal->price) }}</span>
                    </div>
                </div>

                <!-- Chips Row -->
                <div class="d-flex align-items-center flex-wrap gap-2 my-3">
                    <span class="badge px-3 py-2 rounded-pill border border-secondary border-opacity-10 d-inline-flex align-items-center gap-1"
                          style="background: var(--surface-2); color: var(--text-main); font-size: 0.78rem;">
                        <i class="fas fa-utensils text-primary"></i> {{ $meal->category->name ?? __('General') }}
                    </span>
                    @if($meal->prep_time_minutes)
                        <span class="badge px-3 py-2 rounded-pill border border-secondary border-opacity-10 d-inline-flex align-items-center gap-1"
                              style="background: var(--surface-2); color: var(--text-main); font-size: 0.78rem;">
                            <i class="fas fa-clock text-primary"></i> {{ $meal->prep_time_minutes }} {{ __('min') }}
                        </span>
                    @endif
                    @if($meal->number_persons)
                        <span class="badge px-3 py-2 rounded-pill border border-secondary border-opacity-10 d-inline-flex align-items-center gap-1"
                              style="background: var(--surface-2); color: var(--text-main); font-size: 0.78rem;">
                            <i class="fas fa-users text-primary"></i> {{ $meal->number_persons }} {{ __('persons') }}
                        </span>
                    @endif
                    <span class="badge px-3 py-2 rounded-pill border border-secondary border-opacity-10 d-inline-flex align-items-center gap-1"
                          style="background: var(--surface-2); color: #f5b045; font-size: 0.78rem;">
                        <i class="fas fa-star"></i> {{ number_format($meal->ratings->avg('star') ?? 5, 1) }} ({{ $meal->ratings->count() }})
                    </span>
                </div>

                <!-- Chef Row Card -->
                <div class="p-3 rounded-4 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between mt-3"
                     style="background: var(--surface-2);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary border border-primary border-opacity-25"
                             style="width: 44px; height: 44px; background: var(--surface-1);">
                            <i class="fas fa-hat-chef"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block" style="font-size: 0.7rem;">{{ __('Prepared by') }}</small>
                            <h6 class="mb-0 fw-bold text-light" style="font-size: 0.92rem;">
                                {{ $meal->chef->kitchen_name ?? $meal->chef->name ?? __('Chef / Culinary Partner') }}
                            </h6>
                        </div>
                    </div>
                    @if(isset($meal->chef))
                        <a href="{{ route('chefs.show', $meal->chef->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.78rem;">
                            {{ __('View') }}
                        </a>
                    @endif
                </div>

                <!-- Description -->
                <div class="mt-4">
                    <h6 class="text-secondary text-uppercase fw-semibold mb-2" style="font-size: 0.76rem; letter-spacing: 0.05em;">
                        {{ __('Description') }}
                    </h6>
                    <p class="text-light opacity-90 lh-base mb-0" style="font-size: 0.9rem;">{{ $meal->description }}</p>
                </div>
            </div>

            <!-- Reviews Accordion / Preview -->
            <div class="p-4 rounded-4 border border-secondary border-opacity-10 mb-4" style="background: var(--surface-1);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-light mb-0" style="font-size: 1rem;">
                        {{ __('Reviews') }} ({{ $meal->ratings->count() }})
                    </h5>
                    <div class="text-warning small">
                        <i class="fas fa-star"></i> {{ number_format($meal->ratings->avg('star') ?? 5, 1) }}
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    @forelse($meal->ratings->take(3) as $rating)
                        <div class="p-2 rounded-3 border border-secondary border-opacity-10" style="background: var(--surface-2);">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-light small">{{ $rating->user->name ?? __('User') }}</span>
                                <div class="text-warning small" style="font-size: 0.7rem;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $rating->star ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($rating->body)
                                <p class="text-secondary mb-0 small" style="font-size: 0.8rem;">{{ Str::limit($rating->body, 120) }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-secondary mb-0 small">{{ __('No reviews yet. Be the first to review!') }}</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sticky Bottom Action Bar (Reference Screen 4) -->
        <div class="mobile-sticky-action-bar">
            <form action="{{ route('cart.add') }}" method="POST" class="w-100 d-flex align-items-center justify-content-between gap-3">
                @csrf
                <input type="hidden" name="meal_id" value="{{ $meal->id }}">
                <input type="hidden" name="quantity" id="mobileMealQtyInput" value="1">

                <!-- Compact Stepper -->
                <div class="d-flex align-items-center rounded-pill p-1 border border-secondary border-opacity-25"
                     style="background: var(--surface-2); min-width: 110px;">
                    <button type="button" class="btn p-0 text-light d-flex align-items-center justify-content-center"
                            style="width: 34px; height: 34px;"
                            onclick="let q = document.getElementById('mobileMealQtyInput'); let d = document.getElementById('mobileMealQtyDisplay'); if(parseInt(q.value) > 1){ q.value = parseInt(q.value) - 1; d.innerText = q.value; updateMobileBtnPrice(); }">
                        <i class="fas fa-minus small"></i>
                    </button>
                    <span class="flex-grow-1 text-center fw-bold text-light" id="mobileMealQtyDisplay" style="font-size: 0.95rem;">1</span>
                    <button type="button" class="btn p-0 text-light d-flex align-items-center justify-content-center"
                            style="width: 34px; height: 34px;"
                            onclick="let q = document.getElementById('mobileMealQtyInput'); let d = document.getElementById('mobileMealQtyDisplay'); q.value = parseInt(q.value) + 1; d.innerText = q.value; updateMobileBtnPrice();">
                        <i class="fas fa-plus small"></i>
                    </button>
                </div>

                <!-- Add to Cart Button with Dynamic Total -->
                @if($meal->is_available)
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold flex-grow-1 d-flex align-items-center justify-content-center gap-2"
                            style="height: 48px; font-size: 0.95rem;" id="mobileAddToCartBtn">
                        <i class="fas fa-cart-plus"></i>
                        <span>{{ __('Add to Cart') }}</span>
                        <span class="ms-1" id="mobileMealTotalPrice">({{ money($meal->price) }})</span>
                    </button>
                @else
                    <button type="button" class="btn btn-secondary rounded-pill fw-bold flex-grow-1" style="height: 48px;" disabled>
                        <i class="fas fa-ban me-1"></i> {{ __('Unavailable') }}
                    </button>
                @endif
            </form>
        </div>

        <script>
            function updateMobileBtnPrice() {
                const basePrice = {{ (float) $meal->price }};
                const qty = parseInt(document.getElementById('mobileMealQtyInput').value) || 1;
                const total = (basePrice * qty).toFixed(2);
                const priceSpan = document.getElementById('mobileMealTotalPrice');
                if (priceSpan) {
                    priceSpan.innerText = '₪' + total;
                }
            }
        </script>
    </div>

    {{-- ================= DESKTOP VIEW (≥768px, UNTOUCHED) ================= --}}
    <div class="container py-5 d-none d-md-block">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-secondary hover-text-primary">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('meals.index') }}" class="text-secondary hover-text-primary">{{ __('Meals') }}</a></li>
                <li class="breadcrumb-item active text-light" aria-current="page">{{ $meal->name }}</li>
            </ol>
        </nav>

        <div class="row g-4 g-lg-5">
            <!-- Left Column: Image & Gallery -->
            <div class="col-lg-6">
                <div class="position-relative overflow-hidden rounded-4 shadow-lg border border-secondary border-opacity-10 mb-4" style="aspect-ratio: 4/3; background: var(--surface-1);">
                    @php $meal->loadMissing('image'); @endphp
                    @if ($meal->relationLoaded('image') && $meal->image)
                        <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="w-100 h-100 object-fit-cover transition-transform"
                            alt="{{ $meal->name }}">
                    @else
                        <img src="{{ asset('assets/images/placeholder.svg') }}" class="w-100 h-100 object-fit-cover"
                            alt="{{ $meal->name }}">
                    @endif

                    @if(! $meal->is_available)
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-ban me-1"></i> {{ __('Currently Unavailable') }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Chef Preview Card -->
                <div class="card p-3 rounded-4 border border-secondary border-opacity-10 spotlight-card d-flex flex-row align-items-center justify-content-between" style="background: var(--surface-1);">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary border border-primary border-opacity-25"
                             style="width: 52px; height: 52px; background: var(--surface-2);">
                            <i class="fas fa-hat-chef fa-lg"></i>
                        </div>
                        <div>
                            <small class="text-secondary d-block text-uppercase fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                                {{ __('Prepared by') }}
                            </small>
                            <h6 class="mb-0 fw-bold text-light">
                                {{ $meal->chef->kitchen_name ?? $meal->chef->name ?? __('Chef / Culinary Partner') }}
                            </h6>
                        </div>
                    </div>
                    @if(isset($meal->chef))
                        <a href="{{ route('chefs.show', $meal->chef->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                            {{ __('View Chef') }} <i class="fas fa-arrow-right ms-1 small"></i>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right Column: Details & Sticky Order Panel -->
            <div class="col-lg-6">
                <div class="ps-lg-2">
                    <!-- Title & Price -->
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <h1 class="h2 fw-bold text-light mb-0">{{ $meal->name }}</h1>
                        <div class="text-end flex-shrink-0">
                            <span class="h3 fw-bold text-primary d-block mb-0">{{ money($meal->price) }}</span>
                            <small class="text-secondary">{{ __('per order') }}</small>
                        </div>
                    </div>

                    <!-- Meta Tags -->
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-4">
                        <span class="badge px-3 py-2 rounded-pill border border-secondary border-opacity-10" style="background: var(--surface-2); color: var(--text-main);">
                            <i class="fas fa-utensils me-1 text-primary"></i> {{ $meal->category->name ?? __('General') }}
                        </span>
                        @if($meal->number_persons)
                            <span class="badge px-3 py-2 rounded-pill border border-secondary border-opacity-10" style="background: var(--surface-2); color: var(--text-main);">
                                <i class="fas fa-users me-1 text-primary"></i> {{ $meal->number_persons }} {{ __('persons') }}
                            </span>
                        @endif
                        @if($meal->prep_time_minutes)
                            <span class="badge px-3 py-2 rounded-pill border border-secondary border-opacity-10" style="background: var(--surface-2); color: var(--text-main);">
                                <i class="fas fa-clock me-1 text-primary"></i> {{ $meal->prep_time_minutes }} {{ __('min') }}
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="text-secondary text-uppercase fw-semibold mb-2" style="font-size: 0.8rem; letter-spacing: 0.05em;">
                            {{ __('Description') }}
                        </h6>
                        <p class="text-light opacity-90 lh-lg mb-0">{{ $meal->description }}</p>
                    </div>

                    <!-- Add to Cart Sticky Box -->
                    <div class="card p-4 rounded-4 border border-secondary border-opacity-10 mb-5 shadow-lg" style="background: var(--surface-1);">
                        <h5 class="fw-bold text-light mb-3">{{ __('Order Now') }}</h5>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="meal_id" value="{{ $meal->id }}">

                            <div class="d-flex align-items-end gap-3 mb-4">
                                <div class="flex-grow-1">
                                    <label class="form-label text-secondary small fw-medium mb-1">{{ __('Quantity') }}</label>
                                    <div class="input-group" style="max-width: 140px;">
                                        <button class="btn btn-outline-secondary border-opacity-25 text-light" type="button"
                                            onclick="this.nextElementSibling.stepDown()"><i class="fas fa-minus small"></i></button>
                                        <input type="number" name="quantity" value="1" min="1"
                                            class="form-control text-center text-light border-secondary border-opacity-25"
                                            style="background: var(--surface-2);">
                                        <button class="btn btn-outline-secondary border-opacity-25 text-light" type="button"
                                            onclick="this.previousElementSibling.stepUp()"><i class="fas fa-plus small"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                @if($meal->is_available)
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-lg" data-magnetic>
                                        <i class="fas fa-cart-plus me-2"></i> {{ __('Add to Cart') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary btn-lg rounded-pill fw-bold" disabled>
                                        <i class="fas fa-ban me-2"></i> {{ __('Currently Unavailable') }}
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reviews Section -->
                <div class="mt-5 pt-3 border-top border-secondary border-opacity-10">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-light mb-0">
                            {{ __('Reviews') }}
                            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary ms-2" style="font-size: 0.85rem;">
                                {{ $meal->ratings_count ?? $meal->ratings->count() }}
                            </span>
                        </h4>
                    </div>

                    <!-- Review Submission -->
                    @auth
                        <div class="card p-4 rounded-4 border border-secondary border-opacity-10 mb-4" style="background: var(--surface-1);">
                            <h5 class="fw-bold text-light mb-3">{{ __('Write a Review') }}</h5>
                            <form action="{{ route('meals.rate', $meal->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-secondary">{{ __('Rating') }}</label>
                                    <div class="d-flex gap-2">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <input type="radio" class="btn-check" name="star"
                                                id="star{{ $i }}" value="{{ $i }}"
                                                {{ $i == 5 ? 'checked' : '' }}>
                                            <label class="btn btn-outline-warning btn-sm rounded-pill px-3"
                                                for="star{{ $i }}">{{ $i }} <i class="fas fa-star ms-1"></i></label>
                                        @endfor
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold text-secondary">{{ __('Your Review') }}</label>
                                    <textarea name="body" class="form-control text-light border-secondary border-opacity-10" rows="3"
                                        style="background: var(--surface-2);" placeholder="{{ __('Share your experience...') }}"></textarea>
                                </div>
                                <button class="btn btn-primary px-4 rounded-pill fw-bold" data-magnetic>{{ __('Submit Review') }}</button>
                            </form>
                        </div>
                    @else
                        <div class="alert p-3 rounded-4 border border-secondary border-opacity-10 text-secondary mb-4" style="background: var(--surface-1);">
                            <i class="fas fa-info-circle text-primary me-2"></i> {{ __('Please login to write a review.') }}
                        </div>
                    @endauth

                    <!-- Reviews List -->
                    <div class="reviews-list">
                        @forelse($meal->ratings as $rating)
                            <div class="p-3 mb-3 rounded-4 border border-secondary border-opacity-10" style="background: var(--surface-1);">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary"
                                             style="width: 38px; height: 38px; background: var(--surface-2);">
                                            <i class="fas fa-user-circle"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold text-light mb-0">{{ $rating->user->name ?? __('User') }}</h6>
                                            <small class="text-secondary" style="font-size: 0.75rem;">{{ $rating->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    <div class="text-warning small">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $rating->star ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                @if($rating->body)
                                    <p class="text-secondary mb-0 small lh-base ps-1">{{ $rating->body }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-secondary mb-0">{{ __('No reviews yet. Be the first to review!') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Meals -->
        @if ($relatedMeals->count() > 0)
            <div class="mt-5 pt-5 border-top border-secondary border-opacity-10">
                <x-ui.section-heading
                    :title="__('You might also like')"
                    :subtitle="__('Other signature delicacies you will love')" />

                <div class="row g-4" data-reveal-stagger>
                    @foreach ($relatedMeals as $related)
                        <div class="col-12 col-sm-6 col-lg-3">
                            <x-ui.meal-card :meal="$related" />
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
