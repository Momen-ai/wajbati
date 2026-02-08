<x-app-layout>
    <div class="card shadow-sm">

        <div class="card-body d-flex justify-content-between">
            <h4 class="fw-bold">Orders</h4>
            <a href="{{ route('dashboard.orders.create') }}" class="btn btn-success">
                Add Order
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Chef</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="220">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->chef->name }}</td>
                        <td>${{ $order->total }}</td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('dashboard.orders.show', $order) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    Show
                                </a>
                                <a href="{{ route('dashboard.orders.edit', $order) }}" class="btn btn-sm btn-primary">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('dashboard.orders.destroy', $order) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">
                                No orders found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- <div class="card-footer">
            {{ $orders->links() }}
        </div> --}}

    </div>
</x-app-layout>
