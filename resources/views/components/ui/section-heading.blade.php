@props([
    'title' => '',
    'subtitle' => null,
    'badge' => null,
    'viewAllUrl' => null,
    'viewAllText' => null,
])

<div {{ $attributes->merge(['class' => 'd-flex justify-content-between align-items-end mb-4']) }}>
    <div>
        @if($badge)
            <span class="badge badge-primary mb-2">{{ $badge }}</span>
        @endif
        <h2 class="h3 fw-bold text-main mb-1">{{ $title }}</h2>
        @if($subtitle)
            <p class="text-secondary small mb-0">{{ $subtitle }}</p>
        @endif
    </div>

    @if($viewAllUrl)
        <a href="{{ $viewAllUrl }}" class="btn btn-link text-primary text-decoration-none fw-semibold d-inline-flex align-items-center gap-1 p-0">
            <span>{{ $viewAllText ?: __('View All') }}</span>
            <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} small"></i>
        </a>
    @elseif(isset($actions))
        <div>
            {{ $actions }}
        </div>
    @endif
</div>
