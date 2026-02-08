@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none text-muted">My Orders</a></li>
            <li class="breadcrumb-item active" aria-current="page">Order #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-card border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Order Items</h4>
                        <span class="text-muted">Placed on {{ $order->created_at->format('M d, Y H:i') }}</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small text-uppercase">
                                    <th class="border-0 ps-0">Meal</th>
                                    <th class="border-0 text-center">Price</th>
                                    <th class="border-0 text-center">Qty</th>
                                    <th class="border-0 text-end pe-0">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-0 py-3">
                                        <div class="d-flex align-items-center">
                                            @if($item->meal->image)
                                                <img src="{{ asset('storage/' . $item->meal->image->image_path) }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-utensils text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $item->meal->name }}</h6>
                                                <small class="text-muted">{{ $order->chef->name ?? 'Chef' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">${{ $item->price }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end pe-0 fw-bold">${{ $item->price * $item->quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-card border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Delivery Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Address</label>
                            <span class="fw-bold">{{ $order->address }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Phone Number</label>
                            <span class="fw-bold">{{ $order->phone ?? $order->user->phone }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-card border-0 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">${{ $order->total - 3 }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Delivery Fee</span>
                        <span class="fw-bold">$3.00</span>
                    </div>
                    <hr class="opacity-25">
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-bold h5 mb-0">Total</span>
                        <span class="fw-bold h5 text-primary mb-0">${{ $order->total }}</span>
                    </div>

                    <div class="bg-light rounded p-3 mb-3">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted">Order Status</span>
                            @php
                                $statusColor = match($order->status) {
                                    'pending' => 'warning',
                                    'accepted' => 'info',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $statusColor }} text-uppercase">{{ $order->status }}</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span class="text-muted">Payment Method</span>
                            <span class="badge bg-secondary text-uppercase">{{ $order->payment_method }}</span>
                        </div>
                    </div>

                    @if($order->status == 'pending')
                        <div class="alert alert-warning mb-0 small">
                            <i class="fas fa-clock me-2"></i> Your order is waiting for chef's approval.
                        </div>
                    @endif
                </div>
            </div>

            <div class="d-grid mt-3">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to My Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
