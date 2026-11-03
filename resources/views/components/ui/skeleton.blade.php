@props([
    'type' => 'card', // card, text, avatar, row
    'count' => 1,
    'height' => null,
])

@for($i = 0; $i < $count; $i++)
    @if($type === 'card')
        <div {{ $attributes->merge(['class' => 'spotlight-card rounded-4 p-3 mb-3']) }}>
            <div class="skeleton-box w-100 rounded-3 mb-3" style="height: {{ $height ?: '180px' }};"></div>
            <div class="skeleton-box w-75 rounded-2 mb-2" style="height: 20px;"></div>
            <div class="skeleton-box w-50 rounded-2" style="height: 16px;"></div>
        </div>
    @elseif($type === 'avatar')
        <div {{ $attributes->merge(['class' => 'skeleton-box rounded-circle']) }} style="width: {{ $height ?: '48px' }}; height: {{ $height ?: '48px' }};"></div>
    @elseif($type === 'row')
        <div {{ $attributes->merge(['class' => 'd-flex align-items-center gap-3 py-2']) }}>
            <div class="skeleton-box rounded-circle" style="width: 36px; height: 36px; flex-shrink: 0;"></div>
            <div class="flex-grow-1">
                <div class="skeleton-box w-75 rounded-2 mb-1" style="height: 16px;"></div>
                <div class="skeleton-box w-50 rounded-2" style="height: 12px;"></div>
            </div>
            <div class="skeleton-box w-25 rounded-2" style="height: 16px;"></div>
        </div>
    @else
        <div {{ $attributes->merge(['class' => 'skeleton-box rounded-2 mb-2']) }} style="height: {{ $height ?: '20px' }};"></div>
    @endif
@endfor
