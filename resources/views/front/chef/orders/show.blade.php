@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-light">{{ __('Order Details') }} #{{ $order->id }}</h2>
        <a href="{{ route('chef.orders.index') }}" class="btn btn-outline-primary rounded-pill">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to List') }}
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Items Card -->
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 mb-4">
                <div class="card-header bg-card border-bottom border-secondary border-opacity-10 pt-4 px-4">
                    <h5 class="fw-bold mb-3 text-light">{{ __('Order Items') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead class="bg-secondary text-light">
                                <tr>
                                    <th class="ps-4">{{ __('Meal') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th class="pe-4 text-end">{{ __('Subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            @if($item->meal && $item->meal->image)
                                            <img src="{{ asset('storage/' . $item->meal->image->image_path) }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div class="fw-bold text-light">{{ $item->meal_name ?? optional($item->meal)->name }}</div>
                                        </div>
                                    </td>
                                    <td class="text-light">{{ number_format($item->price, 2) }} {{ config('shop.currency', '₪') }}</td>
                                    <td class="text-light">{{ $item->quantity }}</td>
                                    <td class="pe-4 text-end fw-bold text-light">{{ number_format($item->price * $item->quantity, 2) }} {{ config('shop.currency', '₪') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-secondary text-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold py-3">{{ __('Total Amount') }}:</td>
                                    <td class="pe-4 text-end fw-bold py-3 text-primary fs-5">{{ number_format($order->total, 2) }} {{ config('shop.currency', '₪') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4">
                <div class="card-header bg-card border-bottom border-secondary border-opacity-10 pt-4 px-4">
                    <h5 class="fw-bold mb-3 text-light">{{ __('Customer Information') }}</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-light small d-block">{{ __('Customer Name') }}</label>
                            <span class="fw-bold text-light">{{ $order->user->name }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-light small d-block">{{ __('Phone Number') }}</label>
                            <span class="fw-bold text-light">{{ $order->phone ?? $order->user->phone ?? '-' }}</span>
                        </div>
                        <div class="col-12">
                            <label class="text-light small d-block">{{ __('Delivery Address') }}</label>
                            <span class="fw-bold text-light">{{ $order->address }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-light small d-block">{{ __('Payment Method') }}</label>
                            <span class="badge bg-secondary text-light border border-secondary border-opacity-10">{{ strtoupper($order->payment_method ?? 'CASH') }}</span>
                        </div>
                        <div class="col-md-6">
                            <label class="text-light small d-block">{{ __('Payment Status') }}</label>
                            <span class="badge bg-{{ ($order->payment_status ?? 'pending') == 'paid' ? 'success' : 'warning' }}">{{ strtoupper($order->payment_status ?? 'pending') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Order Status Card -->
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-light">{{ __('Action Center') }}</h5>
                    @php $allowedStatuses = \App\Services\OrderStatusService::getAllowedStatuses($order->status); @endphp
                    @if(count($allowedStatuses) > 0)
                    <form action="{{ route('chef.orders.update-status', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label small text-light">{{ __('Update Status') }}</label>
                            <select name="status" class="form-select border-primary bg-secondary text-light">
                                @foreach($allowedStatuses as $status)
                                    <option value="{{ $status }}">{{ __('order.status.' . $status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow">{{ __('Update Status') }}</button>
                    </form>
                    @else
                    <p class="text-muted small">{{ __('No further status updates available.') }}</p>
                    @endif
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="opacity-75">{{ __('Order Date') }}</span>
                        <span>{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="opacity-75">{{ __('Order Time') }}</span>
                        <span>{{ $order->created_at->format('H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
