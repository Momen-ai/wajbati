@extends('layouts.frontend')

@section('content')
    <h2 class="mb-4">All Meals</h2>

    <div class="row">

        @forelse($meals as $meal)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">

                    @if ($meal->image)
                        <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="card-img-top"
                            style="height:220px; object-fit:cover;">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5>{{ $meal->name }}</h5>

                        <p class="text-muted mb-1">
                            Chef: {{ $meal->chef->name ?? '—' }}
                        </p>
                        <div class="mb-2 text-warning small">
                            @php $avg = $meal->averageRating(); @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $avg ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                            <span class="text-muted ms-1">({{ $meal->ratings_count ?? $meal->ratings->count() }})</span>
                        </div>

                        <strong class="mb-2">{{ $meal->price }} $</strong>

                        <a href="{{ route('meals.show', $meal->id) }}" class="btn btn-outline-primary mt-auto">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p>No meals found.</p>
        @endforelse

    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $meals->links() }}
    </div>
@endsection
