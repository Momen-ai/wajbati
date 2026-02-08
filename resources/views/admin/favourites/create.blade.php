<x-app-layout>
    <div class="container mt-5 col-md-8">

        <div class="card">
            <div class="card-header bg-dark text-white">Add Favourite</div>

            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.favourites.store') }}">
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
                        <label>Chef</label>
                        <select name="chef_id" class="form-select">
                            @foreach ($chefs as $chef)
                                <option value="{{ $chef->id }}">{{ $chef->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary">Save</button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
