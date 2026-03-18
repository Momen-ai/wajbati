@extends('layouts.frontend')

@section('content')
    <!-- Page Content -->
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-light">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('meals.index') }}"
                        class="text-decoration-none text-light">Meals</a></li>
                <li class="breadcrumb-item active text-light" aria-current="page">{{ $meal->name }}</li>
            </ol>
        </nav>

        <div class="row g-5">
            <!-- Left Column: Images -->
            <div class="col-lg-6">
                <div class="position-relative overflow-hidden rounded-3 shadow-sm mb-3" style="aspect-ratio:4/3;">
                    @if ($meal->image)
                        <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="w-100 h-100 object-fit-cover"
                            alt="{{ $meal->name }}">
                    @else
                        <img src="https://placehold.co/800x600?text=No+Image" class="w-100 h-100 object-fit-cover"
                            alt="{{ $meal->name }}">
                    @endif
                </div>
            </div>

            <!-- Right Column: Details -->
            <div class="col-lg-6">
                <div class="ps-lg-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h1 class="display-6 fw-bold mb-0">{{ $meal->name }}</h1>
                        <div class="text-end">
                            <h3 class="text-primary fw-bold mb-0">${{ $meal->price }}</h3>
                            <small class="text-light">per order</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <span class="badge bg-secondary text-light border ms-0">{{ $meal->category->name ?? 'General' }}</span>
                    </div>

                    <!-- Chef Info -->
                    <div class="card bg-secondary text-light border border-secondary border-opacity-10 p-3 mb-4 rounded-3 d-flex flex-row align-items-center">
                        <div class="bg-darker text-light rounded-circle p-1 me-3 shadow">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-light"
                                style="width:48px;height:48px"><i class="fas fa-user-chef"></i></div>
                        </div>
                        <div>
                            <small class="text-light d-block text-uppercase fw-bold"
                                style="font-size:0.7rem; letter-spacing:1px">Prepared by</small>
                            <h6 class="mb-0 fw-bold">{{ $meal->chef->name ?? 'Unknown Chef' }}</h6>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold mb-2">Description</h5>
                        <p class="text-light">{{ $meal->description }}</p>
                    </div>

                    <!-- Add to Cart Box -->
                    <div class="card shadow-card p-4 border border-secondary border-opacity-10">
                        <h5 class="fw-bold mb-3 text-light">Order Now</h5>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="meal_id" value="{{ $meal->id }}">

                            <div class="d-flex align-items-end gap-3 mb-3">
                                <div class="flex-grow-1">
                                    <label class="form-label text-light small">Quantity</label>
                                    <div class="input-group">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="this.nextElementSibling.stepDown()"><i
                                                class="fas fa-minus"></i></button>
                                        <input type="number" name="quantity" value="1" min="1"
                                            class="form-control bg-secondary text-light border-secondary text-center" style="max-width:80px">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="this.previousElementSibling.stepUp()"><i
                                                class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary btn-lg shadow-sm">
                                    <i class="fas fa-cart-plus me-2"></i> Add to Cart
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <hr class="my-5 opacity-25">

                <!-- Ratings & Reviews Section -->
                <div class="mb-5">
                    <h4 class="fw-bold mb-4 text-light">Reviews ({{ $meal->ratings_count ?? $meal->ratings->count() }})</h4>

                    <!-- Review Form -->
                    @auth
                        <div class="card shadow-card border border-secondary border-opacity-10 mb-5">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3 text-light">Write a Review</h5>
                                <form action="{{ route('meals.rate', $meal->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-light">Rating</label>
                                        <div class="d-flex gap-2">
                                            @for ($i = 5; $i >= 1; $i--)
                                                <input type="radio" class="btn-check" name="star"
                                                    id="star{{ $i }}" value="{{ $i }}"
                                                    {{ $i == 5 ? 'checked' : '' }}>
                                                <label class="btn btn-outline-warning btn-sm rounded-pill"
                                                    for="star{{ $i }}">{{ $i }} <i
                                                        class="fas fa-star"></i></label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-light">Your Review</label>
                                        <textarea name="body" class="form-control bg-secondary text-light border-secondary border-opacity-10" rows="3" placeholder="Share your experience..."></textarea>
                                    </div>
                                    <button class="btn btn-primary px-4 rounded-pill">Submit Review</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary text-light border border-secondary border-opacity-10 mb-5">
                            Please <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none">login</a> to write a review.
                        </div>
                    @endauth

                    <!-- Reviews List -->
                    <div class="reviews-list">
                        @forelse($meal->ratings as $rating)
                            <div class="d-flex mb-4 border-bottom pb-4">
                                <div class="bg-secondary text-light rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-light"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        <h6 class="fw-bold mb-0 me-2">{{ $rating->user->name ?? 'User' }}</h6>
                                        <small class="text-light">{{ $rating->created_at->diffForHumans() }}</small>
                                    </div>
                                    <div class="mb-2 text-warning small">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i class="{{ $i <= $rating->star ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0 text-light">{{ $rating->body }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-light">No reviews yet. Be the first to review!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Meals -->
        @if ($relatedMeals->count() > 0)
            <div class="row mt-5 pt-5 border-top border-secondary border-opacity-10">
                <div class="col-12 mb-4">
                    <h3 class="fw-bold text-light">You might also like</h3>
                </div>
                @foreach ($relatedMeals as $related)
                    <div class="col-md-3">
                        <div class="card shadow-card border border-secondary border-opacity-10 h-100 meal-card">
                            <div class="meal-card-img-wrapper" style="height: 180px;">
                                <a href="{{ route('meals.show', $related->id) }}">
                                    @if ($related->image)
                                        <img src="{{ asset('storage/' . $related->image->image_path) }}"
                                            alt="{{ $related->name }}">
                                    @else
                                        <img src="https://placehold.co/400x300?text=No+Image" alt="{{ $related->name }}">
                                    @endif
                                </a>
                            </div>
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-1 text-light text-truncate">{{ $related->name }}</h6>
                                <small class="text-primary fw-bold">${{ $related->price }}</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection
