<x-app-layout>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">{{ __('Edit User') }}</h5>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('dashboard.users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label">{{ __('Name') }}</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label">{{ __('Email') }}</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="form-control @error('email') is-invalid @enderror"
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="mb-3">
                            <label class="form-label">{{ __('Phone') }}</label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="form-control @error('phone') is-invalid @enderror"
                            >
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password (Optional) --}}
                        <div class="mb-3">
                            <label class="form-label">
                                {{ __('Password') }} <small class="text-muted">({{ __('leave blank to keep current') }})</small>
                            </label>
                            <input
                                type="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">{{ __('Image') }}</label>
                            <input type="file" id="image" name="image" class="form-control">
                        </div>

                        {{-- Role --}}
                        <div class="mb-4">
                            <label class="form-label">{{ __('Role') }}</label>
                            <select
                                name="role"
                                class="form-select @error('role') is-invalid @enderror"
                            >
                                <option value="user"  @selected(old('role', $user->role) === 'user')>{{ __('User') }}</option>
                                <option value="chef"  @selected(old('role', $user->role) === 'chef')>{{ __('Chef') }}</option>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>{{ __('Admin') }}</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dashboard.users.index') }}" class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Update User') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>
