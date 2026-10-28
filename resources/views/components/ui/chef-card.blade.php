@props([
    'chef',
    'isFavorited' => false,
])

<div {{ $attributes->merge(['class' => 'spotlight-card text-center p-4 h-100 d-flex flex-column align-items-center justify-content-between position-relative']) }} data-spotlight>
    @if($isFavorited)
        <form action="{{ route('favourites.chef.toggle', $chef) }}" method="POST" class="position-absolute top-0 end-0 m-3" style="z-index: 2;">
            @csrf
            <button type="submit" class="btn btn-sm bg-surface-2 text-danger rounded-circle border border-subtle shadow-sm d-flex align-items-center justify-content-center p-0" style="width: 28px; height: 28px;" title="{{ __('Remove') }}">
                <i class="fas fa-heart small"></i>
            </button>
        </form>
    @endif
    <div class="w-100 d-flex flex-column align-items-center">
        <!-- Chef Avatar -->
        <div class="position-relative mb-3">
            @if ($chef->image && $chef->image->image_path)
                <img src="{{ asset('storage/' . $chef->image->image_path) }}"
                     alt="{{ $chef->name }}"
                     class="rounded-circle object-fit-cover shadow-sm border border-subtle"
                     style="width: 88px; height: 88px;"
                     loading="lazy">
            @else
                <div class="rounded-circle bg-surface-2 text-primary d-flex align-items-center justify-content-center shadow-sm border border-subtle fs-3 fw-bold"
                     style="width: 88px; height: 88px;">
                    {{ mb_substr($chef->name, 0, 1) }}
                </div>
            @endif

            <span class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1 d-flex align-items-center justify-content-center"
                  style="width: 24px; height: 24px; font-size: 10px;" title="{{ __('Verified Chef') }}">
                <i class="fas fa-check"></i>
            </span>
        </div>

        <h5 class="fw-bold mb-1 text-main text-truncate w-100">
            <a href="{{ route('chefs.show', $chef->id) }}" class="text-main hover-text-primary text-decoration-none">
                {{ $chef->kitchen_name ?? $chef->name }}
            </a>
        </h5>

        <p class="text-secondary small mb-3 text-truncate w-100">
            <i class="fas fa-map-marker-alt me-1 text-primary small"></i>{{ $chef->address ?? __('Local Chef') }}
        </p>

        @if(isset($chef->meals_count))
            <span class="badge bg-surface-2 text-secondary border border-subtle mb-3">
                {{ $chef->meals_count }} {{ __('Dishes') }}
            </span>
        @endif
    </div>

    <a href="{{ route('chefs.show', $chef->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 fw-semibold mt-auto">
        {{ __('View Kitchen') }}
    </a>
</div>
