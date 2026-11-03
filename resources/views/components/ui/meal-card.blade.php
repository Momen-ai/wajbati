@props([
    'meal',
    'showChef' => true,
    'isFavorited' => false,
])

@php
    $mealHasImage = $meal->relationLoaded('image') && $meal->image && $meal->image->image_path;
    $rating = (isset($meal->ratings_avg_star) && $meal->ratings_avg_star > 0) ? number_format($meal->ratings_avg_star, 1) : '5.0';
@endphp

<!-- Mobile Card (<768px): 2-Column Grid Matching Reference Screens 1, 2, 4 -->
<div class="d-md-none h-100">
    <div {{ $attributes->merge(['class' => 'mobile-meal-card h-100 d-flex flex-column rounded-4 position-relative overflow-hidden']) }}
         style="background: #151515; border: 1px solid rgba(255, 255, 255, 0.08); padding: 6px;">
        
        <!-- Image Area: 108px high, 15px radius -->
        <div class="position-relative overflow-hidden w-100" style="height: 108px; border-radius: 15px; background: #1c1c1c;">
            <a href="{{ route('meals.show', $meal->id) }}" class="d-block w-100 h-100">
                @if ($mealHasImage)
                    <img src="{{ asset('storage/' . $meal->image->image_path) }}"
                         alt="{{ $meal->name }}"
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy">
                @else
                    <img src="{{ asset('assets/images/placeholder.svg') }}"
                         alt="{{ $meal->name }}"
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy">
                @endif
            </a>

            <!-- Heart / Favorite Button (Top Inline-End, 32px Translucent Circle) -->
            @if($isFavorited)
                <form action="{{ route('favourites.meal.toggle', $meal) }}" method="POST" class="position-absolute top-0 end-0 m-1.5" style="z-index: 3;">
                    @csrf
                    <button type="submit" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center p-0 border-0"
                            style="width: 32px; height: 32px; min-height: 32px; background: rgba(0, 0, 0, 0.55); color: #ff6b6b; backdrop-filter: blur(8px);"
                            title="{{ __('Remove') }}" aria-label="{{ __('Remove from favourites') }}">
                        <i class="fas fa-heart" style="font-size: 13px;"></i>
                    </button>
                </form>
            @else
                <form action="{{ route('favourites.meal.toggle', $meal) }}" method="POST" class="position-absolute top-0 end-0 m-1.5" style="z-index: 3;">
                    @csrf
                    <button type="submit" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center p-0 border-0"
                            style="width: 32px; height: 32px; min-height: 32px; background: rgba(0, 0, 0, 0.45); color: rgba(255, 255, 255, 0.85); backdrop-filter: blur(8px);"
                            title="{{ __('Favorite') }}" aria-label="{{ __('Add to favourites') }}">
                        <i class="far fa-heart" style="font-size: 13px;"></i>
                    </button>
                </form>
            @endif

            <!-- Rating or Prep-time Chip (Bottom Inline-Start) -->
            <div class="position-absolute bottom-0 start-0 m-1.5 d-flex align-items-center gap-1 rounded-pill px-2 py-0.5"
                 style="background: rgba(14, 14, 14, 0.82); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.08); font-size: 11px; font-weight: 700; color: #f2f2f2;">
                @if($meal->prep_time_minutes)
                    <i class="far fa-clock text-warning" style="font-size: 10px;"></i>
                    <span>{{ $meal->prep_time_minutes }} {{ __('min') }}</span>
                @else
                    <i class="fas fa-star text-warning" style="font-size: 10px;"></i>
                    <span>{{ $rating }}</span>
                @endif
            </div>
        </div>

        <!-- Body Area: 1-line name, chef/rating meta, price + round 34px "+" button -->
        <div class="pt-2 px-1 pb-1 d-flex flex-column flex-grow-1">
            <h6 class="fw-bold mb-1 text-light text-truncate" style="font-size: 14px; line-height: 1.25;" title="{{ $meal->name }}">
                <a href="{{ route('meals.show', $meal->id) }}" class="text-light text-decoration-none">
                    {{ $meal->name }}
                </a>
            </h6>

            @if($showChef && isset($meal->chef))
                <div class="d-flex align-items-center gap-1.5 mb-2 text-secondary" style="font-size: 11px;">
                    <i class="fas fa-star text-warning" style="font-size: 10px;"></i>
                    <span class="text-light fw-bold">{{ $rating }}</span>
                    <span>•</span>
                    <span class="text-truncate">{{ $meal->chef->kitchen_name ?? $meal->chef->name }}</span>
                </div>
            @endif

            <!-- Price and Round 34px Orange Add Button -->
            <div class="d-flex align-items-center justify-content-between mt-auto pt-1">
                <span class="fw-bold" style="font-size: 16px; color: #ff7a18; letter-spacing: -0.01em;">
                    {{ money($meal->price) }}
                </span>

                @if(!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'chef'))
                    <form action="{{ route('cart.add') }}" method="POST" class="m-0">
                        @csrf
                        <input type="hidden" name="meal_id" value="{{ $meal->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit"
                                class="btn p-0 rounded-circle d-flex align-items-center justify-content-center shadow-sm border-0"
                                style="width: 34px; height: 34px; min-height: 34px; background: #ff7a18; color: #1b0e03 !important;"
                                {{ !$meal->is_available ? 'disabled' : '' }}
                                title="{{ __('Add to Cart') }}"
                                aria-label="{{ __('Add :name to cart', ['name' => $meal->name]) }}">
                            <i class="fas fa-plus" style="font-size: 14px;"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Desktop Card (>=768px): Strictly Unchanged -->
<div class="d-none d-md-flex flex-column h-100">
    <div {{ $attributes->merge(['class' => 'spotlight-card h-100 d-flex flex-column meal-card rounded-4 border border-subtle overflow-hidden']) }} data-spotlight>
        <!-- Image & Badge Container -->
        <div class="position-relative overflow-hidden" style="aspect-ratio: 16/10; border-radius: calc(var(--radius) - 2px) calc(var(--radius) - 2px) 0 0; background: var(--surface-2);">
            <a href="{{ route('meals.show', $meal->id) }}" class="d-block w-100 h-100">
                @if ($mealHasImage)
                    <img src="{{ asset('storage/' . $meal->image->image_path) }}"
                         alt="{{ $meal->name }}"
                         class="w-100 h-100 object-fit-cover transition-transform"
                         loading="lazy"
                         style="transition: transform var(--duration-normal) var(--ease-out);">
                @else
                    <img src="{{ asset('assets/images/placeholder.svg') }}"
                         alt="{{ $meal->name }}"
                         class="w-100 h-100 object-fit-cover"
                         loading="lazy">
                @endif
            </a>

            <!-- Category Chip: Solid dark pill with light border for perfect contrast -->
            <span class="position-absolute top-0 start-0 m-3 badge rounded-pill px-3 py-1.5 border border-subtle shadow-sm"
                  style="background: rgba(18, 18, 18, 0.88); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: var(--text-main); font-weight: 600;">
                <i class="fas fa-utensils me-1 text-primary small"></i> {{ $meal->category->name ?? __('General') }}
            </span>

            <!-- Favorite or Availability or Prep time badge -->
            @if($isFavorited)
                <form action="{{ route('favourites.meal.toggle', $meal) }}" method="POST" class="position-absolute top-0 end-0 m-3" style="z-index: 2;">
                    @csrf
                    <button type="submit" class="btn btn-sm bg-surface-1 text-danger rounded-circle border border-subtle shadow-sm d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="{{ __('Remove') }}">
                        <i class="fas fa-heart"></i>
                    </button>
                </form>
            @elseif(!$meal->is_available)
                <span class="position-absolute top-0 end-0 m-3 badge bg-danger text-white rounded-pill px-2.5 py-1 shadow-sm">
                    {{ __('Unavailable') }}
                </span>
            @elseif($meal->prep_time_minutes)
                <span class="position-absolute top-0 end-0 m-3 badge rounded-pill border border-subtle px-2.5 py-1 shadow-sm"
                      style="background: rgba(18, 18, 18, 0.88); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); color: var(--text-secondary);">
                    <i class="far fa-clock me-1 text-primary"></i>{{ $meal->prep_time_minutes }} {{ __('min') }}
                </span>
            @endif
        </div>

        <!-- Card Content -->
        <div class="p-3 p-md-4 d-flex flex-column flex-grow-1">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <!-- 2-line title clamp with tooltip -->
                <h5 class="fw-bold mb-0 text-main flex-grow-1"
                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.7em; line-height: 1.35;"
                    title="{{ $meal->name }}">
                    <a href="{{ route('meals.show', $meal->id) }}" class="text-main hover-text-primary text-decoration-none">
                        {{ $meal->name }}
                    </a>
                </h5>
                <span class="fw-bold text-primary fs-5 text-nowrap">
                    {{ money($meal->price) }}
                </span>
            </div>

            @if($showChef && isset($meal->chef))
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle overflow-hidden flex-shrink-0 border border-subtle" style="width: 24px; height: 24px; background: var(--surface-2);">
                        @php $chefHasImg = $meal->chef->relationLoaded('image') && $meal->chef->image; @endphp
                        @if ($chefHasImg)
                            <img src="{{ asset('storage/' . $meal->chef->image->image_path) }}" alt="{{ $meal->chef->name }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-primary" style="font-size: 10px;">
                                <i class="fas fa-hat-chef"></i>
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('chefs.show', $meal->chef->id) }}" class="text-secondary small text-decoration-none hover-text-primary text-truncate">
                        {{ $meal->chef->kitchen_name ?? $meal->chef->name }}
                    </a>
                </div>
            @endif

            <p class="text-secondary small mb-4 flex-grow-1"
               style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.8em; line-height: 1.4;">
                {{ $meal->description ?? __('Freshly prepared delicacy crafted with culinary skill and authentic ingredients.') }}
            </p>

            <!-- Card Footer -->
            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top border-subtle">
                <div class="d-flex align-items-center gap-2 text-secondary small">
                    @if($meal->number_persons)
                        <span><i class="fas fa-users me-1 text-primary"></i>{{ $meal->number_persons }}</span>
                    @endif
                    <span class="ms-1"><i class="fas fa-star text-warning me-1"></i>{{ $rating }}</span>
                </div>

                <div class="d-flex gap-2">
                    @if(!auth()->check() || (auth()->user()->role !== 'admin' && auth()->user()->role !== 'chef'))
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="meal_id" value="{{ $meal->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold shadow-sm" {{ !$meal->is_available ? 'disabled' : '' }} title="{{ __('Add to Cart') }}">
                                <i class="fas fa-plus me-1"></i> {{ __('Add') }}
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('meals.show', $meal->id) }}" class="btn btn-outline-subtle btn-sm rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="{{ __('View Details') }}">
                        <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
