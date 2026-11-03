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

@php
    $cleanedTrend = $trend ? trim($trend) : null;
    $isZero = $cleanedTrend ? (in_array($cleanedTrend, ['0%', '+0%', '-0%', '0.0%', '+0.0%', '-0.0%']) || (float) filter_var($cleanedTrend, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) === 0.0) : false;
    $badgeClass = $isZero ? 'bg-surface-2 text-secondary border border-subtle' : ($trendUp ? 'badge-success' : 'badge-danger');
    $arrowIcon = $isZero ? 'minus' : ($trendUp ? 'arrow-up' : 'arrow-down');
@endphp

<!-- Mobile Specific Card (<768px): 152px width for scroll-snap row matching Ref 6 -->
<div class="d-md-none flex-shrink-0 stat-card-mobile rounded-4 p-3 position-relative"
     style="width: 152px; background: #151515; border: 1px solid rgba(255, 255, 255, 0.08);">
    <div class="d-flex align-items-center justify-content-between mb-2">
        @if($icon)
            <div class="rounded-3 d-inline-flex align-items-center justify-content-center"
                 style="width: 32px; height: 32px; background: rgba(255, 122, 24, 0.14); color: #ff7a18;">
                <i class="{{ $icon }}" style="font-size: 15px;"></i>
            </div>
        @endif

        @if($trend)
            <span class="badge rounded-pill px-2 py-0.5 d-inline-flex align-items-center gap-1"
                  style="font-size: 10px; font-weight: 700; background: {{ $trendUp ? 'rgba(74, 222, 128, 0.14)' : 'rgba(255, 107, 107, 0.14)' }}; color: {{ $trendUp ? '#4ade80' : '#ff6b6b' }};">
                <i class="fas fa-{{ $arrowIcon }}" style="font-size: 8px;"></i>
                <span>{{ $trend }}</span>
            </span>
        @endif
    </div>

    <div class="text-secondary small mb-1" style="font-size: 12px; font-weight: 500;">
        {{ $label }}
    </div>

    <div class="fw-bold text-light" style="font-size: 22px; line-height: 1.2; letter-spacing: -0.02em;">
        {{ $value }}
    </div>
</div>

<!-- Desktop Specific Card (>=768px): Unchanged -->
<div {{ $attributes->merge(['class' => 'd-none d-md-block spotlight-card p-4 rounded-4 position-relative overflow-hidden ' . ($isHero ? 'border-primary' : '')]) }}>
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
            <span class="badge {{ $badgeClass }} d-inline-flex align-items-center gap-1 font-monospace">
                <i class="fas fa-{{ $arrowIcon }} small"></i>
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
