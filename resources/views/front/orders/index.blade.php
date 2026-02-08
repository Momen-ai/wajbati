@extends('layouts.frontend')

@section('content')
<div class="container py-5">
  <h2 class="fw-bold mb-4">My Orders</h2>

  <div class="row g-4">
      @forelse($orders as $order)
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-card border-0 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                @php
                    $statusColor = match($order->status) {
                        'pending' => 'warning',
                        'accepted' => 'info',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} px-3 py-2 rounded-pill fw-bold text-uppercase">{{ $order->status }}</span>
                <small class="text-muted">#{{ $order->id }}</small>
            </div>
            <div class="card-body px-4">
                <h5 class="fw-bold mb-1">
                    {{ $order->items->first()->meal->name }} 
                    @if($order->items->count() > 1) 
                        <span class="text-muted small">+ {{ $order->items->count() - 1 }} more</span>
                    @endif
                </h5>
                <p class="text-muted small mb-3">Ordered on {{ $order->created_at->format('M d, Y') }}</p>
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-light rounded-circle p-2 me-2"><i class="fas fa-store text-secondary"></i></div>
                    <small class="fw-bold">{{ $order->chef->name ?? 'Chef' }}</small>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <span class="fw-bold h5 mb-0">${{ $order->total }}</span>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Details</a>
                </div>
            </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center py-5">
          <div class="alert alert-info">No orders found.</div>
      </div>
      @endforelse
  </div>
  
  <div class="mt-4">
      {{ $orders->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection
