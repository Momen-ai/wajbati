<x-app-layout>
    <div class="mb-4">
        <x-ui.page-header
            :title="__('New Category')"
            :subtitle="__('Create a new dish category to organize meals on the platform.')">
            <x-slot name="actions">
                <a href="{{ route('dashboard.category.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i> {{ __('Back to Categories') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <div class="card shadow-card p-4 rounded-4" style="max-width: 680px;">
            <form action="{{ route('dashboard.category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="form-label text-main fw-semibold small">{{ __('Category Name') }} <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control bg-surface-2 text-main border-subtle"
                        placeholder="{{ __('Enter category name') }}" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">
                        <i class="fas fa-check me-1"></i> {{ __('Save') }}
                    </button>
                    <a href="{{ route('dashboard.category.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
