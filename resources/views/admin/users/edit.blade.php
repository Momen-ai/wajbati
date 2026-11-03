<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header :title="__('Edit User')">
            <x-slot name="actions">
                <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-subtle rounded-pill px-4 text-secondary">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i> {{ __('Back to List') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <div class="spotlight-card rounded-4 p-4 p-md-5" style="max-width: 800px;">
            <form method="POST" action="{{ route('dashboard.users.update', $user->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-4">
                    <label class="form-label text-main fw-semibold">{{ __('Name') }}</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="form-control bg-surface-2 text-main border-subtle @error('name') is-invalid @enderror"
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label class="form-label text-main fw-semibold">{{ __('Email') }}</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="form-control bg-surface-2 text-main border-subtle @error('email') is-invalid @enderror"
                        required
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-4">
                    <label class="form-label text-main fw-semibold">{{ __('Phone') }}</label>
                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', $user->phone) }}"
                        class="form-control bg-surface-2 text-main border-subtle @error('phone') is-invalid @enderror"
                    >
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password (Optional) --}}
                <div class="mb-4">
                    <label class="form-label text-main fw-semibold">
                        {{ __('Password') }} <small class="text-secondary">({{ __('leave blank to keep current') }})</small>
                    </label>
                    <input
                        type="password"
                        name="password"
                        class="form-control bg-surface-2 text-main border-subtle @error('password') is-invalid @enderror"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="form-label text-main fw-semibold">{{ __('Image') }}</label>
                    @php $user->loadMissing('image'); @endphp
                    @if ($user->relationLoaded('image') && $user->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $user->image->image_path) }}" alt="{{ $user->name }}" class="rounded-3 border border-subtle" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" id="image" name="image" class="form-control bg-surface-2 text-main border-subtle">
                </div>

                {{-- Role --}}
                <div class="mb-4">
                    <label class="form-label text-main fw-semibold">{{ __('Role') }}</label>
                    <select
                        name="role"
                        class="form-select bg-surface-2 text-main border-subtle @error('role') is-invalid @enderror"
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
                <div class="d-flex justify-content-end gap-2 pt-3 border-top border-subtle">
                    <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-subtle rounded-pill px-4 text-secondary">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold shadow-sm">
                        {{ __('Update User') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
