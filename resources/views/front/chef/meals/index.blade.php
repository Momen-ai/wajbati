@extends('layouts.frontend')

@section('content')
{{-- ================= MOBILE VIEW (<768px) ================= --}}
<div class="d-md-none has-bottom-bar px-3 pt-3 pb-5" style="background: #0a0a0a; min-height: 100vh;">
    <!-- Mobile Header -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h5 fw-bold text-light mb-0">{{ __('My Kitchen Meals') }}</h1>
            <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 px-2 py-1 mt-1 small">
                {{ $meals->total() }} {{ __('Meals') }}
            </span>
        </div>
        <a href="{{ route('chef.meals.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 py-2 fw-bold d-inline-flex align-items-center gap-1 shadow-sm" style="color: #1b0e03 !important;">
            <i class="fas fa-plus"></i> {{ __('Add New Meal') }}
        </a>
    </div>

    @if($meals->count() > 0)
        <!-- Meals Cards List -->
        <div class="d-flex flex-column gap-3">
            @foreach($meals as $meal)
                <div class="p-3 rounded-4 border border-secondary border-opacity-10" style="background: var(--surface-1);">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 64px; height: 64px; background: var(--surface-2);">
                            @if($meal->relationLoaded('image') && $meal->image)
                                <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $meal->name }}">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-primary">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1" style="min-width: 0;">
                            <div class="d-flex align-items-start justify-content-between gap-2">
                                <h6 class="fw-bold text-light mb-1 text-truncate">{{ $meal->name }}</h6>
                                <span class="fw-bold text-primary small flex-shrink-0">{{ money($meal->price) }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge rounded-pill px-2 py-1 border border-secondary border-opacity-15 small" style="background: var(--surface-2); color: var(--text-main); font-size: 0.72rem;">
                                    {{ $meal->category->name ?? __('Uncategorized') }}
                                </span>
                                @if($meal->prep_time_minutes)
                                    <small class="text-secondary" style="font-size: 0.72rem;">
                                        <i class="fas fa-clock me-1"></i>{{ $meal->prep_time_minutes }} {{ __('min') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex align-items-center justify-content-end gap-2 pt-2 border-top border-secondary border-opacity-10">
                        <a href="{{ route('chef.meals.edit', $meal->id) }}" class="btn-action-ghost btn-action-ghost-neutral flex-grow-1 justify-content-center py-2" style="min-height: 44px;">
                            <i class="fas fa-edit small text-primary"></i>
                            <span>{{ __('Edit') }}</span>
                        </a>
                        <form action="{{ route('chef.meals.destroy', $meal->id) }}" method="POST" class="d-inline m-0 flex-grow-1" onsubmit="return confirm('{{ __('Are you sure you want to delete this meal?') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action-ghost btn-action-ghost-danger w-100 justify-content-center py-2" style="min-height: 44px;">
                                <i class="fas fa-trash-alt small"></i>
                                <span>{{ __('Delete') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        @if($meals->hasPages())
            <div class="mt-4">
                {{ $meals->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @else
        <div class="py-5">
            <x-ui.empty-state
                icon="fa-utensils"
                :title="__('No meals added yet')"
                :message="__('Start by adding your special dishes to the menu!')"
                :actionUrl="route('chef.meals.create')"
                :actionText="__('Add First Meal')" />
        </div>
    @endif
</div>

{{-- ================= DESKTOP VIEW (≥768px, UNTOUCHED) ================= --}}
<div class="container py-5 d-none d-md-block">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="h2 fw-bold text-light mb-1">{{ __('My Kitchen Meals') }}</h1>
            <p class="text-secondary small mb-0">{{ __('Manage and update your signature dishes and recipes') }}</p>
        </div>
        <a href="{{ route('chef.meals.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm" data-magnetic>
            <i class="fas fa-plus me-2"></i> {{ __('Add New Meal') }}
        </a>
    </div>

    @if($meals->count() > 0)
    <div class="card p-0 rounded-4 border border-secondary border-opacity-10 shadow-sm overflow-hidden" style="background: var(--surface-1);">
        <div class="table-responsive">
            <table class="table align-middle mb-0 text-light" style="--bs-table-bg: transparent;">
                <thead>
                    <tr class="text-secondary small text-uppercase" style="border-bottom: 1px solid var(--border-subtle); background: var(--surface-2);">
                        <th class="p-3 ps-4 border-0">{{ __('Meal') }}</th>
                        <th class="p-3 border-0">{{ __('Price') }}</th>
                        <th class="p-3 border-0">{{ __('Category') }}</th>
                        <th class="p-3 border-0">{{ __('Last Updated') }}</th>
                        <th class="p-3 border-0 text-end pe-4">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($meals as $meal)
                    <tr style="border-bottom: 1px solid var(--border-subtle);">
                        <td class="p-3 ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 overflow-hidden flex-shrink-0" style="width: 52px; height: 52px; background: var(--surface-2);">
                                    @if($meal->relationLoaded('image') && $meal->image)
                                        <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="w-100 h-100 object-fit-cover" alt="{{ $meal->name }}">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-primary">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="fw-bold text-light mb-0">{{ $meal->name }}</h6>
                                    <small class="text-secondary text-truncate d-inline-block" style="max-width: 220px;">{{ $meal->description }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="p-3 fw-bold text-primary">{{ money($meal->price) }}</td>
                        <td class="p-3">
                            <span class="badge rounded-pill px-3 py-1 border border-secondary border-opacity-15 small" style="background: var(--surface-2); color: var(--text-main);">
                                {{ $meal->category->name ?? __('Uncategorized') }}
                            </span>
                        </td>
                        <td class="p-3 text-secondary small">{{ $meal->updated_at->format('M d, Y') }}</td>
                        <td class="p-3 text-end pe-4">
                            <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                <a href="{{ route('chef.meals.edit', $meal->id) }}" class="btn-action-ghost btn-action-ghost-neutral">
                                    <i class="fas fa-edit small text-primary"></i>
                                    <span>{{ __('Edit') }}</span>
                                </a>
                                <form action="{{ route('chef.meals.destroy', $meal->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('{{ __('Are you sure you want to delete this meal?') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action-ghost btn-action-ghost-danger">
                                        <i class="fas fa-trash-alt small"></i>
                                        <span>{{ __('Delete') }}</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">
        {{ $meals->links('pagination::bootstrap-5') }}
    </div>
    @else
    <div class="col-12 py-5">
        <x-ui.empty-state
            icon="fa-utensils"
            :title="__('No meals added yet')"
            :message="__('Start by adding your special dishes to the menu!')"
            :actionUrl="route('chef.meals.create')"
            :actionText="__('Add First Meal')" />
    </div>
    @endif
</div>
@endsection
