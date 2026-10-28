@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold text-light mb-1">{{ __('Kitchen Orders') }}</h1>
            <p class="text-secondary small mb-0">{{ __('Manage customer orders, update prep status, and track delivery') }}</p>
        </div>
        <span class="badge rounded-pill px-3 py-2 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 fw-bold">
            {{ $orders->total() }} {{ __('Total Orders') }}
        </span>
    </div>

    <div class="row">
        <div class="col-12">
            @if($orders->count() > 0)
            <div class="card p-0 rounded-4 border border-secondary border-opacity-10 shadow-sm overflow-hidden" style="background: var(--surface-1);">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-light" style="--bs-table-bg: transparent;">
                        <thead>
                            <tr class="text-secondary small text-uppercase" style="border-bottom: 1px solid var(--border-subtle); background: var(--surface-2);">
                                <th class="p-3 ps-4 border-0">{{ __('Order ID') }}</th>
                                <th class="p-3 border-0">{{ __('Customer') }}</th>
                                <th class="p-3 border-0">{{ __('Items') }}</th>
                                <th class="p-3 border-0">{{ __('Total') }}</th>
                                <th class="p-3 border-0">{{ __('Status') }}</th>
                                <th class="p-3 border-0">{{ __('Date') }}</th>
                                <th class="p-3 border-0 text-end pe-4">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr style="border-bottom: 1px solid var(--border-subtle);">
                                <td class="p-3 ps-4 fw-mono text-light">#{{ $order->id }}</td>
                                <td class="p-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 34px; height: 34px; background: var(--surface-2);">
                                            <i class="fas fa-user small"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-light">{{ $order->user->name ?? __('Guest') }}</div>
                                            <small class="text-secondary">{{ $order->phone ?? $order->user->phone ?? '-' }}</small>
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
                                        <li class="small text-primary">+ {{ $order->items->count() - 2 }} {{ __('more...') }}</li>
                                        @endif
                                    </ul>
                                </td>
                                <td class="p-3 fw-bold text-primary">{{ money($order->total) }}</td>
                                <td class="p-3">
                                    <x-ui.badge :status="$order->status" />
                                </td>
                                <td class="p-3 text-secondary small">{{ $order->created_at->format('M d, H:i') }}</td>
                                <td class="p-3 text-end pe-4">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <form action="{{ route('chef.orders.update-status', $order->id) }}" method="POST" class="d-inline-flex">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm text-light border-secondary border-opacity-25 rounded-pill" onchange="this.form.submit()" style="background: var(--surface-2); min-width: 120px;">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                                <option value="accepted" {{ $order->status == 'accepted' ? 'selected' : '' }}>{{ __('Accepted') }}</option>
                                                <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>{{ __('Preparing') }}</option>
                                                <option value="prepared" {{ $order->status == 'prepared' ? 'selected' : '' }}>{{ __('Prepared') }}</option>
                                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                                <option value="rejected" {{ $order->status == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                            </select>
                                        </form>
                                        <a href="{{ route('chef.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" title="{{ __('View Details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
            @else
            <div class="col-12 py-5">
                <x-ui.empty-state
                    icon="fa-clipboard-list"
                    :title="__('No orders yet')"
                    :message="__('Wait for customers to discover your delicious meals!')" />
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
