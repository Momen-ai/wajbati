@extends('layouts.frontend')

@section('content')
    <!-- MOBILE VIEW (<768px): Order Details & Tracking -->
    <div class="d-md-none px-3 pt-3 pb-5 has-bottom-bar" style="background: #0a0a0a; min-height: 100vh;">
        <!-- Top Nav Bar -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="{{ route('orders.index') }}"
               class="btn d-inline-flex align-items-center justify-content-center rounded-circle border border-secondary border-opacity-25"
               style="width: 40px; height: 40px; background: rgba(21, 21, 21, 0.75); color: #f2f2f2;">
                <i class="fas fa-chevron-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }}"></i>
            </a>
            <span class="fw-bold text-light small">{{ __('Order') }} #{{ $order->id }}</span>
            <span style="width: 40px;"></span>
        </div>

        @php
            $isCancelledOrRejected = in_array($order->status, ['cancelled', 'rejected']);
            $currentStep = match($order->status) {
                'pending' => 1,
                'accepted', 'preparing' => 2,
                'ready_for_delivery', 'prepared', 'out_for_delivery' => 3,
                'delivered', 'completed' => 4,
                default => 1,
            };

            $mobileSteps = [
                1 => ['label' => __('Order Placed'), 'icon' => 'fa-receipt'],
                2 => ['label' => __('Preparing'), 'icon' => 'fa-fire-burner'],
                3 => ['label' => __('On the Way'), 'icon' => 'fa-moped'],
                4 => ['label' => __('Delivered'), 'icon' => 'fa-box-check'],
            ];
        @endphp

        <!-- Tracking Timeline Card -->
        <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-3 shadow-sm" style="background: var(--surface-1);">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="fw-bold text-light mb-0" style="font-size: 0.95rem;">{{ __('Order Tracking') }}</h6>
                    <small class="text-secondary" style="font-size: 0.72rem;">{{ $order->created_at->format('M d, Y H:i') }}</small>
                </div>
                <x-ui.badge :status="$order->status" />
            </div>

            @if($isCancelledOrRejected)
                <div class="alert p-3 rounded-3 border border-danger border-opacity-25 bg-danger bg-opacity-10 text-danger d-flex align-items-center gap-2 mb-0 small">
                    <i class="fas fa-times-circle"></i>
                    <span>{{ __('Order is cancelled or rejected') }}</span>
                </div>
            @else
                <div class="d-flex justify-content-between text-center pt-2">
                    @foreach($mobileSteps as $stepNum => $step)
                        @php
                            $isCompleted = $currentStep >= $stepNum;
                            $isCurrent = $currentStep === $stepNum;
                        @endphp
                        <div class="flex-grow-1">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-1"
                                 style="width: 36px; height: 36px;
                                        background: {{ $isCompleted ? 'var(--primary)' : 'var(--surface-2)' }};
                                        color: {{ $isCompleted ? '#1b0e03' : 'var(--text-muted)' }};">
                                <i class="fas {{ $step['icon'] }} small" style="font-size: 0.75rem;"></i>
                            </div>
                            <span class="d-block fw-semibold {{ $isCompleted ? 'text-light' : 'text-secondary opacity-60' }}" style="font-size: 0.68rem;">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Order Items Card -->
        <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-3" style="background: var(--surface-1);">
            <h6 class="fw-bold text-light mb-3" style="font-size: 0.95rem;">{{ __('Order Items') }} ({{ $order->items->count() }})</h6>
            <div class="d-flex flex-column gap-2">
                @foreach($order->items as $item)
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border border-secondary border-opacity-10" style="background: var(--surface-2);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 48px; height: 48px; background: var(--surface-1);">
                                @if($item->meal && $item->meal->relationLoaded('image') && $item->meal->image)
                                    <img src="{{ asset('storage/' . $item->meal->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->meal->name }}">
                                @else
                                    <img src="{{ asset('assets/images/placeholder.svg') }}" class="w-100 h-100 object-fit-cover" alt="Meal">
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold text-light mb-0 small text-truncate" style="max-width: 130px;">{{ $item->meal->name ?? __('Meal') }}</h6>
                                <small class="text-secondary" style="font-size: 0.72rem;">{{ money($item->price) }} × {{ $item->quantity }}</small>
                            </div>
                        </div>
                        <span class="fw-bold text-primary small">{{ money($item->price * $item->quantity) }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-4" style="background: var(--surface-1);">
            <h6 class="fw-bold text-light mb-3" style="font-size: 0.95rem;">{{ __('Order Summary') }}</h6>
            <div class="d-flex justify-content-between mb-2 small text-secondary">
                <span>{{ __('Subtotal') }}</span>
                <span class="text-light fw-medium">{{ money($order->total - $order->delivery_fee) }}</span>
            </div>
            <div class="d-flex justify-content-between mb-2 small text-secondary">
                <span>{{ __('Delivery Fee') }}</span>
                <span class="text-light fw-medium">{{ money($order->delivery_fee) }}</span>
            </div>
            <hr class="border-secondary border-opacity-15 my-2">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-light">{{ __('Total') }}</span>
                <span class="fw-bold text-primary h5 mb-0">{{ money($order->total) }}</span>
            </div>
        </div>

        @if($order->status === 'completed' || $order->status === 'delivered')
            <form action="{{ route('orders.reorder', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-bold shadow-lg">
                    <i class="fas fa-redo me-1"></i> {{ __('Reorder Items') }}
                </button>
            </form>
        @endif
    </div>

    <!-- DESKTOP VIEW (>=768px): Strictly Unchanged -->
    <div class="container py-5 d-none d-md-block">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-secondary hover-text-primary">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-secondary hover-text-primary">{{ __('My Orders') }}</a></li>
            <li class="breadcrumb-item active text-light" aria-current="page">{{ __('Order') }} #{{ $order->id }}</li>
        </ol>
    </nav>

    <!-- Visual Order Status Timeline -->
    @php
        $isCancelledOrRejected = in_array($order->status, ['cancelled', 'rejected']);
        $currentStep = match($order->status) {
            'pending' => 1,
            'accepted', 'preparing' => 2,
            'ready_for_delivery', 'prepared', 'out_for_delivery' => 3,
            'delivered', 'completed' => 4,
            default => 1,
        };

        $steps = [
            1 => ['label' => __('Order Placed'), 'icon' => 'fa-receipt'],
            2 => ['label' => __('Preparing'), 'icon' => 'fa-fire-burner'],
            3 => ['label' => __('On the Way'), 'icon' => 'fa-moped'],
            4 => ['label' => __('Delivered'), 'icon' => 'fa-box-check'],
        ];
    @endphp

    <div class="card p-4 rounded-4 border border-secondary border-opacity-10 mb-4 shadow-sm" style="background: var(--surface-1);">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1 text-light">{{ __('Order Tracking') }} #{{ $order->id }}</h4>
                <span class="text-secondary small">{{ __('Placed on') }} {{ $order->created_at->format('M d, Y H:i') }}</span>
            </div>
            <x-ui.badge :status="$order->status" />
        </div>

        @if($isCancelledOrRejected)
            <div class="alert p-3 rounded-3 border border-danger border-opacity-25 bg-danger bg-opacity-10 text-danger d-flex align-items-center gap-3 mb-0">
                <i class="fas fa-times-circle fa-2x"></i>
                <div>
                    <h6 class="fw-bold mb-0">{{ __('Order is cancelled or rejected') }}</h6>
                    <small>{{ __('This order is not currently active.') }}</small>
                </div>
            </div>
        @else
            <!-- Multi-step progression bar -->
            <div class="position-relative py-3">
                <div class="row text-center position-relative" style="z-index: 2;">
                    @foreach($steps as $stepNum => $step)
                        @php
                            $isCompleted = $currentStep >= $stepNum;
                            $isCurrent = $currentStep === $stepNum;
                        @endphp
                        <div class="col-3">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2 shadow-sm transition-all"
                                style="width: 44px; height: 44px; 
                                       background: {{ $isCompleted ? 'var(--primary)' : 'var(--surface-2)' }}; 
                                       color: {{ $isCompleted ? '#fff' : 'var(--text-muted)' }};
                                       border: 2px solid {{ $isCurrent ? 'var(--primary)' : ($isCompleted ? 'var(--primary)' : 'var(--border-subtle)') }};
                                       box-shadow: {{ $isCurrent ? '0 0 15px var(--primary-glow)' : 'none' }};">
                                <i class="fas {{ $step['icon'] }} small"></i>
                            </div>
                            <span class="small d-block fw-semibold {{ $isCompleted ? 'text-light' : 'text-secondary opacity-60' }}" style="font-size: 0.78rem;">
                                {{ $step['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="row g-4">
        <!-- Order Items Column -->
        <div class="col-lg-8">
            <div class="card p-0 rounded-4 border border-secondary border-opacity-10 mb-4 overflow-hidden shadow-sm" style="background: var(--surface-1);">
                <div class="p-4 border-bottom border-secondary border-opacity-10" style="background: var(--surface-2);">
                    <h5 class="fw-bold mb-0 text-light">{{ __('Order Items') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0 text-light" style="--bs-table-bg: transparent;">
                            <thead>
                                <tr class="text-secondary small text-uppercase" style="border-bottom: 1px solid var(--border-subtle);">
                                    <th class="p-3 ps-4 border-0">{{ __('Meal') }}</th>
                                    <th class="p-3 text-center border-0">{{ __('Price') }}</th>
                                    <th class="p-3 text-center border-0">{{ __('Qty') }}</th>
                                    <th class="p-3 text-end pe-4 border-0">{{ __('Total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr style="border-bottom: 1px solid var(--border-subtle);">
                                    <td class="p-3 ps-4">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 50px; height: 50px; background: var(--surface-2);">
                                                @if($item->meal && $item->meal->relationLoaded('image') && $item->meal->image)
                                                    <img src="{{ asset('storage/' . $item->meal->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $item->meal->name }}">
                                                @else
                                                    <img src="{{ asset('assets/images/placeholder.svg') }}" class="w-100 h-100 object-fit-cover" alt="Meal">
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-light">{{ $item->meal_name ?? ($item->meal->name ?? __('Meal')) }}</h6>
                                                <small class="text-secondary">{{ $order->chef->kitchen_name ?? $order->chef->name ?? __('Chef') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3 text-center text-light">{{ money($item->price) }}</td>
                                    <td class="p-3 text-center text-light">{{ $item->quantity }}</td>
                                    <td class="p-3 text-end pe-4 fw-bold text-primary">{{ money($item->price * $item->quantity) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Delivery Information -->
            <div class="card p-4 rounded-4 border border-secondary border-opacity-10 shadow-sm" style="background: var(--surface-1);">
                <h5 class="fw-bold mb-3 text-light">{{ __('Delivery Information') }}</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="text-secondary small d-block mb-1">{{ __('Address') }}</label>
                        <span class="fw-semibold text-light">{{ $order->address }}</span>
                    </div>
                    <div class="col-md-6">
                        <label class="text-secondary small d-block mb-1">{{ __('Phone Number') }}</label>
                        <span class="fw-semibold text-light">{{ $order->phone ?? $order->user->phone }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary Column -->
        <div class="col-lg-4">
            <div class="card p-4 rounded-4 border border-secondary border-opacity-10 mb-4 shadow-sm" style="background: var(--surface-1);">
                <h5 class="fw-bold mb-4 text-light">{{ __('Order Summary') }}</h5>
                <div class="d-flex justify-content-between mb-2 text-secondary">
                    <span>{{ __('Subtotal') }}</span>
                    <span class="fw-medium text-light">{{ money($order->subtotal) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3 text-secondary">
                    <span>{{ __('Delivery Fee') }}</span>
                    <span class="fw-medium text-light">{{ money($order->delivery_fee) }}</span>
                </div>
                <hr class="border-secondary border-opacity-15 my-3">
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold h5 mb-0 text-light">{{ __('Total') }}</span>
                    <span class="fw-bold h4 text-primary mb-0">{{ money($order->total) }}</span>
                </div>

                <div class="rounded-3 p-3 mb-4 border border-secondary border-opacity-10" style="background: var(--surface-2);">
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-secondary">{{ __('Payment Method') }}</span>
                        <span class="badge bg-secondary text-uppercase">{{ __($order->payment_method) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small">
                        <span class="text-secondary">{{ __('Payment Status') }}</span>
                        <span class="badge bg-info bg-opacity-20 text-info text-uppercase">{{ __($order->payment_status) }}</span>
                    </div>
                </div>

                @if($order->status == 'pending')
                    <div class="alert alert-warning mb-3 small border-0 bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-clock me-1"></i> {{ __('Your order is waiting for chef\'s approval.') }}
                    </div>
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to cancel this order?') }}');">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill mb-2 fw-semibold">
                            <i class="fas fa-times-circle me-1"></i> {{ __('Cancel Order') }}
                        </button>
                    </form>
                @endif

                @if(in_array($order->status, ['delivered', 'completed']))
                    <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold" data-magnetic>
                            <i class="fas fa-redo me-1"></i> {{ __('Reorder Items') }}
                        </button>
                    </form>
                @endif

                <div class="d-grid mt-3">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary border-opacity-25 text-light rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i> {{ __('Back to My Orders') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
