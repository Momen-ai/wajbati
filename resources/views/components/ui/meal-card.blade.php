@props([
    'meal',
    'showChef' => true,
    'isFavorited' => false,
])

<div {{ $attributes->merge(['class' => 'spotlight-card h-100 d-flex flex-column meal-card']) }} data-spotlight>
    <!-- Image & Badge Container -->
    <div class="position-relative overflow-hidden" style="aspect-ratio: 16/10; border-radius: calc(var(--radius) - 2px) calc(var(--radius) - 2px) 0 0;">
        <a href="{{ route('meals.show', $meal->id) }}" class="d-block w-100 h-100">
            @if ($meal->image && $meal->image->image_path)
                <img src="{{ asset('storage/' . $meal->image->image_path) }}"
                     alt="{{ $meal->name }}"
                     class="w-100 h-100 object-fit-cover transition-transform"
                     loading="lazy"
                     style="transition: transform var(--duration-normal) var(--ease-out);">
            @else
                <img src="{{ asset('assets/images/placeholder.svg') }}"
                     alt="{{ $meal->name }}"
                     class="w-100 h-100 object-fit-cover"
                     loading="lazy">
            @endif
        </a>

        <!-- Category Chip -->
        <span class="position-absolute top-0 start-0 m-3 badge badge-primary shadow-sm">
            {{ $meal->category->name ?? __('General') }}
        </span>

        <!-- Availability or Prep time badge -->
        @if(!$meal->is_available)
            <span class="position-absolute top-0 end-0 m-3 badge badge-danger shadow-sm">
                {{ __('Unavailable') }}
            </span>
        @elseif($meal->prep_time_minutes)
            <span class="position-absolute top-0 end-0 m-3 badge bg-surface-2 text-secondary border border-subtle shadow-sm">
                <i class="far fa-clock me-1 text-primary"></i>{{ $meal->prep_time_minutes }} {{ __('min') }}
            </span>
        @endif
    </div>

    <!-- Card Content -->
    <div class="p-3 p-md-4 d-flex flex-column flex-grow-1">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
            <h5 class="fw-bold mb-0 text-truncate text-main">
                <a href="{{ route('meals.show', $meal->id) }}" class="text-main hover-text-primary text-decoration-none">
                    {{ $meal->name }}
                </a>
            </h5>
            <span class="fw-bold text-primary fs-5 text-nowrap">
                {{ money($meal->price) }}
            </span>
        </div>

        <p class="text-secondary small mb-3 text-truncate flex-grow-1" style="max-height: 2.8em;">
            {{ Str::limit($meal->description, 70) }}
        </p>

        <!-- Meta (Persons & Rating) -->
        <div class="d-flex align-items-center justify-content-between small text-secondary mb-3 pt-2 border-top border-subtle">
            @if($meal->number_persons)
                <span class="d-inline-flex align-items-center gap-1">
                    <i class="fas fa-user-friends text-primary small"></i>
                    <span>{{ $meal->number_persons }} {{ __('Persons') }}</span>
                </span>
            @else
                <span></span>
            @endif

            @if(isset($meal->ratings_avg_rating) && $meal->ratings_avg_rating)
                <span class="d-inline-flex align-items-center gap-1 text-warning fw-semibold">
                    <i class="fas fa-star small"></i>
                    <span>{{ number_format($meal->ratings_avg_rating, 1) }}</span>
                </span>
            @endif
        </div>

        <!-- Footer: Chef info + Action -->
        <div class="d-flex align-items-center justify-content-between pt-2">
            @if($showChef && $meal->chef)
                <a href="{{ route('chefs.show', $meal->chef->id) }}" class="d-flex align-items-center gap-2 text-decoration-none text-secondary">
                    @if($meal->chef->image && $meal->chef->image->image_path)
                        <img src="{{ asset('storage/' . $meal->chef->image->image_path) }}"
                             alt="{{ $meal->chef->name }}"
                             class="rounded-circle object-fit-cover"
                             style="width: 28px; height: 28px;">
                    @else
                        <div class="rounded-circle bg-surface-3 text-primary d-flex align-items-center justify-content-center small fw-bold"
                             style="width: 28px; height: 28px;">
                            {{ mb_substr($meal->chef->name, 0, 1) }}
                        </div>
                    @endif
                    <span class="small text-truncate" style="max-width: 110px;">{{ $meal->chef->name }}</span>
                </a>
            @else
                <div></div>
            @endif

            <a href="{{ route('meals.show', $meal->id) }}" class="btn btn-outline-primary btn-sm px-3 py-1 fw-bold rounded-pill">
                {{ __('Order') }}
            </a>
        </div>
    </div>
</div>
