<x-app-layout>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Details</h5>
                    <span class="badge bg-info text-dark text-uppercase">
                        {{ $user->role }}
                    </span>
                </div>

                <div class="card-body">

                    {{-- User Image --}}
                    @if ($user->image)
                            <img src="{{ asset('storage/' . $user->image->image_path) }}" class="img-thumbnail"
                                style="max-width: 300px">
                        @else
                            <span class="text-muted">No image</span>
                        @endif

                    {{-- User Info --}}
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $user->phone }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary">
                            Back
                        </a>

                        <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-primary">
                            Edit
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
