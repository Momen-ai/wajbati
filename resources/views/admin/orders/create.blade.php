<x-app-layout>
<div class="container mt-4">

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        Create Order
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('dashboard.orders.store') }}">
            @csrf

            <div class="mb-3">
                <label>User</label>
                <select name="user_id" class="form-select" required>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Chef</label>
                <select name="chef_id" class="form-select" required>
                    @foreach ($chefs as $chef)
                        <option value="{{ $chef->id }}">{{ $chef->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Address</label>
                <input type="text" name="address" class="form-control">
            </div>

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="accepted">Accepted</option>
                    <option value="preparing">Preparing</option>
                    <option value="delivered">Delivered</option>
                </select>
            </div>

            <hr>

            <h6>Order Items</h6>

            @foreach ($meals as $meal)
                <div class="d-flex gap-3 mb-2">
                    <input type="hidden" name="items[{{ $loop->index }}][meal_id]" value="{{ $meal->id }}">
                    <span class="flex-grow-1">{{ $meal->name }}</span>
                    <input type="number" name="items[{{ $loop->index }}][quantity]" min="0" class="form-control" style="width:100px">
                </div>
            @endforeach

            <div class="mt-4 text-end">
                <button class="btn btn-primary">Create Order</button>
            </div>
        </form>
    </div>
</div>

</div>
</x-app-layout>

