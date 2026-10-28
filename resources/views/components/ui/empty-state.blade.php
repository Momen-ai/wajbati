@props([
    'icon' => 'fas fa-utensils',
    'title' => '',
    'description' => null,
    'actionText' => null,
    'actionUrl' => null,
])

<div {{ $attributes->merge(['class' => 'text-center py-5 px-3']) }}>
    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
         style="width: 72px; height: 72px; background: rgba(255, 122, 24, 0.1); color: var(--primary);">
        <i class="{{ $icon }} fs-2"></i>
    </div>
    <h5 class="fw-bold text-main mb-2">{{ $title }}</h5>
    @if($description)
        <p class="text-secondary small mb-4 mx-auto" style="max-width: 420px;">{{ $description }}</p>
    @endif

    @if($actionUrl && $actionText)
        <div>
            <a href="{{ $actionUrl }}" class="btn btn-primary rounded-pill px-4">
                {{ $actionText }}
            </a>
        </div>
    @elseif(isset($action))
        <div>
            {{ $action }}
        </div>
    @endif
</div>
