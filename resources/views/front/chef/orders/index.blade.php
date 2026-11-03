@extends('layouts.frontend')

@section('content')
{{-- ================= MOBILE VIEW (<768px) ================= --}}
<div class="d-md-none has-bottom-bar px-3 pt-3 pb-5" style="background: #0a0a0a; min-height: 100vh;">
    <!-- Mobile Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h5 fw-bold text-light mb-0">{{ __('Kitchen Orders') }}</h1>
            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2 py-1 mt-1 small">
                {{ $orders->total() }} {{ __('Total Orders') }}
            </span>
        </div>
        <a href="{{ route('chef.dashboard') }}" class="btn btn-sm d-flex align-items-center justify-content-center rounded-circle border border-subtle text-secondary" style="width: 40px; height: 40px; background: #151515;">
            <i class="fas fa-store"></i>
        </a>
    </div>

    @if($orders->count() > 0)
        <!-- Orders Cards List -->
        <div class="d-flex flex-column gap-3">
            @foreach($orders as $order)
                <div class="p-3 rounded-4 border border-secondary border-opacity-10" style="background: var(--surface-1);">
                    <!-- Top Meta Row -->
                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom border-secondary border-opacity-10">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold font-monospace text-light" style="font-size: 0.9rem;">#{{ $order->id }}</span>
                            <span class="text-secondary small">·</span>
                            <span class="text-secondary small">{{ $order->created_at->format('M d, H:i') }}</span>
                        </div>
                        <x-ui.badge :status="$order->status" />
                    </div>

                    <!-- Customer & Items Row -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary flex-shrink-0" style="width: 28px; height: 28px; background: var(--surface-2);">
                                <i class="fas fa-user" style="font-size: 12px;"></i>
                            </div>
                            <span class="fw-semibold text-light small text-truncate">{{ $order->user->name ?? __('Guest') }}</span>
                            <span class="text-secondary small ms-auto">{{ $order->phone ?? $order->user->phone ?? '-' }}</span>
                        </div>

                        <!-- Items Preview -->
                        <div class="p-2 rounded-3" style="background: var(--surface-2);">
                            <ul class="list-unstyled mb-0" style="font-size: 0.78rem;">
                                @foreach($order->items->take(2) as $item)
                                    <li class="text-light text-truncate mb-1">
                                        <span class="fw-bold text-primary">{{ $item->quantity }}x</span> {{ $item->meal->name ?? $item->meal_name }}
                                    </li>
                                @endforeach
                                @if($order->items->count() > 2)
                                    <li class="text-secondary" style="font-size: 0.72rem;">
                                        + {{ $order->items->count() - 2 }} {{ __('more...') }}
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Bottom Action & Total Row -->
                    <div class="d-flex align-items-center justify-content-between gap-2 pt-2 border-top border-secondary border-opacity-10">
                        <div>
                            <small class="text-secondary d-block" style="font-size: 0.7rem;">{{ __('Total') }}</small>
                            <span class="fw-bold text-primary" style="font-size: 1rem;">{{ money($order->total) }}</span>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <!-- Quick Status Update Form -->
                            <form action="{{ route('chef.orders.update-status', $order->id) }}" method="POST" class="d-inline-flex m-0">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm text-light border-secondary border-opacity-25 rounded-pill"
                                        onchange="this.form.submit()"
                                        style="background: var(--surface-2); font-size: 0.78rem; min-height: 40px; min-width: 110px;">
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

                            <a href="{{ route('chef.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 d-flex align-items-center justify-content-center" style="min-height: 40px;" title="{{ __('View Details') }}">
                                <i class="fas fa-eye me-1"></i> {{ __('Details') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($orders->hasPages())
            <div class="mt-4">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @else
        <div class="py-5">
            <x-ui.empty-state
                icon="fa-clipboard-list"
                :title="__('No orders yet')"
                :message="__('Wait for customers to discover your delicious meals!')" />
        </div>
    @endif
</div>

{{-- ================= DESKTOP VIEW (≥768px, UNTOUCHED) ================= --}}
<div class="container py-5 d-none d-md-block">
    <x-ui.page-header
        :title="__('Kitchen Orders')"
        :subtitle="__('Manage customer orders, update prep status, and track delivery')">
        <x-slot name="actions">
            <span class="badge rounded-pill px-3 py-2 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 fw-bold">
                {{ $orders->total() }} {{ __('Total Orders') }}
            </span>
        </x-slot>
    </x-ui.page-header>

    <div class="row">
        <div class="col-12">
            @if($orders->count() > 0)
            <div class="table-custom-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-start ps-4" style="width: 100px">{{ __('Order ID') }}</th>
                                <th class="text-start ps-3">{{ __('Customer') }}</th>
                                <th class="text-start ps-3">{{ __('Items') }}</th>
                                <th class="text-start ps-3">{{ __('Total') }}</th>
                                <th class="text-start ps-3" style="width: 130px">{{ __('Status') }}</th>
                                <th class="text-start ps-3" style="width: 130px">{{ __('Date') }}</th>
                                <th class="text-end pe-4" style="width: 220px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="text-start ps-4 fw-mono text-secondary">#{{ $order->id }}</td>
                                <td class="text-start ps-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 34px; height: 34px; background: var(--surface-2);">
                                            <i class="fas fa-user small"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-main">{{ $order->user->name ?? __('Guest') }}</div>
                                            <small class="text-secondary">{{ $order->phone ?? $order->user->phone ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-start ps-3">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($order->items->take(2) as $item)
                                        <li class="small text-main">
                                            {{ $item->quantity }}x {{ $item->meal->name }}
                                        </li>
                                        @endforeach
                                        @if($order->items->count() > 2)
                                        <li class="small text-primary">+ {{ $order->items->count() - 2 }} {{ __('more...') }}</li>
                                        @endif
                                    </ul>
                                </td>
                                <td class="text-start ps-3 fw-bold text-primary">{{ money($order->total) }}</td>
                                <td class="text-start ps-3">
                                    <x-ui.badge :status="$order->status" />
                                </td>
                                <td class="text-start ps-3 text-secondary small">{{ $order->created_at->format('M d, H:i') }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                        <form action="{{ route('chef.orders.update-status', $order->id) }}" method="POST" class="d-inline-flex">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm text-main border-subtle rounded-pill" onchange="this.form.submit()" style="background: var(--surface-2); min-width: 120px;">
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
                                        <a href="{{ route('chef.orders.show', $order->id) }}" class="btn-action-ghost-neutral" title="{{ __('View Details') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                <div class="p-3 border-top border-subtle">
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
                @endif
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
