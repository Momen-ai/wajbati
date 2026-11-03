<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header :title="__('Users Management')">
            <x-slot name="actions">
                <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="fas fa-plus me-1"></i> {{ __('Add User') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <!-- Role Filter Pills -->
        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            <a href="{{ route('dashboard.users.index') }}"
                class="btn btn-sm rounded-pill px-3 {{ request('role') == null ? 'btn-primary' : 'btn-outline-subtle text-secondary' }}">
                {{ __('All') }}
            </a>
            <a href="{{ route('dashboard.users.index', ['role' => 'admin']) }}"
                class="btn btn-sm rounded-pill px-3 {{ request('role') == 'admin' ? 'btn-primary' : 'btn-outline-subtle text-secondary' }}">
                {{ __('Admins') }}
            </a>
            <a href="{{ route('dashboard.users.index', ['role' => 'chef']) }}"
                class="btn btn-sm rounded-pill px-3 {{ request('role') == 'chef' ? 'btn-primary' : 'btn-outline-subtle text-secondary' }}">
                {{ __('Chefs') }}
            </a>
            <a href="{{ route('dashboard.users.index', ['role' => 'user']) }}"
                class="btn btn-sm rounded-pill px-3 {{ request('role') == 'user' ? 'btn-primary' : 'btn-outline-subtle text-secondary' }}">
                {{ __('Users') }}
            </a>
        </div>

        <!-- Table Card -->
        <div class="table-custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-4" style="width: 80px">{{ __('ID') }}</th>
                            <th class="text-start ps-3">{{ __('Name') }}</th>
                            <th class="text-start ps-3">{{ __('Email') }}</th>
                            <th class="text-start ps-3">{{ __('Phone') }}</th>
                            <th class="text-start ps-3" style="width: 120px">{{ __('Role') }}</th>
                            <th class="text-end pe-4" style="width: 180px">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td class="text-start ps-4 text-secondary fw-semibold">#{{ $user->id }}</td>
                                <td class="text-start ps-3 fw-semibold text-main">{{ $user->name }}</td>
                                <td class="text-start ps-3 text-secondary">{{ $user->email }}</td>
                                <td class="text-start ps-3 text-secondary">{{ $user->phone ?? '-' }}</td>
                                <td class="text-start ps-3">
                                    <span class="badge {{ $user->role === 'admin' ? 'bg-danger text-white' : ($user->role === 'chef' ? 'bg-warning text-dark' : 'bg-secondary text-white') }} rounded-pill px-2.5 py-1">
                                        {{ __($user->role) }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                        <a href="{{ route('dashboard.users.show', $user->id) }}"
                                            class="btn-action-ghost-neutral"
                                            title="{{ __('Show') }}">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline ms-1">{{ __('Show') }}</span>
                                        </a>

                                        <a href="{{ route('dashboard.users.edit', $user->id) }}"
                                            class="btn-action-ghost-neutral"
                                            title="{{ __('Edit') }}">
                                            <i class="fas fa-pen"></i>
                                            <span class="d-none d-md-inline ms-1">{{ __('Edit') }}</span>
                                        </a>

                                        @if ($user->role !== 'admin')
                                            <form method="POST" action="{{ route('dashboard.users.destroy', $user->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn-action-ghost-danger"
                                                    title="{{ __('Delete') }}"
                                                    onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <span class="d-none d-md-inline ms-1">{{ __('Delete') }}</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-5">
                                    <i class="fas fa-users fs-2 mb-2 d-block opacity-50"></i>
                                    {{ __('No records found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="p-3 border-top border-subtle">
                    {{ $users->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
