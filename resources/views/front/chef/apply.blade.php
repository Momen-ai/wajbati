@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-card border border-secondary border-opacity-10 p-4">
                <div class="text-center mb-4">
                    <div class="rounded-circle bg-primary-custom d-inline-flex align-items-center justify-content-center text-light mb-3" style="width: 60px; height: 60px;">
                        <i class="fas fa-kitchen-set fa-2x"></i>
                    </div>
                    <h2 class="fw-bold text-light">{{ __('Apply to Become a Chef') }}</h2>
                    <p class="text-muted small">{{ __('Share your passion for home cooking with food lovers in your community.') }}</p>
                </div>

                @if($existing)
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-clock me-2"></i> {{ __('You already have a pending chef application submitted on :date. We are reviewing it and will notify you soon.', ['date' => $existing->created_at->format('Y-m-d')]) }}
                    </div>
                @else
                    <form action="{{ route('chef.apply.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">{{ __('Kitchen Name') }}</label>
                            <input type="text" name="kitchen_name" class="form-control bg-secondary text-light border-secondary border-opacity-10" value="{{ old('kitchen_name') }}" placeholder="{{ __('e.g. Mama\'s Kitchen') }}" required>
                            @error('kitchen_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" class="form-control bg-secondary text-light border-secondary border-opacity-10" value="{{ old('phone', auth()->user()->phone) }}" placeholder="{{ __('Enter phone number') }}" required>
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-light fw-bold">{{ __('Address / Location') }}</label>
                            <input type="text" name="address" class="form-control bg-secondary text-light border-secondary border-opacity-10" value="{{ old('address') }}" placeholder="{{ __('City, Area') }}" required>
                            @error('address')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-light fw-bold">{{ __('Bio / About Your Cooking') }}</label>
                            <textarea name="bio" rows="4" class="form-control bg-secondary text-light border-secondary border-opacity-10" placeholder="{{ __('Tell customers about your cooking style...') }}">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill shadow">{{ __('Submit Application') }}</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
