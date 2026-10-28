@extends('layouts.frontend')

@section('content')
<div class="container py-5">
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
