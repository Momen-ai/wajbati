@extends('layouts.frontend')

@section('content')
    <!-- MOBILE VIEW (<768px): Customer Orders -->
    <div class="d-md-none px-3 pt-3 pb-5 has-bottom-bar" style="background: #0a0a0a; min-height: 100vh;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="fw-bolder text-light mb-0" style="font-size: 26px; letter-spacing: -0.02em;">
                {{ __('My Orders') }}
            </h1>
            <span class="badge rounded-pill px-3 py-1 bg-primary-soft text-primary fw-bold" style="font-size: 0.8rem;">
                {{ $orders->total() }} {{ __('orders') }}
            </span>
        </div>

        <div class="d-flex flex-column gap-3">
            @forelse($orders as $order)
                <div class="p-3 rounded-4 border border-secondary border-opacity-10 shadow-sm" style="background: var(--surface-1);">
                    <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom border-secondary border-opacity-10">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold text-light small">#{{ $order->id }}</span>
                            <span class="text-secondary small">·</span>
                            <span class="text-secondary small">{{ $order->created_at->format('Y-m-d') }}</span>
                        </div>
                        <x-ui.badge :status="$order->status" />
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="fw-bold text-light mb-0 text-truncate" style="font-size: 0.95rem; max-width: 65%;">
                            {{ $order->items->first()?->meal?->name ?? __('Meal') }}
                            @if($order->items->count() > 1)
                                <small class="text-secondary fw-normal">(+{{ $order->items->count() - 1 }})</small>
                            @endif
                        </h6>
                        <span class="fw-bold text-primary">{{ money($order->total) }}</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-2 border-top border-secondary border-opacity-10">
                        <span class="text-secondary small text-truncate" style="max-width: 55%;">
                            <i class="fas fa-hat-chef text-primary me-1"></i>
                            {{ $order->chef->kitchen_name ?? $order->chef->name ?? __('Chef') }}
                        </span>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1" style="font-size: 0.8rem;">
                            {{ __('Details') }} <i class="fas fa-chevron-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} ms-1 small"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-5 text-center">
                    <x-ui.empty-state
                        icon="fa-receipt"
                        :title="__('No orders found.')"
                        :message="__('You haven\'t placed any orders yet. Discover delicious home-cooked meals now!')"
                        :actionUrl="route('meals.index')"
                        :actionText="__('Browse Meals')" />
                </div>
            @endforelse
        </div>

        @if($orders->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <!-- DESKTOP VIEW (>=768px): Strictly Unchanged -->
    <div class="container py-5 d-none d-md-block">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h2 fw-bold text-light mb-1">{{ __('My Orders') }}</h1>
            <p class="text-secondary small mb-0">{{ __('Track and manage your meal orders') }}</p>
        </div>
    </div>

    <div class="row g-4" data-reveal-stagger>
        @forelse($orders as $order)
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 rounded-4 border border-secondary border-opacity-10 shadow-sm spotlight-card d-flex flex-column" style="background: var(--surface-1);">
                <div class="p-4 border-bottom border-secondary border-opacity-10 d-flex justify-content-between align-items-center" style="background: var(--surface-2);">
                    <x-ui.badge :status="$order->status" />
                    <span class="text-secondary small fw-mono">#{{ $order->id }}</span>
                </div>
                
                <div class="p-4 d-flex flex-column flex-grow-1">
                    <h5 class="fw-bold mb-2 text-light">
                        {{ $order->items->first()?->meal?->name ?? __('Meal') }} 
                        @if($order->items->count() > 1) 
                            <span class="text-secondary small fw-normal ms-1">(+{{ $order->items->count() - 1 }} {{ __('more') }})</span>
                        @endif
                    </h5>
                    
                    <p class="text-secondary small mb-3">
                        <i class="far fa-calendar-alt me-1 text-primary"></i>
                        {{ __('Ordered on') }} {{ $order->created_at->format('Y-m-d') }}
                    </p>

                    <div class="d-flex align-items-center gap-2 mb-4 p-2 rounded-3 border border-secondary border-opacity-10" style="background: var(--surface-2);">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 32px; height: 32px; background: var(--surface-1);">
                            <i class="fas fa-hat-chef small"></i>
                        </div>
                        <span class="text-light small fw-medium text-truncate">{{ $order->chef->kitchen_name ?? $order->chef->name ?? __('Chef') }}</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top border-secondary border-opacity-10">
                        <div>
                            <small class="text-secondary d-block" style="font-size: 0.72rem;">{{ __('Total') }}</small>
                            <span class="fw-bold h5 mb-0 text-primary">{{ money($order->total) }}</span>
                        </div>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-semibold">
                            {{ __('Details') }} <i class="fas fa-arrow-right ms-1 small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 py-5">
            <x-ui.empty-state
                icon="fa-receipt"
                :title="__('No orders found.')"
                :message="__('You haven\'t placed any orders yet. Discover delicious home-cooked meals now!')"
                :actionUrl="route('meals.index')"
                :actionText="__('Browse Meals')" />
        </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
