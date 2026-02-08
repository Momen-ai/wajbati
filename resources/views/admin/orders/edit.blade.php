<x-app-layout>
<div class="container mt-4">

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        Edit Order #{{ $order->id }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('dashboard.orders.update', $order->id) }}">
            @csrf
            @method('PUT')

            {{-- User --}}
            <div class="mb-3">
                <label class="form-label">User</label>
                <select name="user_id" class="form-select" disabled>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}"
                            @selected($user->id === $order->user_id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">
                    User cannot be changed after order creation
                </small>
            </div>

            {{-- Chef --}}
            <div class="mb-3">
                <label class="form-label">Chef</label>
                <select name="chef_id" class="form-select" disabled>
                    @foreach ($chefs as $chef)
                        <option value="{{ $chef->id }}"
                            @selected($chef->id === $order->chef_id)>
                            {{ $chef->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Address --}}
            <div class="mb-3">
                <label class="form-label">Address</label>
                <input
                    type="text"
                    name="address"
                    class="form-control"
                    value="{{ old('address', $order->address) }}"
                >
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach (['pending','accepted','preparing','prepared','delivered','completed','rejected','cancelled'] as $status)
                        <option value="{{ $status }}"
                            @selected($order->status === $status)>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Order Items (Read Only) --}}
            <hr>
            <h6 class="fw-bold">Order Items</h6>

            <table class="table table-bordered text-center">
                <thead class="table-light">
                    <tr>
                        <th>Meal</th>
                        <th>Quantity</th>
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

            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('dashboard.orders.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
                <button class="btn btn-primary">
                    Update Order
                </button>
            </div>

        </form>
    </div>
</div>

</div>
</x-app-layout>

