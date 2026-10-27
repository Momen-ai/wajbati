<x-app-layout>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">{{ __('Meal Details') }}</h5>
                    </div>

                    <div class="card-body">

                        @if ($meal->image)
                            <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="img-thumbnail"
                                style="max-width: 300px">
                        @else
                            <span class="text-muted">{{ __('No image') }}</span>
                        @endif


                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{ __('Meal Name') }}:</div>
                            <div class="col-md-8">{{ $meal->name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{ __('Chef') }}:</div>
                            <div class="col-md-8">{{ $meal->chef?->name }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{ __('Category') }}:</div>
                            <div class="col-md-8">
                                {{ $meal->category?->name ?? '-' }}
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">{{ __('Price') }}:</div>
                            <div class="col-md-8">
                                <span class="badge bg-success">
                                    {{ money($meal->price) }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4 fw-bold">{{ __('Description') }}:</div>
                            <div class="col-md-8">
                                {{ $meal->description ?? __('No description') }}
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dashboard.meals.index') }}" class="btn btn-secondary">
                                {{ __('Back') }}
                            </a>

                            <a href="{{ route('dashboard.meals.edit', $meal->id) }}" class="btn btn-primary">
                                {{ __('Edit') }}
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
