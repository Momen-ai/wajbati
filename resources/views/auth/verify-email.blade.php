<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Wajbati') }} - {{ __('Verify Email') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

    <div class="auth-wrapper row g-0" style="min-height: 100vh;">
        <!-- Left Side: Image -->
        <div class="col-lg-6 d-none d-lg-block position-relative">
            <div class="auth-image w-100 h-100"
                style="background-image: url('{{ asset('assets/images/hero-plate.png') }}'); background-size: cover; background-position: center;">
            </div>
            <div class="position-absolute top-0 start-0 w-100 h-100"
                style="background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.6));"></div>
            <div class="position-absolute bottom-0 start-0 p-5 text-light">
                <h2 class="display-4 fw-bold text-light mb-3">{{ __('Verify Your Email') }}</h2>
                <p class="lead mb-0">{{ __('Just one more step to unlock your account.') }}</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-darker text-light">
            <div class="p-4 p-md-5 w-100" style="max-width: 500px;">
                <div class="d-flex align-items-center mb-5">
                    <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center text-light">
                        <div class="rounded-circle bg-primary-custom d-flex align-items-center justify-content-center text-light me-2"
                            style="width:32px;height:32px">
                            <i class="fas fa-utensils small"></i>
                        </div>
                        <span class="fw-bold h5 m-0">{{ config('app.name', 'Wajbati') }}</span>
                    </a>
                </div>

                <div class="mb-4">
                    <h3 class="fw-bold mb-3">{{ __('Almost there!') }}</h3>
                    <p class="text-light opacity-75">
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                    </p>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success bg-success bg-opacity-10 text-success border border-success border-opacity-25 mb-4" role="alert">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="d-flex align-items-center justify-content-between mt-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary px-4 py-2 shadow border-0">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-link text-light text-decoration-none opacity-75 hover-opacity-100">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
