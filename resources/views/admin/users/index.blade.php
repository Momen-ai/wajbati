<x-app-layout>
    <div class="card shadow-sm mb-4">

        {{-- Header --}}
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h4 class="mb-0 fw-bold">Users Management</h4>

            <div class="btn-group">
                <a href="{{ route('dashboard.users.index') }}"
                    class="btn btn-outline-secondary {{ request('role') == null ? 'active' : '' }}">
                    All
                </a>
                <a href="{{ route('dashboard.users.index', ['role' => 'admin']) }}"
                    class="btn btn-outline-secondary {{ request('role') == 'admin' ? 'active' : '' }}">
                    Admins
                </a>
                <a href="{{ route('dashboard.users.index', ['role' => 'chef']) }}"
                    class="btn btn-outline-secondary {{ request('role') == 'chef' ? 'active' : '' }}">
                    Chefs
                </a>
                <a href="{{ route('dashboard.users.index', ['role' => 'user']) }}"
                    class="btn btn-outline-secondary {{ request('role') == 'user' ? 'active' : '' }}">
                    Users
                </a>
            </div>

            <a href="{{ route('dashboard.users.create') }}" class="btn btn-success">
                Add User
            </a>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">

                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th style="width: 120px">Role</th>
                        <th style="width: 220px">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                <span
                                    class="badge
                            {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'chef' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <a class="btn btn-sm btn-primary"
                                        href="{{ route('dashboard.users.edit', $user->id) }}">
                                        Edit
                                    </a>

                                    <a class="btn btn-sm btn-outline-light"
                                        href="{{ route('dashboard.users.show', $user->id) }}">
                                        Show
                                    </a>

                                    @if ($user->role !== 'admin')
                                        <form method="POST"
                                            action="{{ route('dashboard.users.destroy', $user->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

</x-app-layout>
