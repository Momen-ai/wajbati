<x-app-layout>
<div class="container mt-4">

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        Order #{{ $order->id }}
    </div>

    <div class="card-body">
        <p><strong>User:</strong> {{ $order->user->name }}</p>
        <p><strong>Chef:</strong> {{ $order->chef->name }}</p>
        <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        <p><strong>Total:</strong> ${{ $order->total }}</p>

        <hr>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Meal</th>
                    <th>Qty</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->meal->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ $item->price }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>
</div>

</div>
</x-app-layout>

