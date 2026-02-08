<x-app-layout>
    <div class="container mt-5 col-md-8">

        <div class="card">
            <div class="card-header bg-dark text-white">Add To Cart</div>

            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.cart.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label>User</label>
                        <select name="user_id" class="form-select">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Meal</label>
                        <select name="meal_id" class="form-select">
                            @foreach ($meals as $meal)
                                <option value="{{ $meal->id }}">
                                    {{ $meal->name }} - {{ $meal->price }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1">
                    </div>

                    <button class="btn btn-primary">Add</button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
