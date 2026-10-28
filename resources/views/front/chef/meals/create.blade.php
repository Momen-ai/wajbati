@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <div class="card p-4 p-md-5 rounded-4 border border-secondary border-opacity-10 shadow-lg" style="background: var(--surface-1);">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-10">
                    <div>
                        <h2 class="h4 fw-bold text-light mb-1">{{ __('Add New Meal') }}</h2>
                        <p class="text-secondary small mb-0">{{ __('Create a new homemade dish for your kitchen menu') }}</p>
                    </div>
                </div>

                <form action="{{ route('chef.meals.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-light small">{{ __('Meal Name') }}</label>
                        <input type="text" name="name" class="form-control text-light border-secondary border-opacity-25"
                            style="background: var(--surface-2);" required placeholder="{{ __('e.g. Traditional Mandi') }}" value="{{ old('name') }}">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light small">{{ __('Price') }}</label>
                            <input type="number" step="0.01" min="0" name="price" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" required placeholder="0.00" value="{{ old('price') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light small">{{ __('Category') }}</label>
                            <select name="category_id" class="form-select text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" required>
                                <option value="" disabled selected>{{ __('Select Category') }}</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light small">{{ __('Number of Persons') }}</label>
                            <input type="number" name="number_persons" min="1" max="50" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" placeholder="1" value="{{ old('number_persons', 1) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light small">{{ __('Prep Time (Minutes)') }}</label>
                            <input type="number" name="prep_time_minutes" min="1" max="1440" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" placeholder="30" value="{{ old('prep_time_minutes') }}">
                        </div>
                    </div>

                    <div class="mb-3 form-check form-switch p-3 rounded-3 border border-secondary border-opacity-10" style="background: var(--surface-2);">
                        <input class="form-check-input ms-0 me-2" type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', '1') ? 'checked' : '' }}>
                        <label class="form-check-label text-light fw-medium small mb-0" for="is_available">
                            {{ __('Available for Ordering') }}
                        </label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-light small">{{ __('Description') }}</label>
                        <textarea name="description" class="form-control text-light border-secondary border-opacity-25"
                            style="background: var(--surface-2);" rows="4" placeholder="{{ __('Describe the ingredients and portion size...') }}">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-light small">{{ __('Meal Image') }}</label>
                        <input type="file" name="image" class="form-control text-light border-secondary border-opacity-25"
                            style="background: var(--surface-2);" accept="image/*" required>
                        <div class="form-text text-secondary small mt-1">{{ __('Supported formats: jpg, jpeg, png, webp. Max size: 2MB.') }}</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2">
                        <a href="{{ route('chef.meals.index') }}" class="btn btn-outline-secondary border-opacity-25 text-light rounded-pill px-4">{{ __('Cancel') }}</a>
                        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-lg" data-magnetic>{{ __('Save Meal') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
