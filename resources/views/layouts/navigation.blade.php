<!-- Mobile Sidebar Backdrop -->
<div x-show="sidebarOpen"
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="position-fixed top-0 start-0 w-100 h-100 bg-black bg-opacity-75 d-lg-none"
     style="z-index: 1040; backdrop-filter: blur(4px);"
     x-cloak>
</div>

<!-- Sidebar Container -->
<aside :class="{ 'show-drawer': sidebarOpen }"
       class="app-sidebar flex-column flex-shrink-0 bg-surface-1 border-end border-subtle d-flex"
       id="adminSidebar">

    <!-- Sidebar Brand -->
    <div class="p-3 border-bottom border-subtle d-flex align-items-center justify-content-between">
        <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                 style="width: 38px; height: 38px;">
                <i class="fas fa-utensils"></i>
            </div>
            <span class="fs-5 fw-bold text-primary tracking-tight">{{ __('Wajbati') }}</span>
        </a>

        <!-- Mobile Close Button -->
        <button type="button" @click="sidebarOpen = false" class="btn btn-sm text-secondary d-lg-none" aria-label="Close sidebar">
            <i class="fas fa-times fs-5"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <div class="p-3 flex-grow-1 overflow-y-auto">
        <div class="text-secondary small fw-bold text-uppercase px-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
            {{ __('Navigation') }}
        </div>

        <nav class="nav nav-pills flex-column gap-1">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-main {{ request()->routeIs('dashboard') ? 'bg-primary-soft text-primary fw-bold border-start border-3 border-primary' : 'hover-bg-surface-2' }}">
                <i class="fas fa-chart-line text-primary" style="width: 20px;"></i>
                <span>{{ __('Dashboard') }}</span>
            </a>

            <!-- Categories -->
            <a href="{{ route('dashboard.category.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-main {{ request()->routeIs('dashboard.category.*') ? 'bg-primary-soft text-primary fw-bold border-start border-3 border-primary' : 'hover-bg-surface-2' }}">
                <i class="fas fa-th-large text-primary" style="width: 20px;"></i>
                <span>{{ __('Categories') }}</span>
            </a>

            <!-- Meals -->
            <a href="{{ route('dashboard.meals.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-main {{ request()->routeIs('dashboard.meals.*') ? 'bg-primary-soft text-primary fw-bold border-start border-3 border-primary' : 'hover-bg-surface-2' }}">
                <i class="fas fa-hamburger text-primary" style="width: 20px;"></i>
                <span>{{ __('Meals') }}</span>
            </a>

            <!-- Orders -->
            <a href="{{ route('dashboard.orders.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-main {{ request()->routeIs('dashboard.orders.*') ? 'bg-primary-soft text-primary fw-bold border-start border-3 border-primary' : 'hover-bg-surface-2' }}">
                <i class="fas fa-shopping-bag text-primary" style="width: 20px;"></i>
                <span>{{ __('Orders') }}</span>
            </a>

            <!-- Ratings -->
            <a href="{{ route('dashboard.ratings.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-main {{ request()->routeIs('dashboard.ratings.*') ? 'bg-primary-soft text-primary fw-bold border-start border-3 border-primary' : 'hover-bg-surface-2' }}">
                <i class="fas fa-star text-primary" style="width: 20px;"></i>
                <span>{{ __('Rating') }}</span>
            </a>

            <!-- Contacts -->
            <a href="{{ route('dashboard.contacts.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-main {{ request()->routeIs('dashboard.contacts.*') ? 'bg-primary-soft text-primary fw-bold border-start border-3 border-primary' : 'hover-bg-surface-2' }}">
                <i class="fas fa-envelope text-primary" style="width: 20px;"></i>
                <span>{{ __('Contacts') }}</span>
            </a>

            @auth
                @if (auth()->user()->role === 'admin')
                    <div class="text-secondary small fw-bold text-uppercase px-3 mt-3 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                        {{ __('Administration') }}
                    </div>

                    <!-- Users -->
                    <a href="{{ route('dashboard.users.index') }}"
                       class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-main {{ request()->routeIs('dashboard.users.*') ? 'bg-primary-soft text-primary fw-bold border-start border-3 border-primary' : 'hover-bg-surface-2' }}">
                        <i class="fas fa-users text-primary" style="width: 20px;"></i>
                        <span>{{ __('Users') }}</span>
                    </a>

                    <!-- Chef Applications -->
                    <a href="{{ route('dashboard.chef-applications.index') }}"
                       class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-main {{ request()->routeIs('dashboard.chef-applications.*') ? 'bg-primary-soft text-primary fw-bold border-start border-3 border-primary' : 'hover-bg-surface-2' }}">
                        <i class="fas fa-user-check text-primary" style="width: 20px;"></i>
                        <span>{{ __('Chef Applications') }}</span>
                    </a>

                    <!-- Audit Log -->
                    <a href="{{ route('dashboard.audit-log.index') }}"
                       class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3 text-main {{ request()->routeIs('dashboard.audit-log.*') ? 'bg-primary-soft text-primary fw-bold border-start border-3 border-primary' : 'hover-bg-surface-2' }}">
                        <i class="fas fa-history text-primary" style="width: 20px;"></i>
                        <span>{{ __('Audit Log') }}</span>
                    </a>
                @endif
            @endauth
        </nav>
    </div>

    <!-- Sidebar Footer -->
    <div class="p-3 border-top border-subtle">
        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill py-2 d-flex align-items-center justify-content-center gap-2">
            <i class="fas fa-external-link-alt small"></i>
            <span>{{ __('Back to Store') }}</span>
        </a>
    </div>
</aside>
