@props([
    'title' => '',
    'subtitle' => null,
    'badge' => null,
])

<div {{ $attributes->merge(['class' => 'd-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2']) }}>
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <h1 class="h2 fw-bold text-main mb-0 tracking-tight">{{ $title }}</h1>
            @if($badge)
                <span class="badge badge-primary">{{ $badge }}</span>
            @endif
        </div>
        @if($subtitle)
            <p class="text-secondary mb-0 small">{{ $subtitle }}</p>
        @endif
    </div>

    @if(isset($actions))
        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{ $actions }}
        </div>
    @endif
</div>
