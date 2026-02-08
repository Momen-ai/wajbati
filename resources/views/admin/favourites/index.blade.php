<x-app-layout>
    <div class="card shadow-sm">

        <div class="card-body d-flex justify-content-between">
            <h4 class="fw-bold">Favourites</h4>
            <a href="{{ route('dashboard.favourites.create') }}" class="btn btn-success">
                Add Favourite
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">

                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Chef</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($favourites as $fav)
                        <tr>
                            <td>{{ $fav->id }}</td>
                            <td>{{ $fav->user->name }}</td>
                            <td>{{ $fav->chef->name }}</td>
                            <td>
                                <a href="{{ route('dashboard.favourites.edit', $fav->id) }}"
                                    class="btn btn-sm btn-warning">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('dashboard.favourites.destroy', $fav->id) }}"
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
                                No favourites found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>





        </div>
    </div>
</x-app-layout>
