<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header
            :title="__('Orders')"
            :subtitle="__('Track customer orders, delivery statuses, and payment totals.')">
            <x-slot name="actions">
                <a href="{{ route('dashboard.orders.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="fas fa-plus me-1"></i> {{ __('Add Order') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <!-- Table Card -->
        <div class="table-custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-4" style="width: 90px">{{ __('ID') }}</th>
                            <th class="text-start ps-3">{{ __('Customer') }}</th>
                            <th class="text-start ps-3">{{ __('Chef') }}</th>
                            <th class="text-start ps-3">{{ __('Total') }}</th>
                            <th class="text-start ps-3">{{ __('Status') }}</th>
                            <th class="text-end pe-4" style="width: 250px">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr>
                            <td class="text-start ps-4 text-secondary fw-semibold">#{{ $order->id }}</td>
                            <td class="text-start ps-3 fw-semibold text-main">{{ $order->user?->name ?? __('Unknown') }}</td>
                            <td class="text-start ps-3 text-secondary">{{ $order->chef?->name ?? __('Unknown') }}</td>
                            <td class="text-start ps-3 fw-bold text-primary">{{ money($order->total) }}</td>
                            <td class="text-start ps-3">
                                <x-ui.badge :status="$order->status" />
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                    <a href="{{ route('dashboard.orders.show', $order) }}"
                                       class="btn-action-ghost btn-action-ghost-neutral">
                                        <i class="fas fa-eye small text-secondary"></i>
                                        <span>{{ __('Show') }}</span>
                                    </a>
                                    <a href="{{ route('dashboard.orders.edit', $order) }}"
                                       class="btn-action-ghost btn-action-ghost-neutral">
                                        <i class="fas fa-edit small text-primary"></i>
                                        <span>{{ __('Edit') }}</span>
                                    </a>
                                    <form method="POST" action="{{ route('dashboard.orders.destroy', $order) }}" class="d-inline m-0"
                                          onsubmit="return confirm('{{ __('Are you sure you want to delete this order?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-ghost btn-action-ghost-danger">
                                            <i class="fas fa-trash-alt small"></i>
                                            <span>{{ __('Delete') }}</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-secondary">
                                    <i class="fas fa-receipt fa-2x mb-2 d-block opacity-40"></i>
                                    {{ __('No orders found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($orders, 'hasPages') ? $orders->hasPages() : (method_exists($orders, 'links') && $orders->count() > 0))
            <div class="p-3 border-top border-subtle">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
