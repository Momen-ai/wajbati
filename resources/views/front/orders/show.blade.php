@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-light">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-light">{{ __('My Orders') }}</a></li>
            <li class="breadcrumb-item active text-light" aria-current="page">{{ __('Order') }} #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-card border border-secondary border-opacity-10 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0 text-light">{{ __('Order Items') }}</h4>
                        <span class="text-light">{{ __('Placed on') }} {{ $order->created_at->format('M d, Y H:i') }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-dark align-middle">
                            <thead>
                                <tr class="text-light small text-uppercase">
                                    <th class="border-0 ps-0">{{ __('Meal') }}</th>
                                    <th class="border-0 text-center">{{ __('Price') }}</th>
                                    <th class="border-0 text-center">{{ __('Qty') }}</th>
                                    <th class="border-0 text-end pe-0">{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-0 py-3">
                                        <div class="d-flex align-items-center">
                                            @if($item->meal && $item->meal->image)
                                                <img src="{{ asset('storage/' . $item->meal->image->image_path) }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <img src="{{ asset('assets/images/placeholder.svg') }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold text-light">{{ $item->meal_name ?? ($item->meal->name ?? __('Meal')) }}</h6>
                                                <small class="text-light opacity-75">{{ $order->chef->name ?? __('Chef') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ money($item->price) }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end pe-0 fw-bold">{{ money($item->price * $item->quantity) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-card border border-secondary border-opacity-10">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-light">{{ __('Delivery Information') }}</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-light small d-block">{{ __('Address') }}</label>
                            <span class="fw-bold text-light">{{ $order->address }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-light small d-block">{{ __('Phone Number') }}</label>
                            <span class="fw-bold text-light">{{ $order->phone ?? $order->user->phone }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-card border border-secondary border-opacity-10 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-light">{{ __('Order Summary') }}</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-light">{{ __('Subtotal') }}</span>
                        <span class="fw-bold text-light">{{ money($order->subtotal) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-light">{{ __('Delivery Fee') }}</span>
                        <span class="fw-bold text-light">{{ money($order->delivery_fee) }}</span>
                    </div>
                    <hr class="opacity-25 text-light">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold h5 mb-0 text-light">{{ __('Total') }}</span>
                        <span class="fw-bold h5 text-primary mb-0">{{ money($order->total) }}</span>
                    </div>

                    <div class="bg-secondary text-light rounded p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-light">{{ __('Order Status') }}</span>
                            @php
                                $statusColor = match($order->status) {
                                    'pending' => 'warning',
                                    'accepted' => 'info',
                                    'preparing' => 'primary',
                                    'ready_for_delivery', 'prepared' => 'info',
                                    'out_for_delivery' => 'primary',
                                    'delivered', 'completed' => 'success',
                                    'cancelled', 'rejected' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }} text-uppercase">{{ __($order->status) }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-light">{{ __('Payment Method') }}</span>
                            <span class="badge bg-dark text-uppercase">{{ __($order->payment_method) }}</span>
                        </div>
                        <div class="d-flex justify-content-between small mt-2">
                            <span class="text-light">{{ __('Payment Status') }}</span>
                            <span class="badge bg-info text-uppercase">{{ __($order->payment_status) }}</span>
                        </div>
                    </div>

                    @if($order->status == 'pending')
                        <div class="alert alert-warning mb-0 small">
                            <i class="fas fa-clock me-2"></i> {{ __('Your order is waiting for chef\'s approval.') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-grid mt-3">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> {{ __('Back to My Orders') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
