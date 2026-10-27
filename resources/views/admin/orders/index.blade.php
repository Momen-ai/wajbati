<x-app-layout>
    <div class="card shadow-sm">

        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">{{ __('Orders') }}</h4>
            <a href="{{ route('dashboard.orders.create') }}" class="btn btn-success">
                {{ __('Add Order') }}
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Chef') }}</th>
                        <th>{{ __('Total') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th width="220">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user?->name ?? __('Unknown') }}</td>
                        <td>{{ $order->chef?->name ?? __('Unknown') }}</td>
                        <td>{{ money($order->total) }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ __($order->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('dashboard.orders.show', $order) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    {{ __('Show') }}
                                </a>
                                <a href="{{ route('dashboard.orders.edit', $order) }}" class="btn btn-sm btn-primary">
                                    {{ __('Edit') }}
                                </a>
                                <form method="POST" action="{{ route('dashboard.orders.destroy', $order) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this order?') }}')">
                                        {{ __('Delete') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">
                                {{ __('No orders found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($orders, 'links'))
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
        @endif

    </div>
</x-app-layout>
