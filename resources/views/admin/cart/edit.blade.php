<x-app-layout>
    <div class="container mt-5 col-md-8">

        <div class="card">
            <div class="card-header bg-dark text-white">Edit Cart</div>

            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.cart.update', $cart->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>User</label>
                        <select name="user_id" class="form-select">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected($cart->user_id == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Meal</label>
                        <select name="meal_id" class="form-select">
                            @foreach ($meals as $meal)
                                <option value="{{ $meal->id }}" @selected($cart->meal_id == $meal->id)>
                                    {{ $meal->name }} - {{ $meal->price }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="{{ $cart->quantity }}">
                    </div>

                    <button class="btn btn-primary">Update</button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
