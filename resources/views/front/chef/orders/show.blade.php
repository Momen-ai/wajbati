@extends('layouts.frontend')

@section('content')
{{-- ================= MOBILE VIEW (<768px) ================= --}}
<div class="d-md-none has-bottom-bar px-3 pt-3 pb-5" style="background: #0a0a0a; min-height: 100vh;">
    <!-- Mobile Top Bar -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('chef.orders.index') }}" class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle border border-subtle text-secondary" style="width: 40px; height: 40px; background: #151515;">
                <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
            </a>
            <div>
                <h1 class="h5 fw-bold text-light mb-0">{{ __('Order Details') }}</h1>
                <span class="font-monospace text-secondary small">#{{ $order->id }}</span>
            </div>
        </div>
        <x-ui.badge :status="$order->status" />
    </div>

    <!-- Status Update Action Card -->
    @php $allowedStatuses = \App\Services\OrderStatusService::getAllowedStatuses($order->status); @endphp
    @if(count($allowedStatuses) > 0)
        <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-3" style="background: var(--surface-1);">
            <h6 class="fw-bold text-light mb-2" style="font-size: 0.9rem;">{{ __('Update Order Status') }}</h6>
            <form action="{{ route('chef.orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="d-flex gap-2">
                    <select name="status" class="form-select form-select-sm text-light border-secondary border-opacity-25 rounded-pill"
                            style="background: var(--surface-2); font-size: 16px; min-height: 44px;">
                        @foreach($allowedStatuses as $status)
                            <option value="{{ $status }}">{{ __('order.status.' . $status) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold flex-shrink-0" style="color: #1b0e03 !important; min-height: 44px;">
                        {{ __('Update') }}
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Customer Details Card -->
    <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-3" style="background: var(--surface-1);">
        <h6 class="fw-bold text-light mb-3" style="font-size: 0.9rem;">{{ __('Customer Information') }}</h6>
        <div class="d-flex flex-column gap-2">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">{{ __('Customer Name') }}</span>
                <span class="fw-semibold text-light small">{{ $order->user->name }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-secondary small">{{ __('Phone Number') }}</span>
                @if($order->phone ?? $order->user->phone)
                    <a href="tel:{{ $order->phone ?? $order->user->phone }}" class="text-primary text-decoration-none fw-semibold small d-inline-flex align-items-center gap-1">
                        <i class="fas fa-phone-alt" style="font-size: 11px;"></i> {{ $order->phone ?? $order->user->phone }}
                    </a>
                @else
                    <span class="text-secondary small">-</span>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-start">
                <span class="text-secondary small flex-shrink-0">{{ __('Delivery Address') }}</span>
                <span class="fw-semibold text-light small text-end" style="max-width: 65%;">{{ $order->address }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-2 border-top border-secondary border-opacity-10">
                <span class="text-secondary small">{{ __('Payment') }}</span>
                <div class="d-flex align-items-center gap-1">
                    <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-10" style="font-size: 0.7rem;">
                        {{ strtoupper($order->payment_method ?? 'CASH') }}
                    </span>
                    <span class="badge bg-{{ ($order->payment_status ?? 'pending') == 'paid' ? 'success' : 'warning' }} bg-opacity-20 text-{{ ($order->payment_status ?? 'pending') == 'paid' ? 'success' : 'warning' }}" style="font-size: 0.7rem;">
                        {{ strtoupper($order->payment_status ?? 'pending') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Card -->
    <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-3" style="background: var(--surface-1);">
        <h6 class="fw-bold text-light mb-3" style="font-size: 0.9rem;">{{ __('Order Items') }} ({{ $order->items->count() }})</h6>
        <div class="d-flex flex-column gap-3 mb-3">
            @foreach($order->items as $item)
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 48px; height: 48px; background: var(--surface-2);">
                        @if($item->meal && $item->meal->relationLoaded('image') && $item->meal->image)
                            <img src="{{ asset('storage/' . $item->meal->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="Meal">
                        @else
                            <img src="{{ asset('assets/images/placeholder.svg') }}" class="w-100 h-100 object-fit-cover" alt="Meal">
                        @endif
                    </div>
                    <div class="flex-grow-1" style="min-width: 0;">
                        <h6 class="fw-semibold text-light mb-1 small text-truncate">{{ $item->meal_name ?? optional($item->meal)->name }}</h6>
                        <small class="text-secondary" style="font-size: 0.72rem;">{{ money($item->price) }} × {{ $item->quantity }}</small>
                    </div>
                    <span class="fw-bold text-primary small flex-shrink-0">{{ money($item->price * $item->quantity) }}</span>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-secondary border-opacity-10">
            <span class="fw-bold text-light">{{ __('Total Amount') }}</span>
            <span class="fw-bold text-primary h5 mb-0">{{ money($order->total) }}</span>
        </div>
    </div>

    <!-- Order Timing Card -->
    <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-4" style="background: var(--surface-1);">
        <h6 class="fw-bold text-light mb-2" style="font-size: 0.85rem;">{{ __('Order Timing') }}</h6>
        <div class="d-flex justify-content-between align-items-center text-secondary small">
            <span>{{ __('Order Date') }}</span>
            <span class="text-light">{{ $order->created_at->format('M d, Y · H:i') }}</span>
        </div>
    </div>
</div>

{{-- ================= DESKTOP VIEW (≥768px, UNTOUCHED) ================= --}}
<div class="container py-5 d-none d-md-block">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold text-light mb-1">{{ __('Order Details') }} #{{ $order->id }}</h1>
            <p class="text-secondary small mb-0">{{ __('Review customer order details and manage preparation status') }}</p>
        </div>
        <a href="{{ route('chef.orders.index') }}" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to List') }}
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Items Card -->
            <div class="card p-0 rounded-4 border border-secondary border-opacity-10 shadow-sm overflow-hidden mb-4" style="background: var(--surface-1);">
                <div class="p-4 border-bottom border-secondary border-opacity-10" style="background: var(--surface-2);">
                    <h5 class="fw-bold mb-0 text-light">{{ __('Order Items') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 text-light" style="--bs-table-bg: transparent;">
                            <thead>
                                <tr class="text-secondary small text-uppercase" style="border-bottom: 1px solid var(--border-subtle);">
                                    <th class="p-3 ps-4 border-0">{{ __('Meal') }}</th>
                                    <th class="p-3 border-0">{{ __('Price') }}</th>
                                    <th class="p-3 text-center border-0">{{ __('Quantity') }}</th>
                                    <th class="p-3 pe-4 text-end border-0">{{ __('Subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr style="border-bottom: 1px solid var(--border-subtle);">
                                    <td class="p-3 ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 48px; height: 48px; background: var(--surface-2);">
                                                @if($item->meal && $item->meal->relationLoaded('image') && $item->meal->image)
                                                    <img src="{{ asset('storage/' . $item->meal->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="Meal">
                                                @else
                                                    <img src="{{ asset('assets/images/placeholder.svg') }}" class="w-100 h-100 object-fit-cover" alt="Meal">
                                                @endif
                                            </div>
                                            <div class="fw-semibold text-light">{{ $item->meal_name ?? optional($item->meal)->name }}</div>
                                        </div>
                                    </td>
                                    <td class="p-3 text-light">{{ money($item->price) }}</td>
                                    <td class="p-3 text-center text-light">{{ $item->quantity }}</td>
                                    <td class="p-3 pe-4 text-end fw-bold text-primary">{{ money($item->price * $item->quantity) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="border-top: 1px solid var(--border-subtle); background: var(--surface-2);">
                                    <td colspan="3" class="p-3 ps-4 text-end fw-bold text-light">{{ __('Total Amount') }}:</td>
                                    <td class="p-3 pe-4 text-end fw-bold text-primary h5 mb-0">{{ money($order->total) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card p-4 rounded-4 border border-secondary border-opacity-10 shadow-sm" style="background: var(--surface-1);">
                <h5 class="fw-bold mb-3 text-light">{{ __('Customer Information') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-secondary small d-block mb-1">{{ __('Customer Name') }}</label>
                        <span class="fw-semibold text-light">{{ $order->user->name }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small d-block mb-1">{{ __('Phone Number') }}</label>
                        <span class="fw-semibold text-light">{{ $order->phone ?? $order->user->phone ?? '-' }}</span>
                    </div>
                    <div class="col-12">
                        <label class="text-secondary small d-block mb-1">{{ __('Delivery Address') }}</label>
                        <span class="fw-semibold text-light">{{ $order->address }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small d-block mb-1">{{ __('Payment Method') }}</label>
                        <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary border-opacity-10">{{ strtoupper($order->payment_method ?? 'CASH') }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small d-block mb-1">{{ __('Payment Status') }}</label>
                        <span class="badge bg-{{ ($order->payment_status ?? 'pending') == 'paid' ? 'success' : 'warning' }} bg-opacity-20 text-{{ ($order->payment_status ?? 'pending') == 'paid' ? 'success' : 'warning' }}">{{ strtoupper($order->payment_status ?? 'pending') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Action Center -->
            <div class="card p-4 rounded-4 border border-secondary border-opacity-10 shadow-sm mb-4" style="background: var(--surface-1);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-light">{{ __('Action Center') }}</h5>
                    <x-ui.badge :status="$order->status" />
                </div>

                @php $allowedStatuses = \App\Services\OrderStatusService::getAllowedStatuses($order->status); @endphp
                @if(count($allowedStatuses) > 0)
                <form action="{{ route('chef.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label small text-secondary fw-semibold">{{ __('Update Status') }}</label>
                        <select name="status" class="form-select text-light border-secondary border-opacity-25" style="background: var(--surface-2);">
                            @foreach($allowedStatuses as $status)
                                <option value="{{ $status }}">{{ __('order.status.' . $status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold shadow-lg" data-magnetic>{{ __('Update Status') }}</button>
                </form>
                @else
                <p class="text-secondary small mb-0">{{ __('No further status updates available.') }}</p>
                @endif
            </div>

            <!-- Summary Meta Card -->
            <div class="card p-4 rounded-4 border border-secondary border-opacity-10 shadow-sm" style="background: var(--surface-1);">
                <h6 class="fw-bold text-light mb-3">{{ __('Order Timing') }}</h6>
                <div class="d-flex justify-content-between align-items-center mb-2 text-secondary small">
                    <span>{{ __('Order Date') }}</span>
                    <span class="text-light fw-medium">{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center text-secondary small">
                    <span>{{ __('Order Time') }}</span>
                    <span class="text-light fw-medium">{{ $order->created_at->format('H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
