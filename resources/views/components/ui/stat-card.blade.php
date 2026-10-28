@props([
    'icon' => null,
    'label' => '',
    'value' => '',
    'numericValue' => null,
    'trend' => null,
    'trendUp' => true,
    'isHero' => false,
    'sparkline' => null,
])

<div {{ $attributes->merge(['class' => 'spotlight-card p-4 rounded-4 position-relative overflow-hidden ' . ($isHero ? 'border-primary' : '')]) }}>
    <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-secondary small fw-medium">{{ $label }}</span>
        @if($icon)
            <div class="rounded-3 d-inline-flex align-items-center justify-content-center"
                 style="width: 44px; height: 44px; background: rgba(255, 122, 24, 0.12); color: var(--primary);">
                <i class="{{ $icon }} fs-5"></i>
            </div>
        @endif
    </div>

    <div class="d-flex align-items-baseline justify-content-between">
        <h3 class="{{ $isHero ? 'display-6 fw-bold text-primary' : 'h2 fw-bold text-main' }} mb-0"
            @if($numericValue !== null) data-count-up="{{ $numericValue }}" @endif>
            {{ $value }}
        </h3>

        @if($trend)
            <span class="badge {{ $trendUp ? 'badge-success' : 'badge-danger' }} d-inline-flex align-items-center gap-1">
                <i class="fas fa-arrow-{{ $trendUp ? 'up' : 'down' }} small"></i>
                <span>{{ $trend }}</span>
            </span>
        @endif
    </div>

    @if($sparkline)
        <div class="mt-3 pt-2 border-top border-subtle">
            {{ $sparkline }}
        </div>
    @endif
</div>
