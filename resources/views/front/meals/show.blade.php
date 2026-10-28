@extends('layouts.frontend')

@section('content')
    <div class="container py-5">
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
                    @if ($meal->image)
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
                                {{ $meal->chef->kitchen_name ?? $meal->chef->name ?? __('Home Cook') }}
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
                    :subtitle="__('Other homemade delicacies you will love')" />

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
