<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Wajbati') }} - {{ __('Register') }}</title>
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
                <h2 class="display-4 fw-bold text-light mb-3">{{ __('Join Our Community') }}</h2>
                <p class="lead mb-0">
                    {{ __('Discover authentic homemade flavors or start your own kitchen journey with us.') }}</p>
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
                    <h3 class="fw-bold">{{ __('Create Account') }}</h3>
                    <p class="text-light">{{ __('Fill in your details to get started.') }}</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="nameInput" placeholder="{{ __('Full Name') }}" value="{{ old('name') }}" required
                            autofocus>
                        <label for="nameInput">{{ __('Full Name') }}</label>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            id="emailInput" placeholder="Email Address" value="{{ old('email') }}" required>
                        <label for="emailInput">{{ __('Email Address') }}</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            id="phoneInput" placeholder="{{ __('Phone Number') }}" value="{{ old('phone') }}"
                            required>
                        <label for="phoneInput">{{ __('Phone Number') }}</label>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" id="passwordInput"
                            placeholder="{{ __('Password') }}" required autocomplete="new-password">
                        <label for="passwordInput">{{ __('Password') }}</label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            id="confirmPasswordInput" placeholder="{{ __('Confirm Password') }}" required
                            autocomplete="new-password">
                        <label for="confirmPasswordInput">{{ __('Confirm Password') }}</label>
                    </div>

                    <div class="form-floating mb-4">
                        <select name="role" class="form-select" id="roleSelect" aria-label="Role select">
                            <option value="user">{{ __('Customer') }}</option>
                            <option value="chef">{{ __('Family (Chef)') }}</option>
                        </select>
                        <label for="roleSelect">{{ __('I want to join as') }}</label>
                    </div>

                    <button class="btn btn-primary w-100 py-3 mb-4 shadow text-light"
                        type="submit">{{ __('Sign Up') }}</button>

                    <div class="text-center">
                        <span class="text-light">{{ __('Already have an account?') }}</span>
                        <a href="{{ route('login') }}"
                            class="text-primary fw-bold text-decoration-none ms-1">{{ __('Login') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
