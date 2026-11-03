<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header
            :title="__('Meals Management')"
            :subtitle="__('Manage dishes, ingredients, chefs, and pricing across kitchens.')">
            <x-slot name="actions">
                <a href="{{ route('dashboard.meals.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="fas fa-plus me-1"></i> {{ __('Add Meal') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <!-- Table Card -->
        <div class="table-custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-4" style="width: 80px">{{ __('ID') }}</th>
                            <th class="text-start ps-3">{{ __('Name') }}</th>
                            <th class="text-start ps-3">{{ __('Chef') }}</th>
                            <th class="text-start ps-3">{{ __('Category') }}</th>
                            <th class="text-start ps-3">{{ __('Price') }}</th>
                            <th class="text-end pe-4" style="width: 250px">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($meals as $meal)
                            <tr>
                                <td class="text-start ps-4 text-secondary fw-semibold">#{{ $meal->id }}</td>
                                <td class="text-start ps-3 fw-semibold text-main">{{ $meal->name }}</td>
                                <td class="text-start ps-3 text-secondary">{{ $meal->chef?->name ?? __('N/A') }}</td>
                                <td class="text-start ps-3 text-secondary">{{ $meal->category?->name ?? '-' }}</td>
                                <td class="text-start ps-3 fw-bold text-primary">{{ money($meal->price) }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                        <a href="{{ route('dashboard.meals.show', $meal->id) }}"
                                           class="btn-action-ghost btn-action-ghost-neutral">
                                            <i class="fas fa-eye small text-secondary"></i>
                                            <span>{{ __('Show') }}</span>
                                        </a>

                                        <a href="{{ route('dashboard.meals.edit', $meal->id) }}"
                                           class="btn-action-ghost btn-action-ghost-neutral">
                                            <i class="fas fa-edit small text-primary"></i>
                                            <span>{{ __('Edit') }}</span>
                                        </a>

                                        <form action="{{ route('dashboard.meals.destroy', $meal->id) }}" method="POST"
                                              class="d-inline m-0"
                                              onsubmit="return confirm('{{ __('Are you sure you want to delete this meal?') }}')">
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
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-secondary">
                                    <i class="fas fa-utensils fa-2x mb-2 d-block opacity-40"></i>
                                    {{ __('No meals found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($meals->hasPages())
                <div class="p-3 border-top border-subtle">
                    {{ $meals->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
