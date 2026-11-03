@extends('layouts.frontend')

@section('content')
{{-- ================= MOBILE VIEW (<768px) ================= --}}
<div class="d-md-none has-bottom-bar px-3 pt-3 pb-5" style="background: #0a0a0a; min-height: 100vh;">
    <!-- Mobile Top Bar -->
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('chef.dashboard') }}" class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle border border-subtle text-secondary" style="width: 40px; height: 40px; background: #151515;">
            <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
        </a>
        <div>
            <h1 class="h5 fw-bold text-light mb-0">{{ __('Kitchen Settings') }}</h1>
            <small class="text-secondary" style="font-size: 0.75rem;">{{ __('Update your kitchen brand and info') }}</small>
        </div>
    </div>

    <!-- Mobile Form Card -->
    <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-4" style="background: var(--surface-1);">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <!-- Avatar Section -->
            <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom border-secondary border-opacity-10">
                <div class="position-relative flex-shrink-0">
                    @php
                        auth()->user()->loadMissing('image');
                        $chefAuthHasImage = auth()->user()->relationLoaded('image') && auth()->user()->image && auth()->user()->image->image_path;
                    @endphp
                    @if($chefAuthHasImage)
                        <img src="{{ asset('storage/' . auth()->user()->image->image_path) }}" class="rounded-circle shadow-sm border border-primary border-opacity-30" style="width: 64px; height: 64px; object-fit: cover;">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary border border-secondary border-opacity-20" style="width: 64px; height: 64px; background: var(--surface-2);">
                            <i class="fas fa-hat-chef fa-lg"></i>
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1" style="min-width: 0;">
                    <label class="form-label fw-semibold text-light small mb-1">{{ __('Profile / Kitchen Image') }}</label>
                    <input type="file" name="image" class="form-control form-control-sm text-light border-secondary border-opacity-25" style="background: var(--surface-2); font-size: 16px;" accept="image/*">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-light small">{{ __('Kitchen Name') }}</label>
                <input type="text" name="kitchen_name" class="form-control text-light border-secondary border-opacity-25"
                    style="background: var(--surface-2); font-size: 16px;" value="{{ old('kitchen_name', auth()->user()->kitchen_name ?? auth()->user()->name) }}" placeholder="{{ __('e.g. Mama\'s Kitchen') }}">
                <div class="form-text text-secondary small mt-1" style="font-size: 0.72rem;">{{ __('This will be displayed as your brand name.') }}</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-light small">{{ __('Chef Name') }}</label>
                <input type="text" name="name" class="form-control text-light border-secondary border-opacity-25"
                    style="background: var(--surface-2); font-size: 16px;" value="{{ old('name', auth()->user()->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-light small">{{ __('Bio / About') }}</label>
                <textarea name="bio" class="form-control text-light border-secondary border-opacity-25"
                    style="background: var(--surface-2); font-size: 16px;" rows="3" placeholder="{{ __('Tell customers about your cooking style...') }}">{{ old('bio', auth()->user()->bio) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-light small">{{ __('Phone Number') }}</label>
                <input type="text" name="phone" class="form-control text-light border-secondary border-opacity-25"
                    style="background: var(--surface-2); font-size: 16px;" value="{{ old('phone', auth()->user()->phone) }}" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold text-light small">{{ __('Address / Location') }}</label>
                <input type="text" name="address" class="form-control text-light border-secondary border-opacity-25"
                    style="background: var(--surface-2); font-size: 16px;" value="{{ old('address', auth()->user()->address) }}" placeholder="{{ __('City, Area') }}">
            </div>

            <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg" style="color: #1b0e03 !important;">
                {{ __('Save Changes') }}
            </button>
        </form>
    </div>
</div>

{{-- ================= DESKTOP VIEW (≥768px, UNTOUCHED) ================= --}}
<div class="container py-5 d-none d-md-block">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <div class="card p-4 p-md-5 rounded-4 border border-secondary border-opacity-10 shadow-lg" style="background: var(--surface-1);">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-10">
                    <div>
                        <h2 class="h4 fw-bold text-light mb-1">{{ __('Kitchen Settings') }}</h2>
                        <p class="text-secondary small mb-0">{{ __('Update your kitchen brand, contact info, and bio') }}</p>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Avatar Section -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="position-relative flex-shrink-0">
                            @php
                                auth()->user()->loadMissing('image');
                                $chefAuthHasImage = auth()->user()->relationLoaded('image') && auth()->user()->image && auth()->user()->image->image_path;
                            @endphp
                            @if($chefAuthHasImage)
                                <img src="{{ asset('storage/' . auth()->user()->image->image_path) }}" class="rounded-circle shadow-sm border border-primary border-opacity-30" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary border border-secondary border-opacity-20" style="width: 80px; height: 80px; background: var(--surface-2);">
                                    <i class="fas fa-hat-chef fa-2x"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="form-label fw-semibold text-secondary small mb-1">{{ __('Profile / Kitchen Image') }}</label>
                            <input type="file" name="image" class="form-control form-control-sm text-light border-secondary border-opacity-25" style="background: var(--surface-2);" accept="image/*">
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light small">{{ __('Kitchen Name') }}</label>
                            <input type="text" name="kitchen_name" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" value="{{ old('kitchen_name', auth()->user()->kitchen_name ?? auth()->user()->name) }}" placeholder="{{ __('e.g. Mama\'s Kitchen') }}">
                            <div class="form-text text-secondary small mt-1">{{ __('This will be displayed as your brand name.') }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light small">{{ __('Chef Name') }}</label>
                            <input type="text" name="name" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold text-light small">{{ __('Bio / About') }}</label>
                            <textarea name="bio" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" rows="3" placeholder="{{ __('Tell customers about your cooking style...') }}">{{ old('bio', auth()->user()->bio) }}</textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light small">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" value="{{ old('phone', auth()->user()->phone) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-light small">{{ __('Address / Location') }}</label>
                            <input type="text" name="address" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" value="{{ old('address', auth()->user()->address) }}" placeholder="{{ __('City, Area') }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end pt-4 mt-3 border-top border-secondary border-opacity-10">
                        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-bold shadow-lg" data-magnetic>{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
