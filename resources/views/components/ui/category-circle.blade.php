@props([
    'category',
    'isSelected' => false,
])

@php
    $iconsMap = [
        'Traditional' => 'fas fa-bowl-food',
        'Oriental' => 'fas fa-pepper-hot',
        'Sweets' => 'fas fa-cake-candles',
        'Fast Food' => 'fas fa-burger',
        'Western' => 'fas fa-pizza-slice',
    ];
    $categoryIcon = $iconsMap[$category->name] ?? 'fas fa-utensils';
@endphp

<a href="{{ route('meals.index', ['category' => $category->id]) }}"
   class="text-decoration-none d-flex flex-column align-items-center flex-shrink-0 category-circle-item"
   style="width: 72px;">
    <!-- 62px circle -->
    <div class="category-circle-wrapper rounded-circle d-flex align-items-center justify-content-center position-relative mb-2 {{ $isSelected ? 'selected' : '' }}"
         style="width: 62px; height: 62px; background: #1c1c1c; border: 1px solid rgba(255, 255, 255, 0.08); transition: all 0.2s cubic-bezier(0.2, 0.8, 0.2, 1);">
        
        <div class="rounded-circle d-flex align-items-center justify-content-center"
             style="width: 50px; height: 50px; background: rgba(255, 122, 24, 0.12); color: #ff7a18;">
            <i class="{{ $categoryIcon }}" style="font-size: 20px;"></i>
        </div>
    </div>
    
    <span class="category-circle-label text-truncate w-100 text-center"
          style="font-size: 12px; font-weight: {{ $isSelected ? '700' : '600' }}; color: {{ $isSelected ? '#ff7a18' : '#f2f2f2' }};">
        {{ $category->name }}
    </span>
</a>
