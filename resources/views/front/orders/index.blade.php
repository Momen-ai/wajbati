@extends('layouts.frontend')

@section('content')
<div class="container py-5">
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
