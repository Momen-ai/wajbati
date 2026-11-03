@props([
    'category',
    'icon' => 'fas fa-utensils',
])

@php
    $iconsMap = [
        'Traditional' => 'fas fa-bowl-food',
        'Oriental' => 'fas fa-pepper-hot',
        'Sweets' => 'fas fa-cake-candles',
        'Fast Food' => 'fas fa-burger',
        'Western' => 'fas fa-pizza-slice',
    ];
    $categoryIcon = $iconsMap[$category->name] ?? $icon;
@endphp

<a href="{{ route('meals.index', ['category' => $category->id]) }}" class="text-decoration-none d-block h-100">
    <div {{ $attributes->merge(['class' => 'spotlight-card text-center p-3 p-md-4 h-100 d-flex flex-column align-items-center justify-content-center rounded-4 border border-subtle category-tile-expanded']) }} data-spotlight>
        <div class="category-icon rounded-circle d-flex align-items-center justify-content-center mb-3"
             style="width: 68px; height: 68px; background: rgba(255, 122, 24, 0.12); color: var(--primary); transition: all var(--duration-normal) var(--ease-out);">
            <i class="{{ $categoryIcon }} fs-3"></i>
        </div>
        <h6 class="fw-bold text-main mb-1 text-truncate w-100">{{ $category->name }}</h6>
        @if(isset($category->meals_count))
            <span class="badge rounded-pill bg-surface-2 text-secondary border border-subtle small px-2.5 py-0.5">
                {{ $category->meals_count }} {{ __('Meals') }}
            </span>
        @endif
    </div>
</a>
