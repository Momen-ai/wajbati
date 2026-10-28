@extends('layouts.frontend')

@section('content')
<div class="container py-5">
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

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [{
                    label: '{{ __('Earnings') }}',
                    data: {!! json_encode($chartData['values']) !!},
                    borderColor: '#ff7a18',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ff7a18',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
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
                        padding: 10
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.04)' },
                        ticks: { color: '#a0a0a0', font: { family: 'Cairo, Inter, sans-serif' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.04)' },
                        ticks: { color: '#a0a0a0', font: { family: 'Cairo, Inter, sans-serif' } }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
