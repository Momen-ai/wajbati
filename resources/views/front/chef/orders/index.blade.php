@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-light">Kitchen Orders</h2>
        <div class="badge bg-primary fs-6">{{ $orders->total() }} Total Orders</div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($orders->count() > 0)
            <div class="card shadow-card border border-secondary border-opacity-10">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead class="bg-secondary text-light">
                                <tr>
                                    <th class="p-3 border-0">Order ID</th>
                                    <th class="p-3 border-0">Customer</th>
                                    <th class="p-3 border-0">Items</th>
                                    <th class="p-3 border-0">Total</th>
                                    <th class="p-3 border-0">Status</th>
                                    <th class="p-3 border-0">Date</th>
                                    <th class="p-3 border-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr>
                                    <td class="p-3 fw-bold text-light">#{{ $order->id }}</td>
                                    <td class="p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary text-light rounded-circle p-2 me-2">
                                                <i class="fas fa-user text-light"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-light">{{ $order->user->name ?? 'Guest' }}</div>
                                                <small class="text-light">{{ $order->phone ?? $order->user->phone ?? '-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3">
                                        <ul class="list-unstyled mb-0">
                                            @foreach($order->items->take(2) as $item)
                                            <li class="small text-light">
                                                {{ $item->quantity }}x {{ $item->meal->name }}
                                            </li>
                                            @endforeach
                                            @if($order->items->count() > 2)
                                            <li class="small text-primary">+ {{ $order->items->count() - 2 }} more...</li>
                                            @endif
                                        </ul>
                                    </td>
                                    <td class="p-3 fw-bold text-light">${{ $order->total }}</td>
                                    <td class="p-3">
                                        @php
                                            $statusColor = match($order->status) {
                                                'pending' => 'warning',
                                                'accepted' => 'info',
                                                'cooking' => 'primary',
                                                'delivered' => 'success',
                                                'cancelled' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }} text-{{ $statusColor }} bg-opacity-10 px-3 py-2 rounded-pill text-uppercase">{{ $order->status }}</span>
                                    </td>
                                    <td class="p-3 text-light small">{{ $order->created_at->format('M d, H:i') }}</td>
                                    <td class="p-3">
                                        <form action="{{ route('chef.orders.update-status', $order->id) }}" method="POST" class="d-inline-flex">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm border-secondary bg-secondary text-light me-2" onchange="this.form.submit()" style="width: 130px;">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                                <option value="prepared" {{ $order->status == 'prepared' ? 'selected' : '' }}>Prepared</option>
                                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                        <a href="{{ route('chef.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary ms-1"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
            @else
            <div class="text-center py-5">
                <div class="mb-3"><i class="fas fa-clipboard-list fa-4x text-light opacity-25"></i></div>
                <h3 class="text-light">No orders yet</h3>
                <p class="text-light">Wait for customers to discover your delicious meals!</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
