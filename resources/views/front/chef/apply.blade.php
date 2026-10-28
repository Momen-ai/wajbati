@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card p-4 p-md-5 rounded-4 border border-secondary border-opacity-10 shadow-lg" style="background: var(--surface-1);">
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-primary mb-3 border border-primary border-opacity-30"
                         style="width: 64px; height: 64px; background: var(--surface-2);">
                        <i class="fas fa-kitchen-set fa-2x"></i>
                    </div>
                    <h2 class="h3 fw-bold text-light mb-1">{{ __('Apply to Become a Chef') }}</h2>
                    <p class="text-secondary small mb-0">{{ __('Share your passion for home cooking with food lovers in your community.') }}</p>
                </div>

                @if($existing)
                    <div class="alert p-3 rounded-3 border border-warning border-opacity-25 bg-warning bg-opacity-10 text-warning text-center">
                        <i class="fas fa-clock me-2"></i> {{ __('You already have a pending chef application submitted on :date. We are reviewing it and will notify you soon.', ['date' => $existing->created_at->format('Y-m-d')]) }}
                    </div>
                @else
                    <form action="{{ route('chef.apply.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-light fw-semibold small">{{ __('Kitchen Name') }}</label>
                            <input type="text" name="kitchen_name" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" value="{{ old('kitchen_name') }}" placeholder="{{ __('e.g. Mama\'s Kitchen') }}" required>
                            @error('kitchen_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light fw-semibold small">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" value="{{ old('phone', auth()->user()->phone) }}" placeholder="{{ __('Enter phone number') }}" required>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light fw-semibold small">{{ __('Address / Location') }}</label>
                            <input type="text" name="address" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" value="{{ old('address') }}" placeholder="{{ __('City, Area') }}" required>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light fw-semibold small">{{ __('Bio / About Your Cooking') }}</label>
                            <textarea name="bio" rows="4" class="form-control text-light border-secondary border-opacity-25"
                                style="background: var(--surface-2);" placeholder="{{ __('Tell customers about your cooking style...') }}">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-lg" data-magnetic>
                            {{ __('Submit Application') }} <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
