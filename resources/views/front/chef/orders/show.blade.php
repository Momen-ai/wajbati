@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Order Details #{{ $order->id }}</h2>
        <a href="{{ route('chef.orders.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i> Back to List
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Items Card -->
            <div class="card shadow-card border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-bottom pt-4 px-4">
                    <h5 class="fw-bold mb-3">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Meal</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="pe-4 text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($item->meal->image)
                                            <img src="{{ asset('storage/' . $item->meal->image->image_path) }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div class="fw-bold">{{ $item->meal->name }}</div>
                                        </div>
                                    </td>
                                    <td>${{ $item->price }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="pe-4 text-end fw-bold">${{ $item->price * $item->quantity }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold py-3">Total Amount:</td>
                                    <td class="pe-4 text-end fw-bold py-3 text-primary fs-5">${{ $order->total }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="card shadow-card border-0 rounded-4">
                <div class="card-header bg-white border-bottom pt-4 px-4">
                    <h5 class="fw-bold mb-3">Customer Information</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Customer Name</label>
                            <span class="fw-bold">{{ $order->user->name }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Phone Number</label>
                            <span class="fw-bold">{{ $order->phone ?? $order->user->phone ?? '-' }}</span>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small d-block">Delivery Address</label>
                            <span class="fw-bold">{{ $order->address }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Payment Method</label>
                            <span class="badge bg-light text-dark border">{{ strtoupper($order->payment_method ?? 'CASH') }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">Payment Status</label>
                            <span class="badge bg-{{ ($order->payment_status ?? 'pending') == 'paid' ? 'success' : 'warning' }}">{{ strtoupper($order->payment_status ?? 'pending') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Status Card -->
            <div class="card shadow-card border-0 rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Action Center</h5>
                    <form action="{{ route('chef.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label small text-muted">Update Status</label>
                            <select name="status" class="form-select border-primary">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                <option value="prepared" {{ $order->status == 'prepared' ? 'selected' : '' }}>Prepared</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">Update Status</button>
                    </form>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card shadow-card border-0 rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="opacity-75">Order Date</span>
                        <span>{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="opacity-75">Order Time</span>
                        <span>{{ $order->created_at->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
