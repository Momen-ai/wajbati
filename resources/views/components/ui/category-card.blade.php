@props([
    'category',
    'icon' => 'fas fa-utensils',
])

<a href="{{ route('meals.index', ['category' => $category->id]) }}" class="text-decoration-none d-block h-100">
    <div {{ $attributes->merge(['class' => 'spotlight-card text-center p-4 h-100 d-flex flex-column align-items-center justify-content-center']) }} data-spotlight>
        <div class="category-icon rounded-circle d-flex align-items-center justify-content-center mb-3"
             style="width: 64px; height: 64px; background: rgba(255, 122, 24, 0.12); color: var(--primary); transition: all var(--duration-normal) var(--ease-out);">
            <i class="{{ $icon }} fs-4"></i>
        </div>
        <h6 class="fw-bold text-main mb-1 text-truncate w-100">{{ $category->name }}</h6>
        @if(isset($category->meals_count))
            <small class="text-secondary">{{ $category->meals_count }} {{ __('Meals') }}</small>
        @endif
    </div>
</a>
