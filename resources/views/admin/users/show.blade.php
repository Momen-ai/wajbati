<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header :title="__('User Details')">
            <x-slot name="actions">
                <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-subtle rounded-pill px-4 text-secondary">
                    <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'right' : 'left' }} me-1"></i> {{ __('Back to List') }}
                </a>
                <a href="{{ route('dashboard.users.edit', $user->id) }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="fas fa-pen me-1"></i> {{ __('Edit') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <div class="spotlight-card rounded-4 p-4 p-md-5" style="max-width: 800px;">
            <div class="d-flex align-items-center gap-4 mb-4 pb-4 border-bottom border-subtle">
                @php $user->loadMissing('image'); @endphp
                <div class="rounded-circle overflow-hidden flex-shrink-0 border border-subtle" style="width: 80px; height: 80px; background: var(--surface-2);">
                    @if ($user->relationLoaded('image') && $user->image)
                        <img src="{{ asset('storage/' . $user->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $user->name }}">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                            <i class="fas fa-user fa-2x opacity-50"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h3 class="fw-bold text-main mb-1">{{ $user->name }}</h3>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $user->role === 'admin' ? 'bg-danger text-white' : ($user->role === 'chef' ? 'bg-warning text-dark' : 'bg-secondary text-white') }} rounded-pill px-2.5 py-1">
                            {{ __($user->role) }}
                        </span>
                        <span class="text-secondary small">{{ $user->email }}</span>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-sm-6">
                    <div class="p-3 rounded-3 bg-surface-2 border border-subtle">
                        <span class="text-secondary small d-block mb-1">{{ __('Phone') }}</span>
                        <span class="fw-semibold text-main">{{ $user->phone ?? '-' }}</span>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="p-3 rounded-3 bg-surface-2 border border-subtle">
                        <span class="text-secondary small d-block mb-1">{{ __('Created At') }}</span>
                        <span class="fw-semibold text-main">{{ $user->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
