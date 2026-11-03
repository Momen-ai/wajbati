@extends('layouts.frontend')

@section('content')
{{-- ================= MOBILE VIEW (<768px) ================= --}}
<div class="d-md-none has-bottom-bar px-3 pt-3 pb-5" style="background: #0a0a0a; min-height: 100vh;">
    <!-- Mobile Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('chef.dashboard') }}" class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle border border-subtle text-secondary" style="width: 40px; height: 40px; background: #151515;">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
            </a>
            <div>
                <h1 class="h5 fw-bold text-light mb-0">{{ __('Earnings & Performance') }}</h1>
                <small class="text-secondary" style="font-size: 0.75rem;">{{ __('Revenue & orders report') }}</small>
            </div>
        </div>
    </div>

    <!-- Daily Capacity Settings Card -->
    <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-3" style="background: var(--surface-1);">
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 32px; height: 32px; background: var(--surface-2);">
                <i class="fas fa-sliders-h" style="font-size: 13px;"></i>
            </div>
            <h6 class="fw-bold text-light mb-0" style="font-size: 0.9rem;">{{ __('Daily Order Capacity') }}</h6>
        </div>
        <p class="text-secondary small mb-3" style="font-size: 0.75rem;">{{ __('Maximum orders your kitchen can prepare per day:') }}</p>

        <form action="{{ route('chef.capacity.update') }}" method="POST">
            @csrf
            <div class="d-flex gap-2">
                <input type="number" name="daily_capacity" class="form-control text-light border-secondary border-opacity-25 rounded-pill text-center"
                       value="{{ old('daily_capacity', $chef->daily_capacity ?? 30) }}" min="1" max="1000"
                       style="background: var(--surface-2); font-size: 16px; min-height: 44px; width: 100px;" required>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold flex-grow-1" style="color: #1b0e03 !important; min-height: 44px;">
                    {{ __('Update Capacity') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Stat Cards (Row of 3 cards: Hero + 2 compact) -->
    <div class="mb-3">
        <!-- Hero: Total Revenue -->
        <div class="mb-2">
            <x-ui.stat-card
                icon="fas fa-wallet"
                :label="__('Total Revenue')"
                :value="money($totalEarnings)"
                :isHero="true" />
        </div>

        <div class="row g-2">
            <div class="col-6">
                <x-ui.stat-card
                    icon="fas fa-check-double"
                    :label="__('Completed Orders')"
                    :value="$ordersCount"
                    :numericValue="$ordersCount" />
            </div>
            <div class="col-6">
                <x-ui.stat-card
                    icon="fas fa-receipt"
                    :label="__('Average Order Value')"
                    :value="money($averageOrderValue)" />
            </div>
        </div>
    </div>

    <!-- Completed Orders History List -->
    <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-4" style="background: var(--surface-1);">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-light mb-0" style="font-size: 0.9rem;">{{ __('Completed Orders History') }}</h6>
            <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-10 px-2 py-1 small">
                {{ count($completedOrders) }}
            </span>
        </div>

        <div class="d-flex flex-column gap-2">
            @forelse ($completedOrders as $order)
                <div class="p-3 rounded-3 border border-secondary border-opacity-10" style="background: var(--surface-2);">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="font-monospace fw-bold text-light small">#{{ $order->id }}</span>
                        <x-ui.badge type="success">
                            {{ __($order->status) }}
                        </x-ui.badge>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-light small fw-medium text-truncate d-block" style="max-width: 140px;">{{ $order->user?->name ?? __('Guest') }}</span>
                            <small class="text-secondary" style="font-size: 0.72rem;">{{ $order->created_at->format('Y-m-d H:i') }}</small>
                        </div>
                        <span class="fw-bold text-primary">{{ money($order->total) }}</span>
                    </div>
                </div>
            @empty
                <div class="py-4 text-center">
                    <x-ui.empty-state
                        icon="fas fa-receipt"
                        :title="__('No completed orders found.')" />
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ================= DESKTOP VIEW (≥768px, UNTOUCHED) ================= --}}
<div class="container py-5 d-none d-md-block">
    <!-- Header -->
    <x-ui.page-header
        :title="__('Earnings & Performance Report')"
        :subtitle="__('Track your kitchen revenue, order volume, and daily fulfillment capacity.')">
        <x-slot name="actions">
            <a href="{{ route('chef.dashboard') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i>{{ __('Back to Dashboard') }}
            </a>
        </x-slot>
    </x-ui.page-header>

    {{-- Capacity Settings Card --}}
    <div class="spotlight-card rounded-4 p-4 mb-4" data-spotlight>
        <div class="d-flex align-items-center gap-2 mb-3">
            <div class="rounded-circle bg-primary-soft text-primary d-inline-flex align-items-center justify-content-center"
                 style="width: 36px; height: 36px;">
                <i class="fas fa-sliders-h"></i>
            </div>
            <h5 class="fw-bold text-main mb-0">{{ __('Daily Order Capacity') }}</h5>
        </div>

        <form action="{{ route('chef.capacity.update') }}" method="POST" class="row g-3 align-items-center">
            @csrf
            <div class="col-12 col-md-auto">
                <label class="form-label text-secondary small mb-0">{{ __('Maximum orders your kitchen can prepare per day:') }}</label>
            </div>
            <div class="col-auto">
                <input type="number" name="daily_capacity" class="form-control" value="{{ old('daily_capacity', $chef->daily_capacity ?? 30) }}" min="1" max="1000" style="width: 130px;" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('Update Capacity') }}</button>
            </div>
        </form>
    </div>

    {{-- Metrics Cards with Hierarchy --}}
    <div class="row g-4 mb-5">
        <!-- Hero: Total Revenue -->
        <div class="col-12 col-md-4">
            <x-ui.stat-card
                icon="fas fa-wallet"
                :label="__('Total Revenue')"
                :value="money($totalEarnings)"
                :isHero="true"
                class="h-100" />
        </div>

        <!-- Completed Orders -->
        <div class="col-6 col-md-4">
            <x-ui.stat-card
                icon="fas fa-check-double"
                :label="__('Completed Orders')"
                :value="$ordersCount"
                :numericValue="$ordersCount"
                class="h-100" />
        </div>

        <!-- Average Order Value -->
        <div class="col-6 col-md-4">
            <x-ui.stat-card
                icon="fas fa-receipt"
                :label="__('Average Order Value')"
                :value="money($averageOrderValue)"
                class="h-100" />
        </div>
    </div>

    {{-- Orders History Table --}}
    <div class="spotlight-card rounded-4 p-4" data-spotlight>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-main mb-0">{{ __('Completed Orders History') }}</h5>
            <span class="badge bg-surface-2 text-secondary border border-subtle">
                {{ count($completedOrders) }}
            </span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('Order ID') }}</th>
                        <th>{{ __('Customer') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($completedOrders as $order)
                        <tr>
                            <td class="text-secondary fw-semibold">#{{ $order->id }}</td>
                            <td class="text-main fw-semibold">{{ $order->user?->name ?? __('Guest') }}</td>
                            <td class="fw-bold text-primary">{{ money($order->total) }}</td>
                            <td>
                                <x-ui.badge type="success">
                                    {{ __($order->status) }}
                                </x-ui.badge>
                            </td>
                            <td class="text-secondary small">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <x-ui.empty-state
                                    icon="fas fa-receipt"
                                    :title="__('No completed orders found.')" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
