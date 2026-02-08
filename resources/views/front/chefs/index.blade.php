@extends('layouts.frontend')

@section('content')
    <div class="bg-light py-5 mb-5 border-bottom">
        <div class="container text-center">
            <h1 class="fw-bold mb-3">Meet Our Chefs</h1>
            <p class="lead text-muted mx-auto" style="max-width: 600px;">
                Discover the talented families behind the delicious homemade meals. Each kitchen has a story and a unique
                taste.
            </p>
        </div>
    </div>

    <div class="container mb-5">
        <div class="row g-4">
            @forelse($chefs as $chef)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card shadow-card border-0 h-100 text-center p-4">
                        <div class="position-relative mx-auto mb-3">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto shadow-sm"
                                style="width:100px; height:100px;">
                                <!-- Check if user has image or use placeholder -->
                                <i class="fas fa-user-chef fa-3x text-secondary"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-1">{{ $chef->name }}</h5>
                        <p class="text-muted small mb-2"><i class="fas fa-map-marker-alt me-1"></i>
                            {{ $chef->address ?? 'Local Chef' }}</p>

                        <a href="{{ route('meals.index', ['chef' => $chef->id]) }}"
                            class="btn btn-outline-primary rounded-pill w-100">View Menu</a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No family kitchens found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $chefs->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
