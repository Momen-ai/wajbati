<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Wajbati') }} - {{ __('Login') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
        .bg-primary-custom {
            background-color: #ff6b6b;
        }

        .text-primary {
            color: #ff6b6b !important;
        }

        .btn-primary {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
        }

        .btn-primary:hover {
            background-color: #fa5252;
            border-color: #fa5252;
        }
    </style>
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
            <div class="position-absolute bottom-0 start-0 p-5 text-white">
                <h2 class="display-4 fw-bold text-white mb-3">{{ __('Welcome Back!') }}</h2>
                <p class="lead mb-0">{{ __('Order your favorite homemade meals with just a few clicks.') }}</p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white">
            <div class="p-4 p-md-5 w-100" style="max-width: 500px;">
                <div class="d-flex align-items-center mb-5">
                    <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center text-dark">
                        <div class="rounded-circle bg-primary-custom d-flex align-items-center justify-content-center text-white me-2"
                            style="width:32px;height:32px">
                            <i class="fas fa-utensils small"></i>
                        </div>
                        <span class="fw-bold h5 m-0">{{ config('app.name', 'Wajbati') }}</span>
                    </a>
                </div>

                <div class="mb-4">
                    <h3 class="fw-bold">{{ __('Login') }}</h3>
                    <p class="text-muted">{{ __('Enter your details to access your account.') }}</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success mb-4" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="text" name="login" class="form-control @error('login') is-invalid @enderror"
                            id="loginInput" placeholder="{{ __('Email or Phone') }}" value="{{ old('login') }}"
                            required autofocus>
                        <label for="loginInput">{{ __('Email or Phone') }}</label>
                        @error('login')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" id="passwordInput"
                            placeholder="{{ __('Password') }}" required autocomplete="current-password">
                        <label for="passwordInput">{{ __('Password') }}</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                            <label class="form-check-label text-muted" for="rememberMe">
                                {{ __('Remember me') }}
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-primary fw-bold small text-decoration-none">{{ __('Forgot Password?') }}</a>
                        @endif
                    </div>

                    <button class="btn btn-primary w-100 py-3 mb-4 shadow-sm text-white"
                        type="submit">{{ __('Sign In') }}</button>

                    <div class="text-center">
                        <span class="text-muted">{{ __("Don't have an account?") }}</span>
                        <a href="{{ route('register') }}"
                            class="text-primary fw-bold text-decoration-none ms-1">{{ __('Register') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
