@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-card border border-secondary border-opacity-10">
                <div class="card-header bg-card border-bottom border-secondary border-opacity-10 pt-4 px-4">
                    <h4 class="fw-bold mb-0 text-light">{{ __('Edit Meal') }}: {{ $meal->name }}</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('chef.meals.update', $meal->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-light">{{ __('Meal Name') }}</label>
                            <input type="text" name="name" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ old('name', $meal->name) }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">{{ __('Price') }}</label>
                                <input type="number" step="0.01" min="0" name="price" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ old('price', $meal->price) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">{{ __('Category') }}</label>
                                <select name="category_id" class="form-select bg-secondary text-light border border-secondary border-opacity-10" required>
                                    <option value="" disabled>{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $meal->category_id) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">{{ __('Number of Persons') }}</label>
                                <input type="number" name="number_persons" min="1" max="50" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ old('number_persons', $meal->number_persons ?? 1) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">{{ __('Prep Time (Minutes)') }}</label>
                                <input type="number" name="prep_time_minutes" min="1" max="1440" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ old('prep_time_minutes', $meal->prep_time_minutes) }}">
                            </div>
                        </div>

                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_available" id="is_available" value="1" {{ old('is_available', $meal->is_available) ? 'checked' : '' }}>
                            <label class="form-check-label text-light fw-bold ms-2" for="is_available">
                                {{ __('Available for Ordering') }}
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-light">{{ __('Description') }}</label>
                            <textarea name="description" class="form-control bg-secondary text-light border border-secondary border-opacity-10" rows="4">{{ old('description', $meal->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-light">{{ __('Meal Image') }}</label>
                            @if($meal->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $meal->image->image_path) }}" alt="{{ $meal->name }}" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            @endif
                            <input type="file" name="image" class="form-control bg-secondary text-light border border-secondary border-opacity-10" accept="image/*">
                            <div class="form-text text-light opacity-75">{{ __('Leave empty to keep current image.') }}</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('chef.meals.index') }}" class="btn btn-secondary text-light">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow">{{ __('Update Meal') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
