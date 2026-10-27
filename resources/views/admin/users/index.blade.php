<x-app-layout>
    <div class="card shadow-sm mb-4">

        {{-- Header --}}
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h4 class="mb-0 fw-bold">{{ __('Users Management') }}</h4>

            <div class="btn-group">
                <a href="{{ route('dashboard.users.index') }}"
                    class="btn btn-outline-secondary {{ request('role') == null ? 'active' : '' }}">
                    {{ __('All') }}
                </a>
                <a href="{{ route('dashboard.users.index', ['role' => 'admin']) }}"
                    class="btn btn-outline-secondary {{ request('role') == 'admin' ? 'active' : '' }}">
                    {{ __('Admins') }}
                </a>
                <a href="{{ route('dashboard.users.index', ['role' => 'chef']) }}"
                    class="btn btn-outline-secondary {{ request('role') == 'chef' ? 'active' : '' }}">
                    {{ __('Chefs') }}
                </a>
                <a href="{{ route('dashboard.users.index', ['role' => 'user']) }}"
                    class="btn btn-outline-secondary {{ request('role') == 'user' ? 'active' : '' }}">
                    {{ __('Users') }}
                </a>
            </div>

            <a href="{{ route('dashboard.users.create') }}" class="btn btn-success">
                {{ __('Add User') }}
            </a>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">

                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px">{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th style="width: 120px">{{ __('Role') }}</th>
                        <th style="width: 220px">{{ __('Actions') }}</th>
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
                                    {{ __($user->role) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <a class="btn btn-sm btn-primary"
                                        href="{{ route('dashboard.users.edit', $user->id) }}">
                                        {{ __('Edit') }}
                                    </a>

                                    <a class="btn btn-sm btn-outline-secondary"
                                        href="{{ route('dashboard.users.show', $user->id) }}">
                                        {{ __('Show') }}
                                    </a>

                                    @if ($user->role !== 'admin')
                                        <form method="POST"
                                            action="{{ route('dashboard.users.destroy', $user->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                                {{ __('Delete') }}
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

        <div class="mt-4">
            {{ $users->withQueryString()->links() }}
        </div>

    </div>

</x-app-layout>
