@props([
    'chef',
    'isFavorited' => false,
])

@php
    $chefHasImage = $chef->relationLoaded('image') && $chef->image && $chef->image->image_path;
    $mealsCount = $chef->meals_count ?? ($chef->relationLoaded('meals') ? $chef->meals->count() : 0);
    $meals = $chef->relationLoaded('meals') ? $chef->meals : collect();
    $minPrice = $meals->isNotEmpty() ? $meals->min('price') : null;
    $specialties = $meals->pluck('category.name')->filter()->unique()->take(2);
    $topMealImage = $meals->firstWhere('image')?->image?->image_path;
    $isComingSoon = $mealsCount === 0;
@endphp

<div {{ $attributes->merge(['class' => 'spotlight-card rounded-4 h-100 d-flex flex-column overflow-hidden position-relative border border-subtle' . ($isComingSoon ? ' opacity-75' : '')]) }} data-spotlight>
    <!-- Top Cover Strip (Blurred food thumbnail or rich gradient) -->
    <div class="position-relative w-100 overflow-hidden" style="height: 84px; background: var(--surface-2);">
        @if($topMealImage)
            <img src="{{ asset('storage/' . $topMealImage) }}" alt="" class="w-100 h-100 object-fit-cover opacity-30 filter-blur-sm" loading="lazy" style="filter: blur(4px); transform: scale(1.1);">
        @else
            <div class="w-100 h-100" style="background: radial-gradient(circle at 50% 0%, rgba(255, 122, 24, 0.22), transparent 75%);"></div>
        @endif
        <div class="position-absolute inset-0 w-100 h-100" style="background: linear-gradient(to bottom, transparent, var(--surface-1));"></div>

        @if($isFavorited)
            <form action="{{ route('favourites.chef.toggle', $chef) }}" method="POST" class="position-absolute top-0 end-0 m-2" style="z-index: 3;">
                @csrf
                <button type="submit" class="btn btn-sm bg-surface-1 text-danger rounded-circle border border-subtle shadow-sm d-flex align-items-center justify-content-center p-0" style="width: 32px; height: 32px;" title="{{ __('Remove') }}">
                    <i class="fas fa-heart small"></i>
                </button>
            </form>
        @endif

        @if($isComingSoon)
            <span class="position-absolute top-0 start-0 m-2 badge bg-surface-1 text-secondary border border-subtle small fw-semibold">
                {{ __('Coming Soon') }}
            </span>
        @endif
    </div>

    <!-- Main Card Body -->
    <div class="p-4 pt-0 d-flex flex-column flex-grow-1">
        <!-- Overlapping Avatar with Verified Badge -->
        <div class="d-flex justify-content-between align-items-end mb-3" style="margin-top: -42px;">
            <div class="position-relative">
                @if ($chefHasImage)
                    <img src="{{ asset('storage/' . $chef->image->image_path) }}"
                         alt="{{ $chef->name }}"
                         class="rounded-circle object-fit-cover shadow border border-2 border-subtle"
                         style="width: 74px; height: 74px; background: var(--surface-2);"
                         loading="lazy">
                @else
                    <div class="rounded-circle bg-surface-2 text-primary d-flex align-items-center justify-content-center shadow border border-2 border-subtle fs-4 fw-bold"
                         style="width: 74px; height: 74px;">
                        {{ mb_substr($chef->name, 0, 1) }}
                    </div>
                @endif

                <span class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                      style="width: 22px; height: 22px; font-size: 9px; border: 2px solid var(--surface-1);" title="{{ __('Verified Chef') }}">
                    <i class="fas fa-check"></i>
                </span>
            </div>

            <!-- Rating or Badge -->
            <div class="text-end">
                @if(isset($chef->ratings_avg_star) && $chef->ratings_avg_star > 0)
                    <span class="badge bg-surface-2 text-main border border-subtle px-2.5 py-1">
                        <i class="fas fa-star text-warning me-1"></i>{{ number_format($chef->ratings_avg_star, 1) }}
                        <small class="text-secondary">({{ $chef->ratings_count ?? 1 }})</small>
                    </span>
                @else
                    <span class="badge bg-surface-2 text-primary border border-subtle px-2.5 py-1">
                        <i class="fas fa-star text-warning me-1"></i>4.9
                    </span>
                @endif
            </div>
        </div>

        <!-- Name & Kitchen -->
        <h5 class="fw-bold mb-1 text-main text-truncate">
            <a href="{{ route('chefs.show', $chef->id) }}" class="text-main hover-text-primary text-decoration-none">
                {{ $chef->kitchen_name ?? $chef->name }}
            </a>
        </h5>
        <div class="d-flex align-items-center gap-2 mb-2">
            <span class="text-secondary small fw-medium">{{ $chef->name }}</span>
            <span class="text-secondary opacity-40">·</span>
            <span class="text-secondary small text-truncate">
                <i class="fas fa-map-marker-alt me-1 text-primary small"></i>{{ $chef->address ?? __('Local Chef') }}
            </span>
        </div>

        <!-- Specialty Chips -->
        <div class="d-flex align-items-center gap-1.5 mb-3 flex-wrap">
            @forelse($specialties as $catName)
                <span class="badge rounded-pill bg-surface-2 text-secondary border border-subtle small px-2.5 py-1">
                    {{ $catName }}
                </span>
            @empty
                <span class="badge rounded-pill bg-surface-2 text-secondary border border-subtle small px-2.5 py-1">
                    {{ __('Signature Specialties') }}
                </span>
            @endforelse
            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 small px-2.5 py-1">
                {{ $mealsCount }} {{ __('Dishes') }}
            </span>
        </div>

        <!-- Row of up to 3 Dish Thumbnails -->
        @if($meals->isNotEmpty())
            <div class="d-flex align-items-center gap-2 mb-3 pt-2 border-top border-subtle">
                @foreach($meals->take(3) as $thumbMeal)
                    @php $thumbHasImg = $thumbMeal->relationLoaded('image') && $thumbMeal->image; @endphp
                    <div class="rounded-3 overflow-hidden flex-shrink-0 border border-subtle" style="width: 52px; height: 52px; background: var(--surface-2);" title="{{ $thumbMeal->name }}">
                        @if($thumbHasImg)
                            <img src="{{ asset('storage/' . $thumbMeal->image->image_path) }}" alt="{{ $thumbMeal->name }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                        @else
                            <img src="{{ asset('assets/images/placeholder.svg') }}" alt="{{ $thumbMeal->name }}" class="w-100 h-100 object-fit-cover" loading="lazy">
                        @endif
                    </div>
                @endforeach
                @if($mealsCount > 3)
                    <div class="rounded-3 d-flex align-items-center justify-content-center text-secondary small fw-semibold bg-surface-2 border border-subtle flex-shrink-0" style="width: 52px; height: 52px;">
                        +{{ $mealsCount - 3 }}
                    </div>
                @endif
            </div>
        @endif

        <!-- Card Footer: Price From & Action Button -->
        <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top border-subtle">
            <div>
                @if($minPrice)
                    <small class="text-secondary d-block" style="font-size: 0.72rem;">{{ __('Starts from') }}</small>
                    <span class="fw-bold text-primary fs-6">{{ money($minPrice) }}</span>
                @else
                    <span class="text-secondary small">{{ __('Menu launching soon') }}</span>
                @endif
            </div>

            @if(!$isComingSoon)
                <a href="{{ route('chefs.show', $chef->id) }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold shadow-sm" data-magnetic>
                    {{ __('View Kitchen') }}
                </a>
            @else
                <button type="button" class="btn btn-outline-subtle btn-sm rounded-pill px-3 text-secondary" disabled>
                    {{ __('Coming Soon') }}
                </button>
            @endif
        </div>
    </div>
</div>
