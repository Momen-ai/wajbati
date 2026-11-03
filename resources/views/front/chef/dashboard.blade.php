@extends('layouts.frontend')

@section('content')
{{-- ================= MOBILE VIEW (<768px, Reference Screen 6) ================= --}}
<div class="d-md-none has-bottom-bar px-3 pt-3 pb-5" style="background: #0a0a0a; min-height: 100vh;">
    <!-- Kitchen Header with Avatar & Open/Closed Switch -->
    <div class="d-flex align-items-center justify-content-between p-3 rounded-4 border border-secondary border-opacity-10 mb-3" style="background: var(--surface-1);">
        <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary fw-bold"
                 style="width: 44px; height: 44px; background: var(--surface-2); border: 2px solid #ff7a18;">
                <i class="fas fa-store"></i>
            </div>
            <div>
                <h6 class="fw-bold text-light mb-0" style="font-size: 0.95rem;">
                    {{ Auth::user()->kitchen_name ?? Auth::user()->name }}
                </h6>
                <small class="text-secondary" style="font-size: 0.72rem;">{{ __('Chef Dashboard') }}</small>
            </div>
        </div>

        <!-- Open / Closed Status Pill -->
        <div class="form-check form-switch m-0 d-flex align-items-center gap-2">
            <label class="form-check-label text-success small fw-bold mb-0" for="mobileKitchenStatusSwitch" style="font-size: 0.78rem;">
                {{ __('Open') }}
            </label>
            <input class="form-check-input" type="checkbox" role="switch" id="mobileKitchenStatusSwitch" checked style="cursor: pointer;">
        </div>
    </div>

    <!-- Quick Action Bar -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h5 fw-bold text-light mb-0">{{ __('Kitchen Overview') }}</h1>
        <a href="{{ route('chef.meals.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-1 fw-bold d-inline-flex align-items-center gap-1" style="font-size: 0.8rem; color: #1b0e03 !important;">
            <i class="fas fa-plus"></i> {{ __('New Meal') }}
        </a>
    </div>

    <!-- Chef Quick Navigation Tiles -->
    <div class="row g-2 mb-3">
        <div class="col-3">
            <a href="{{ route('chef.meals.index') }}" class="p-2 rounded-3 border border-secondary border-opacity-10 d-flex flex-column align-items-center justify-content-center text-decoration-none text-center" style="background: var(--surface-1); min-height: 68px;">
                <i class="fas fa-utensils text-primary mb-1" style="font-size: 15px;"></i>
                <span class="text-light fw-medium" style="font-size: 0.72rem;">{{ __('My Meals') }}</span>
            </a>
        </div>
        <div class="col-3">
            <a href="{{ route('chef.orders.index') }}" class="p-2 rounded-3 border border-secondary border-opacity-10 d-flex flex-column align-items-center justify-content-center text-decoration-none text-center" style="background: var(--surface-1); min-height: 68px;">
                <i class="fas fa-clipboard-list text-primary mb-1" style="font-size: 15px;"></i>
                <span class="text-light fw-medium" style="font-size: 0.72rem;">{{ __('Orders') }}</span>
            </a>
        </div>
        <div class="col-3">
            <a href="{{ route('chef.earnings') }}" class="p-2 rounded-3 border border-secondary border-opacity-10 d-flex flex-column align-items-center justify-content-center text-decoration-none text-center" style="background: var(--surface-1); min-height: 68px;">
                <i class="fas fa-wallet text-primary mb-1" style="font-size: 15px;"></i>
                <span class="text-light fw-medium" style="font-size: 0.72rem;">{{ __('Earnings') }}</span>
            </a>
        </div>
        <div class="col-3">
            <a href="{{ route('profile.edit') }}" class="p-2 rounded-3 border border-secondary border-opacity-10 d-flex flex-column align-items-center justify-content-center text-decoration-none text-center" style="background: var(--surface-1); min-height: 68px;">
                <i class="fas fa-cog text-primary mb-1" style="font-size: 15px;"></i>
                <span class="text-light fw-medium" style="font-size: 0.72rem;">{{ __('Settings') }}</span>
            </a>
        </div>
    </div>

    <!-- Time Period Filter (Segmented control) -->
    <div class="d-flex p-1 rounded-pill border border-secondary border-opacity-10 mb-3" style="background: var(--surface-1);">
        <button type="button" class="btn btn-sm rounded-pill flex-grow-1 fw-bold text-light" style="background: var(--surface-2); font-size: 0.8rem;">{{ __('Today') }}</button>
        <button type="button" class="btn btn-sm rounded-pill flex-grow-1 text-secondary" style="font-size: 0.8rem;">{{ __('Week') }}</button>
        <button type="button" class="btn btn-sm rounded-pill flex-grow-1 text-secondary" style="font-size: 0.8rem;">{{ __('Month') }}</button>
    </div>

    <!-- Stat Cards (Horizontal scroll-snap row: 152px width each) -->
    <div class="d-flex gap-2 overflow-x-auto pb-2 mb-3" style="scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch; scrollbar-width: none;">
        <div style="scroll-snap-align: start; flex-shrink: 0;">
            <x-ui.stat-card
                icon="fas fa-check-circle"
                :label="__('Completed Orders')"
                :value="$completedOrders"
                :numericValue="$completedOrders"
                :trend="$growth['revenue']"
                :trendUp="!str_contains($growth['revenue'], '-')"
                :isHero="true" />
        </div>
        <div style="scroll-snap-align: start; flex-shrink: 0;">
            <x-ui.stat-card
                icon="fas fa-shopping-bag"
                :label="__('Total Orders')"
                :value="$totalOrders"
                :numericValue="$totalOrders" />
        </div>
        <div style="scroll-snap-align: start; flex-shrink: 0;">
            <x-ui.stat-card
                icon="fas fa-clock"
                :label="__('Pending Orders')"
                :value="$pendingOrders"
                :numericValue="$pendingOrders" />
        </div>
        <div style="scroll-snap-align: start; flex-shrink: 0;">
            <x-ui.stat-card
                icon="fas fa-utensils"
                :label="__('Total Meals')"
                :value="$totalMeals"
                :numericValue="$totalMeals" />
        </div>
    </div>

    <!-- Revenue Area Chart -->
    <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-4" style="background: var(--surface-1);">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h6 class="fw-bold text-light mb-0" style="font-size: 0.92rem;">{{ __('Monthly Earnings') }}</h6>
                <small class="text-secondary" style="font-size: 0.72rem;">{{ __('Real-time revenue tracking') }}</small>
            </div>
            <span class="badge rounded-pill bg-primary-soft text-primary px-2 py-1 fw-bold" style="font-size: 0.72rem;">
                {{ __('Earnings') }}
            </span>
        </div>
        <div style="height: 180px; position: relative;">
            <canvas id="chefMobileRevenueChart"></canvas>
            @if(array_sum($chartData['values']) == 0)
                <div class="position-absolute top-50 start-50 translate-middle text-center pe-none">
                    <span class="badge bg-surface-2 text-secondary border border-subtle px-3 py-1 rounded-pill small">
                        <i class="fas fa-chart-line me-1 text-primary"></i> {{ __('No revenue yet') }}
                    </span>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Orders List -->
    <div class="p-3 rounded-4 border border-secondary border-opacity-10" style="background: var(--surface-1);">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-light mb-0" style="font-size: 0.95rem;">{{ __('Recent Orders') }}</h6>
            <a href="{{ route('chef.orders.index') }}" class="text-primary text-decoration-none fw-semibold small" style="font-size: 0.78rem;">
                {{ __('View All') }} <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} ms-1"></i>
            </a>
        </div>

        <div class="d-flex flex-column gap-2">
            @forelse($recentOrders as $order)
                <div class="p-3 rounded-3 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between" style="background: var(--surface-2);">
                    <div style="min-width: 0;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="fw-bold text-light" style="font-size: 0.88rem;">#{{ $order->id }}</span>
                            <span class="text-secondary small">·</span>
                            <span class="text-secondary small text-truncate" style="max-width: 110px;">{{ $order->user->name ?? __('Guest') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-primary small">{{ money($order->total) }}</span>
                            @php
                                $statusType = match($order->status) {
                                    'completed', 'delivered' => 'delivered',
                                    'pending' => 'pending',
                                    'preparing' => 'preparing',
                                    'cancelled', 'rejected' => 'cancelled',
                                    default => 'pending'
                                };
                            @endphp
                            <x-ui.status-badge :status="$statusType" :label="__(ucfirst(str_replace('_', ' ', $order->status)))" />
                        </div>
                    </div>
                    <a href="{{ route('chef.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.78rem;">
                        {{ __('Details') }}
                    </a>
                </div>
            @empty
                <p class="text-secondary small text-center my-3">{{ __('No records found') }}</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ================= DESKTOP VIEW (≥768px, UNTOUCHED) ================= --}}
<div class="container py-5 d-none d-md-block">
    <!-- Header -->
    <x-ui.page-header
        :title="__('Welcome back') . ', ' . Auth::user()->name . '!'"
        :subtitle="__('Here\'s what\'s happening with your kitchen today.')">
        <x-slot name="actions">
            <a href="{{ route('chef.earnings') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="fas fa-wallet me-1"></i>{{ __('Earnings') }}
            </a>
            <a href="{{ route('chef.orders.export') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="fas fa-file-csv me-1"></i>{{ __('Export Report') }}
            </a>
        </x-slot>
    </x-ui.page-header>

    <!-- Stats Hierarchy -->
    <div class="row g-4 mb-4">
        <!-- Hero: Completed Orders -->
        <div class="col-12 col-md-6 col-xl-3">
            <x-ui.stat-card
                icon="fas fa-check-circle"
                :label="__('Completed Orders')"
                :value="$completedOrders"
                :numericValue="$completedOrders"
                :trend="$growth['revenue']"
                :trendUp="!str_contains($growth['revenue'], '-')"
                :isHero="true"
                class="h-100" />
        </div>

        <!-- Total Orders -->
        <div class="col-6 col-md-6 col-xl-3">
            <x-ui.stat-card
                icon="fas fa-shopping-bag"
                :label="__('Total Orders')"
                :value="$totalOrders"
                :numericValue="$totalOrders"
                class="h-100" />
        </div>

        <!-- Total Meals -->
        <div class="col-6 col-md-6 col-xl-3">
            <x-ui.stat-card
                icon="fas fa-utensils"
                :label="__('Total Meals')"
                :value="$totalMeals"
                :numericValue="$totalMeals"
                class="h-100" />
        </div>

        <!-- Pending Orders -->
        <div class="col-12 col-md-6 col-xl-3">
            <x-ui.stat-card
                icon="fas fa-clock"
                :label="__('Pending Orders')"
                :value="$pendingOrders"
                :numericValue="$pendingOrders"
                class="h-100" />
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="spotlight-card p-4 rounded-4" data-spotlight>
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
                    <div>
                        <h5 class="fw-bold text-main mb-1">{{ __('Monthly Earnings') }}</h5>
                        <span class="text-secondary small">{{ __('Real-time revenue tracking') }}</span>
                    </div>
                    <span class="badge badge-primary">{{ __('Earnings') }}</span>
                </div>
                <div style="height: 280px; position: relative;">
                    <canvas id="chefRevenueChart"></canvas>
                    @if(array_sum($chartData['values']) == 0)
                        <div class="position-absolute top-50 start-50 translate-middle text-center pe-none">
                            <span class="badge bg-surface-2 text-secondary border border-subtle px-3 py-2 rounded-pill small">
                                <i class="fas fa-chart-line me-1 text-primary"></i> {{ __('No revenue recorded for this period yet') }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Actions -->
    <div class="row g-4">
        <!-- Recent Orders Table -->
        <div class="col-12 col-lg-8">
            <div class="spotlight-card p-4 rounded-4 h-100 d-flex flex-column" data-spotlight>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-main mb-0">{{ __('Recent Orders') }}</h5>
                    <a href="{{ route('chef.orders.index') }}" class="btn btn-link text-primary text-decoration-none small fw-semibold p-0">
                        {{ __('View All') }} <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} ms-1"></i>
                    </a>
                </div>

                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('Order ID') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="text-secondary fw-semibold">#{{ $order->id }}</td>
                                    <td class="text-main fw-semibold">{{ $order->user->name ?? __('Guest') }}</td>
                                    <td class="fw-bold text-primary">{{ money($order->total) }}</td>
                                    <td>
                                        @php
                                            $badgeType = match($order->status) {
                                                'completed', 'delivered' => 'success',
                                                'pending' => 'warning',
                                                'cancelled', 'rejected' => 'danger',
                                                default => 'primary'
                                            };
                                        @endphp
                                        <x-ui.badge :type="$badgeType">
                                            {{ __(ucfirst(str_replace('_', ' ', $order->status))) }}
                                        </x-ui.badge>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('chef.orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            {{ __('Details') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-secondary">
                                        {{ __('No records found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Side Actions -->
        <div class="col-12 col-lg-4">
            <div class="d-flex flex-column gap-4 h-100">
                <!-- Add Meal Card -->
                <div class="spotlight-card p-4 rounded-4 text-center" data-spotlight>
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 56px; height: 56px;">
                        <i class="fas fa-plus fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-main mb-1">{{ __('Growing your menu?') }}</h5>
                    <p class="text-secondary small mb-3">{{ __('Add your latest delicious creations to attract more customers.') }}</p>
                    <a href="{{ route('chef.meals.create') }}" class="btn btn-primary rounded-pill w-100">
                        <i class="fas fa-plus me-1"></i>{{ __('Add New Meal') }}
                    </a>
                </div>

                <!-- Kitchen Profile Info -->
                <div class="spotlight-card p-4 rounded-4 flex-grow-1" data-spotlight>
                    <h5 class="fw-bold text-main mb-3">{{ __('Kitchen Profile') }}</h5>
                    <div class="d-flex align-items-center gap-3 mb-3 p-2 rounded-3 bg-surface-2 border border-subtle">
                        <div class="rounded-circle bg-primary-soft text-primary d-flex align-items-center justify-content-center fw-bold fs-5"
                             style="width: 48px; height: 48px;">
                            <i class="fas fa-store"></i>
                        </div>
                        <div style="min-width: 0;">
                            <div class="fw-bold text-main text-truncate">{{ Auth::user()->kitchen_name ?? __('My Kitchen') }}</div>
                            <small class="text-secondary">{{ __('Chef Account') }}</small>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100">
                        <i class="fas fa-user-edit me-1"></i>{{ __('Edit Profile') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartCanvas = document.getElementById('chefRevenueChart');
        if (!chartCanvas) return;

        const ctx = chartCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(255, 122, 24, 0.35)');
        gradient.addColorStop(1, 'rgba(255, 122, 24, 0.0)');

        function initChart(canvasId) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const gradient = ctx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, 'rgba(255, 122, 24, 0.35)');
            gradient.addColorStop(1, 'rgba(255, 122, 24, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [{
                        label: '{{ __('Earnings') }}',
                        data: {!! json_encode($chartData['values']) !!},
                        borderColor: '#ff7a18',
                        borderWidth: 2.5,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ff7a18',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#161616',
                            titleColor: '#f0f0f0',
                            bodyColor: '#ff7a18',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            padding: 8
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255, 255, 255, 0.04)' },
                            ticks: { color: '#a0a0a0', font: { family: 'Cairo, Inter, sans-serif', size: 10 } }
                        },
                        y: {
                            beginAtZero: true,
                            min: 0,
                            suggestedMax: 100,
                            grid: { color: 'rgba(255, 255, 255, 0.04)' },
                            ticks: { color: '#a0a0a0', font: { family: 'Cairo, Inter, sans-serif', size: 10 }, precision: 0 }
                        }
                    }
                }
            });
        }

        initChart('chefRevenueChart');
        initChart('chefMobileRevenueChart');
    });
</script>
@endpush
@endsection
