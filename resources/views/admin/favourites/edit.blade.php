<x-app-layout>
    <div class="container mt-5 col-md-8">

        <div class="card">
            <div class="card-header bg-dark text-white">Edit Favourite</div>

            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.favourites.update', $favourite->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>User</label>
                        <select name="user_id" class="form-select">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected($favourite->user_id == $user->id)>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Chef</label>
                        <select name="chef_id" class="form-select">
                            @foreach ($chefs as $chef)
                                <option value="{{ $chef->id }}" @selected($favourite->chef_id == $chef->id)>
                                    {{ $chef->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary">Update</button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
