<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header :title="__('Edit Meal')">
            <x-slot name="actions">
                <a href="{{ route('dashboard.meals.index') }}" class="btn btn-outline-subtle rounded-pill px-4 text-secondary">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i> {{ __('Back to List') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <div class="spotlight-card rounded-4 p-4 p-md-5" style="max-width: 900px;">
            <form method="POST" action="{{ route('dashboard.meals.update', $meal->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Chef --}}
                <div class="mb-4">
                    <label class="form-label text-main fw-semibold">{{ __('Chef') }}</label>
                    <select name="chef_id" class="form-select bg-surface-2 text-main border-subtle" required>
                        <option value="">{{ __('Select Chef') }}</option>
                        @foreach ($chefs as $chef)
                            <option value="{{ $chef->id }}" @selected(old('chef_id', $meal->chef_id) == $chef->id)>
                                {{ $chef->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Category --}}
                <div class="mb-4">
                    <label class="form-label text-main fw-semibold">{{ __('Category') }}</label>
                    <select name="category_id" class="form-select bg-surface-2 text-main border-subtle">
                        <option value="">-- {{ __('None') }} --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $meal->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Name --}}
                <div class="mb-4">
                    <label class="form-label text-main fw-semibold">{{ __('Meal Name') }}</label>
                    <input type="text" name="name" class="form-control bg-surface-2 text-main border-subtle" value="{{ old('name', $meal->name) }}" required>
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label class="form-label text-main fw-semibold">{{ __('Description') }}</label>
                    <textarea name="description" rows="4" class="form-control bg-surface-2 text-main border-subtle" placeholder="{{ __('Meal description') }}">{{ old('description', $meal->description) }}</textarea>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label text-main fw-semibold">{{ __('Price') }}</label>
                        <input type="number" step="0.01" min="0" name="price" class="form-control bg-surface-2 text-main border-subtle"
                            value="{{ old('price', $meal->price) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-main fw-semibold">{{ __('Number of Persons') }}</label>
                        <input type="number" name="number_persons" min="1" max="50" class="form-control bg-surface-2 text-main border-subtle"
                            value="{{ old('number_persons', $meal->number_persons ?? 1) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-main fw-semibold">{{ __('Prep Time (Minutes)') }}</label>
                        <input type="number" name="prep_time_minutes" min="1" max="1440" class="form-control bg-surface-2 text-main border-subtle"
                            value="{{ old('prep_time_minutes', $meal->prep_time_minutes) }}">
                    </div>
                </div>

                <div class="mb-4 form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_available" id="admin_edit_is_available" value="1" {{ old('is_available', $meal->is_available) ? 'checked' : '' }}>
                    <label class="form-check-label ms-2 text-main" for="admin_edit_is_available">
                        {{ __('Available for Ordering') }}
                    </label>
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label text-main fw-semibold">{{ __('Image') }}</label>
                    @php $meal->loadMissing('image'); @endphp
                    @if($meal->relationLoaded('image') && $meal->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $meal->image->image_path) }}" alt="{{ $meal->name }}" class="rounded-3 border border-subtle" style="width: 80px; height: 80px; object-fit: cover;">
                    </div>
                    @endif
                    <input type="file" id="image" name="image" class="form-control bg-surface-2 text-main border-subtle" accept="image/*">
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-end gap-2 pt-3 border-top border-subtle">
                    <a href="{{ route('dashboard.meals.index') }}" class="btn btn-outline-subtle rounded-pill px-4 text-secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold shadow-sm">
                        {{ __('Update Meal') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
