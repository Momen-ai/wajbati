<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header :title="__('Order') . ' #' . $order->id">
            <x-slot name="actions">
                <a href="{{ route('dashboard.orders.index') }}" class="btn btn-outline-subtle rounded-pill px-4 text-secondary">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i> {{ __('Back to List') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <div class="row g-4">
            <!-- Order Summary Card -->
            <div class="col-12 col-lg-4">
                <div class="spotlight-card rounded-4 p-4 h-100">
                    <h5 class="fw-bold text-main mb-4 pb-2 border-bottom border-subtle">{{ __('Order Details') }}</h5>
                    
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">{{ __('Customer') }}</span>
                            <span class="fw-semibold text-main">{{ $order->user?->name ?? __('Unknown') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">{{ __('Chef') }}</span>
                            <span class="fw-semibold text-main">{{ $order->chef?->name ?? __('Unknown') }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">{{ __('Status') }}</span>
                            <x-ui.badge :status="$order->status" />
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top border-subtle">
                            <span class="text-secondary fw-semibold">{{ __('Total') }}</span>
                            <span class="h4 fw-bold text-primary mb-0">{{ money($order->total) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Table Card -->
            <div class="col-12 col-lg-8">
                <div class="table-custom-card">
                    <div class="p-4 border-bottom border-subtle">
                        <h5 class="fw-bold text-main mb-0">{{ __('Order Items') }}</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-start ps-4">{{ __('Meal') }}</th>
                                    <th class="text-start ps-3" style="width: 120px">{{ __('Quantity') }}</th>
                                    <th class="text-end pe-4" style="width: 140px">{{ __('Price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td class="text-start ps-4 fw-semibold text-main">{{ $item->meal?->name ?? __('Meal') }}</td>
                                        <td class="text-start ps-3 text-secondary">{{ $item->quantity }}</td>
                                        <td class="text-end pe-4 fw-bold text-primary">{{ money($item->price) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
