@extends('layouts.frontend')

@section('content')
<div class="container py-5">
  <div class="row align-items-stretch g-4 g-lg-5">
    
    <div class="col-lg-5">
        <div class="pe-lg-4">
            <span class="badge rounded-pill px-3 py-1 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 fw-bold mb-3">
                {{ __('Contact Us') }}
            </span>
            <h1 class="display-6 fw-bold mb-3 text-light">{{ __('Get in touch with our friendly team') }}</h1>
            <p class="text-secondary mb-5">{{ __("We'd love to hear from you! Please fill out the form or reach out to us using the contact details.") }}</p>

            <div class="d-flex align-items-center mb-4 p-3 rounded-4 border border-secondary border-opacity-10" style="background: var(--surface-1);">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary me-3 flex-shrink-0" style="width: 48px; height: 48px; background: var(--surface-2);">
                    <i class="fas fa-envelope fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-light">{{ __('Email') }}</h6>
                    <p class="text-secondary mb-0 small">{{ config('mail.from.address', 'contact@wajbati.com') }}</p>
                </div>
            </div>
            
            <div class="d-flex align-items-center mb-4 p-3 rounded-4 border border-secondary border-opacity-10" style="background: var(--surface-1);">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary me-3 flex-shrink-0" style="width: 48px; height: 48px; background: var(--surface-2);">
                    <i class="fas fa-phone-alt fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-light">{{ __('Phone') }}</h6>
                    <p class="text-secondary mb-0 small">+970 599 000 000</p>
                </div>
            </div>

            <div class="d-flex align-items-center mb-5 p-3 rounded-4 border border-secondary border-opacity-10" style="background: var(--surface-1);">
                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary me-3 flex-shrink-0" style="width: 48px; height: 48px; background: var(--surface-2);">
                    <i class="fas fa-map-marker-alt fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-light">{{ __('Office') }}</h6>
                    <p class="text-secondary mb-0 small">{{ __('Gaza, Palestine') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card p-4 p-md-5 rounded-4 border border-secondary border-opacity-10 shadow-lg h-100" style="background: var(--surface-1);">
            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-light fw-semibold small">{{ __('Name') }}</label>
                        <input name="name" class="form-control text-light border-secondary border-opacity-25" style="background: var(--surface-2);" placeholder="{{ __('Your name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-light fw-semibold small">{{ __('Email') }}</label>
                        <input name="email" type="email" class="form-control text-light border-secondary border-opacity-25" style="background: var(--surface-2);" placeholder="{{ __('you@company.com') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-light fw-semibold small">{{ __('Subject') }}</label>
                        <input name="subject" class="form-control text-light border-secondary border-opacity-25" style="background: var(--surface-2);" placeholder="{{ __('How can we help?') }}">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label text-light fw-semibold small">{{ __('Message') }}</label>
                        <textarea name="message" class="form-control text-light border-secondary border-opacity-25" style="background: var(--surface-2);" rows="5" placeholder="{{ __('Tell us more...') }}" required></textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-lg" type="submit" data-magnetic>{{ __('Send Message') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

  </div>
</div>
@endsection
