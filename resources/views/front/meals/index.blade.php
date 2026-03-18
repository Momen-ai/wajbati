@extends('layouts.frontend')

@section('content')
    <div class="container py-4">
        <h2 class="fw-bold mb-4 text-light">All Meals</h2>

        <div class="row g-4">

        @forelse($meals as $meal)
            <div class="col-md-4 mb-4">
                <div class="card shadow-card border border-secondary border-opacity-10 h-100 meal-card">

                    <div class="meal-card-img-wrapper">
                        @if ($meal->image)
                            <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="card-img-top"
                                style="height:220px; object-fit:cover;">
                        @else
                            <img src="https://placehold.co/600x400?text=No+Image" alt="{{ $meal->name }}">
                        @endif
                        <span class="card-badge text-primary-custom">{{ $meal->category->name ?? 'Meals' }}</span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold text-light">{{ $meal->name }}</h5>

                        <p class="text-light opacity-75 mb-1">
                            Chef: {{ $meal->chef->name ?? '—' }}
                        </p>
                        <div class="mb-2 text-warning small">
                            @php $avg = $meal->averageRating(); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $avg ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                            <span class="text-light ms-1">({{ $meal->ratings_count ?? $meal->ratings->count() }})</span>
                        </div>

                        <strong class="mb-3 text-primary-custom">{{ $meal->price }} $</strong>

                        <a href="{{ route('meals.show', $meal->id) }}" class="btn btn-outline-primary mt-auto rounded-pill">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-light">No meals found.</p>
        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $meals->links() }}
    </div>
    </div>
@endsection
