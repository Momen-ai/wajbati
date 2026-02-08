<x-app-layout>
    <div class="card shadow-sm">

        <div class="card-body d-flex justify-content-between">
            <h4 class="fw-bold">Cart</h4>
            <a href="{{ route('dashboard.cart.create') }}" class="btn btn-success">
                Add Item
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Meal</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($carts as $cart)
                        <tr>
                            <td>{{ $cart->id }}</td>
                            <td>{{ $cart->user->name }}</td>
                            <td>{{ $cart->meal->name }}</td>
                            <td>{{ $cart->meal->price }}</td>
                            <td>{{ $cart->quantity }}</td>
                            <td>{{ $cart->meal->price * $cart->quantity }}</td>
                            <td>
                                <a href="{{ route('dashboard.cart.edit', $cart->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('dashboard.cart.destroy', $cart->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">
                                No carts found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</x-app-layout>
