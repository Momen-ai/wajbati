<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Wajbati') }} - {{ __('Reset Password') }}</title>
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
                <h2 class="display-4 fw-bold text-light mb-3">{{ __('Secure Your Account') }}</h2>
                <p class="lead mb-0">{{ __('Create a new, strong password.') }}</p>
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
                    <h3 class="fw-bold mb-2">{{ __('Reset Password') }}</h3>
                    <p class="text-light opacity-75">{{ __('Please enter your new password below.') }}</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="form-floating mb-3">
                        <input id="email" class="form-control bg-secondary text-light border-secondary border-opacity-10 @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="{{ __('Email') }}" />
                        <label for="email" class="text-light">{{ __('Email') }}</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input id="password" class="form-control bg-secondary text-light border-secondary border-opacity-10 @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}" />
                        <label for="password" class="text-light">{{ __('Password') }}</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-4">
                        <input id="password_confirmation" class="form-control bg-secondary text-light border-secondary border-opacity-10 @error('password_confirmation') is-invalid @enderror" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}" />
                        <label for="password_confirmation" class="text-light">{{ __('Confirm Password') }}</label>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-end mt-4">
                        <button class="btn btn-primary px-4 py-3 w-100 shadow rounded-3" type="submit">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
