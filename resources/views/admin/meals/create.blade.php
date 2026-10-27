<x-app-layout>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">{{ __('Create New Meal') }}</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.meals.store') }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Chef --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Chef') }}</label>
                                <select name="chef_id" class="form-select" required>
                                    <option value="">{{ __('Select Chef') }}</option>
                                    @foreach ($chefs as $chef)
                                        <option value="{{ $chef->id }}" @selected(old('chef_id') == $chef->id)>
                                            {{ $chef->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Category --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Category') }}</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- {{ __('None') }} --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Meal Name') }}</label>
                                <input type="text" name="name" class="form-control" placeholder="{{ __('Enter meal name') }}" value="{{ old('name') }}" required>
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label class="form-label">{{ __('Description') }}</label>
                                <textarea name="description" rows="4" class="form-control" placeholder="{{ __('Meal description') }}">{{ old('description') }}</textarea>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('Price') }}</label>
                                    <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('Number of Persons') }}</label>
                                    <input type="number" name="number_persons" min="1" max="50" class="form-control" value="{{ old('number_persons', 1) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('Prep Time (Minutes)') }}</label>
                                    <input type="number" name="prep_time_minutes" min="1" max="1440" class="form-control" value="{{ old('prep_time_minutes') }}">
                                </div>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_available" id="admin_is_available" value="1" {{ old('is_available', '1') ? 'checked' : '' }}>
                                <label class="form-check-label ms-2" for="admin_is_available">
                                    {{ __('Available for Ordering') }}
                                </label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Meal Image') }}</label>
                                <input type="file" name="image" class="form-control" required accept="image/*">
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('dashboard.meals.index') }}" class="btn btn-secondary">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create Meal') }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
