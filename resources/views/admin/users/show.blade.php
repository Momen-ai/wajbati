<x-app-layout>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('User Details') }}</h5>
                    <span class="badge bg-info text-dark text-uppercase">
                        {{ __($user->role) }}
                    </span>
                </div>

                <div class="card-body">

                    {{-- User Image --}}
                    @if ($user->image)
                        <img src="{{ asset('storage/' . $user->image->image_path) }}" class="img-thumbnail"
                            style="max-width: 300px">
                    @else
                        <span class="text-muted">{{ __('No image') }}</span>
                    @endif

                    {{-- User Info --}}
                    <table class="table table-bordered mt-3">
                        <tr>
                            <th width="30%">{{ __('Name') }}</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Email') }}</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Phone') }}</th>
                            <td>{{ $user->phone }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Role') }}</th>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ __($user->role) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ __('Created At') }}</th>
                            <td>{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary">
                            {{ __('Back') }}
                        </a>

                        <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-primary">
                            {{ __('Edit') }}
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
