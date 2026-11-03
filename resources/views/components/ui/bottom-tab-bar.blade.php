@php
    $isChefContext = auth()->check() && auth()->user()->role === 'chef' && request()->routeIs('chef.*');
    $cartCount = count(session('cart', []));
    $hideOnRoutes = request()->routeIs('meals.show') || request()->routeIs('cart.index');
@endphp

@unless($hideOnRoutes)
<nav class="mobile-bottom-tab-bar d-md-none" aria-label="{{ __('Navigation') }}">
    @if($isChefContext)
        <!-- Chef Tabs -->
        <a href="{{ route('chef.dashboard') }}"
           class="mobile-tab-item {{ request()->routeIs('chef.dashboard') ? 'active' : '' }}"
           {!! request()->routeIs('chef.dashboard') ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-th-large"></i>
            <span>{{ __('Dashboard') }}</span>
        </a>

        <a href="{{ route('chef.orders.index') }}"
           class="mobile-tab-item {{ request()->routeIs('chef.orders.*') ? 'active' : '' }}"
           {!! request()->routeIs('chef.orders.*') ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-receipt"></i>
            <span>{{ __('Orders') }}</span>
        </a>

        <a href="{{ route('chef.meals.index') }}"
           class="mobile-tab-item {{ request()->routeIs('chef.meals.*') ? 'active' : '' }}"
           {!! request()->routeIs('chef.meals.*') ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-utensils"></i>
            <span>{{ __('Meals') }}</span>
        </a>

        <a href="{{ route('chef.earnings') }}"
           class="mobile-tab-item {{ request()->routeIs('chef.earnings') ? 'active' : '' }}"
           {!! request()->routeIs('chef.earnings') ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-wallet"></i>
            <span>{{ __('Earnings') }}</span>
        </a>

        <a href="{{ route('profile.edit') }}"
           class="mobile-tab-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"
           {!! request()->routeIs('profile.*') ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-user"></i>
            <span>{{ __('Account') }}</span>
        </a>
    @else
        <!-- Customer / Guest Tabs (Home, Meals, Chefs, Cart, Account) -->
        <a href="{{ route('home') }}"
           class="mobile-tab-item {{ request()->routeIs('home') ? 'active' : '' }}"
           {!! request()->routeIs('home') ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-home"></i>
            <span>{{ __('Home') }}</span>
        </a>

        <a href="{{ route('meals.index') }}"
           class="mobile-tab-item {{ request()->routeIs('meals.*') ? 'active' : '' }}"
           {!! request()->routeIs('meals.*') ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-utensils"></i>
            <span>{{ __('Meals') }}</span>
        </a>

        <a href="{{ route('chefs.index') }}"
           class="mobile-tab-item {{ request()->routeIs('chefs.*') ? 'active' : '' }}"
           {!! request()->routeIs('chefs.*') ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-users"></i>
            <span>{{ __('Chefs') }}</span>
        </a>

        <a href="{{ route('cart.index') }}"
           class="mobile-tab-item {{ request()->routeIs('cart.*') ? 'active' : '' }}"
           {!! request()->routeIs('cart.*') ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-shopping-bag"></i>
            @if($cartCount > 0)
                <span class="mobile-tab-badge" aria-live="polite">{{ $cartCount }}</span>
            @endif
            <span>{{ __('Cart') }}</span>
        </a>

        <a href="{{ auth()->check() ? route('profile.edit') : route('login') }}"
           class="mobile-tab-item {{ request()->routeIs('profile.*') || request()->routeIs('login') ? 'active' : '' }}"
           {!! (request()->routeIs('profile.*') || request()->routeIs('login')) ? 'aria-current="page"' : '' !!}>
            <i class="fas fa-user"></i>
            <span>{{ __('Account') }}</span>
        </a>
    @endif
</nav>
@endunless
