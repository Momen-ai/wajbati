@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-light mb-1">{{ __('Earnings & Performance Report') }}</h2>
            <p class="text-muted small mb-0">{{ __('Track your kitchen revenue, order volume, and daily fulfillment capacity.') }}</p>
        </div>
        <a href="{{ route('chef.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Dashboard') }}
        </a>
    </div>

    {{-- Capacity Settings Card --}}
    <div class="card shadow-card border border-secondary border-opacity-10 mb-4 p-4">
        <h5 class="fw-bold text-light mb-3">{{ __('Daily Order Capacity') }}</h5>
        <form action="{{ route('chef.capacity.update') }}" method="POST" class="row g-3 align-items-center">
            @csrf
            <div class="col-auto">
                <label class="form-label text-muted small mb-0">{{ __('Maximum orders your kitchen can prepare per day:') }}</label>
            </div>
            <div class="col-auto">
                <input type="number" name="daily_capacity" class="form-control bg-secondary text-light border-secondary border-opacity-10" value="{{ old('daily_capacity', $chef->daily_capacity ?? 30) }}" min="1" max="1000" style="width: 120px;" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary rounded-pill px-4">{{ __('Update Capacity') }}</button>
            </div>
        </form>
    </div>

    {{-- Metrics Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card shadow-card border border-secondary border-opacity-10 p-4 text-center">
                <div class="text-primary mb-2"><i class="fas fa-wallet fa-2x"></i></div>
                <h6 class="text-muted small text-uppercase mb-1">{{ __('Total Revenue') }}</h6>
                <h3 class="fw-bold text-light mb-0">{{ money($totalEarnings) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-card border border-secondary border-opacity-10 p-4 text-center">
                <div class="text-success mb-2"><i class="fas fa-check-double fa-2x"></i></div>
                <h6 class="text-muted small text-uppercase mb-1">{{ __('Completed Orders') }}</h6>
                <h3 class="fw-bold text-light mb-0">{{ $ordersCount }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-card border border-secondary border-opacity-10 p-4 text-center">
                <div class="text-info mb-2"><i class="fas fa-receipt fa-2x"></i></div>
                <h6 class="text-muted small text-uppercase mb-1">{{ __('Average Order Value') }}</h6>
                <h3 class="fw-bold text-light mb-0">{{ money($averageOrderValue) }}</h3>
            </div>
        </div>
    </div>

    {{-- Orders History Table --}}
    <div class="card shadow-card border border-secondary border-opacity-10">
        <div class="card-header bg-card border-bottom border-secondary border-opacity-10 p-4">
            <h5 class="fw-bold text-light mb-0">{{ __('Completed Orders History') }}</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0">
                <thead class="table-dark">
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
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user?->name ?? __('Guest') }}</td>
                            <td class="fw-bold text-primary">{{ money($order->total) }}</td>
                            <td><span class="badge bg-success">{{ __($order->status) }}</span></td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted py-4">{{ __('No completed orders found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
