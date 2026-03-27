@extends('layouts.frontend')

@section('content')
<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="text-center">
        <div class="position-relative mb-5">
            <h1 class="display-1 fw-900 opacity-25" style="letter-spacing: 20px;">500</h1>
            <div class="position-absolute top-50 start-50 translate-middle w-100">
                <i class="fas fa-fire fa-4x text-danger opacity-75"></i>
            </div>
        </div>
        <h2 class="fw-bold text-light mb-3">Kitchen Fire! Server Error</h2>
        <p class="text-muted mb-5 mx-auto" style="max-width: 500px;">Something went wrong on our end. Our chefs are working hard to put out the fire and get the kitchen back in order.</p>
        <div class="d-flex gap-3 justify-content-center">
            <button onclick="location.reload()" class="btn btn-primary rounded-pill px-5 py-2">
                <i class="fas fa-redo me-2"></i>Try Refreshing
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-primary rounded-pill px-5 py-2">
                Back to Safety
            </a>
        </div>
    </div>
</div>

<style>
    .display-1 { font-size: 8rem; }
    .display-1 { color: #f21b3f; }
</style>
@endsection
