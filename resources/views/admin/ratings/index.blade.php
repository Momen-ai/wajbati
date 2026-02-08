<x-app-layout>
    <div class="card shadow-sm">

        <div class="card-body d-flex justify-content-between">
            <h4 class="fw-bold">Ratings</h4>
            <a href="{{ route('dashboard.ratings.create') }}" class="btn btn-success">
                Add Rating
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Chef</th>
                        <th>Stars</th>
                        <th>Comment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ratings as $rating)
                        <tr>
                            <td>{{ $rating->id }}</td>
                            <td>{{ $rating->user->name }}</td>
                            <td>{{ $rating->chef->name }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $rating->star }} ★
                                </span>
                            </td>
                            <td>{{ $rating->body ?? '-' }}</td>
                            <td>
                                <a href="{{ route('dashboard.ratings.edit', $rating->id) }}"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('dashboard.ratings.destroy', $rating->id) }}"
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
                                No ratings found
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
