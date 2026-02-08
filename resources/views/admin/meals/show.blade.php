<x-app-layout>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Meal Details</h5>
                    </div>

                    <div class="card-body">

                        @if ($meal->image)
                            <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="img-thumbnail"
                                style="max-width: 300px">
                        @else
                            <span class="text-muted">No image</span>
                        @endif


                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Meal Name:</div>
                            <div class="col-md-8">{{ $meal->name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Chef:</div>
                            <div class="col-md-8">{{ $meal->chef?->name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Category:</div>
                            <div class="col-md-8">
                                {{ $meal->category?->name ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Price:</div>
                            <div class="col-md-8">
                                <span class="badge bg-success">
                                    {{ number_format($meal->price, 2) }} $
                                </span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 fw-bold">Description:</div>
                            <div class="col-md-8">
                                {{ $meal->description ?? 'No description' }}
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dashboard.meals.index') }}" class="btn btn-secondary">
                                Back
                            </a>

                            <a href="{{ route('dashboard.meals.edit', $meal->id) }}" class="btn btn-primary">
                                Edit
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
