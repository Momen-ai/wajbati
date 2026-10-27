<x-app-layout>
<div class="container mt-4">

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        {{ __('Order') }} #{{ $order->id }}
    </div>

    <div class="card-body">
        <p><strong>{{ __('User') }}:</strong> {{ $order->user?->name ?? __('Unknown') }}</p>
        <p><strong>{{ __('Chef') }}:</strong> {{ $order->chef?->name ?? __('Unknown') }}</p>
        <p><strong>{{ __('Status') }}:</strong> {{ __($order->status) }}</p>
        <p><strong>{{ __('Total') }}:</strong> {{ money($order->total) }}</p>

        <hr>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Meal') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Price') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->meal?->name ?? __('Meal') }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ money($item->price) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary">
            {{ __('Back') }}
        </a>
    </div>
</div>

</div>
</x-app-layout>
