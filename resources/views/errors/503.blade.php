@extends('layouts.frontend')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="position-relative mb-5">
            <h1 class="display-1 fw-900 opacity-25" style="letter-spacing: 20px;">503</h1>
            <div class="position-absolute top-50 start-50 translate-middle w-100">
                <i class="fas fa-tools fa-4x text-warning opacity-75"></i>
            </div>
        </div>
        <h2 class="fw-bold text-light mb-3">{{ __('Service Unavailable') }}</h2>
        <p class="text-muted mb-5 mx-auto" style="max-width: 500px;">{{ __('We are currently performing maintenance. We will be back shortly!') }}</p>
    </div>
</div>

<style>
    .display-1 { font-size: 8rem; }
</style>
@endsection
