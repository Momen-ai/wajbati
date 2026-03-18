<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Wajbati') }} - {{ __('Forgot Password') }}</title>
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
                <h2 class="display-4 fw-bold text-light mb-3">{{ __('Password Reset') }}</h2>
                <p class="lead mb-0">{{ __('Regain access to your account quickly and securely.') }}</p>
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
                    <h3 class="fw-bold mb-3">{{ __('Forgot Password?') }}</h3>
                    <p class="text-light opacity-75">{{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success bg-success bg-opacity-10 text-success border border-success border-opacity-25 mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-floating mb-4">
                        <input id="email" class="form-control bg-secondary text-light border-secondary border-opacity-10 @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="{{ __('Email') }}" />
                        <label for="email" class="text-light">{{ __('Email') }}</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4">
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">{{ __('Back to login') }}</a>
                        <button class="btn btn-primary px-4 py-2 shadow" type="submit">
                            {{ __('Email Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
