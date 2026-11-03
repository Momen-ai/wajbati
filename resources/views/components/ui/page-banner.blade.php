@props([
    'image',
    'badge' => null,
    'title' => '',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'inner-page-banner']) }}>
    <!-- Dimmed static frame with subtle transform -->
    <img src="{{ $image }}" alt="" class="banner-media-layer" loading="lazy">

    <!-- Directional & bottom fade scrims -->
    <div class="banner-scrim-bottom"></div>
    <div class="banner-scrim-start"></div>

    <div class="container banner-content-container">
        <div class="row align-items-center justify-content-between g-4">
            <div class="col-lg-7">
                @if($badge)
                    <div class="mb-2">
                        <span class="badge rounded-pill px-3 py-1 bg-surface-2 text-primary border border-subtle fw-bold small">
                            {{ $badge }}
                        </span>
                    </div>
                @endif

                <h1 class="display-6 fw-bold tracking-tight text-main mb-2">
                    {{ $title }}
                </h1>

                @if($subtitle)
                    <p class="text-secondary lead fs-6 mb-0" style="max-width: 620px;">
                        {{ $subtitle }}
                    </p>
                @endif

                @if(isset($stats))
                    <div class="d-flex align-items-center gap-2 mt-3 flex-wrap">
                        {{ $stats }}
                    </div>
                @endif
            </div>

            @if(isset($actions) || isset($slot))
                <div class="col-lg-5">
                    {{ $actions ?? $slot }}
                </div>
            @endif
        </div>
    </div>
</div>
