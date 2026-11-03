<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header
            :title="__('Categories Management')"
            :subtitle="__('Organize and manage dish categories across the platform.')">
            <x-slot name="actions">
                <a href="{{ route('dashboard.category.create') }}" class="btn btn-primary rounded-pill px-4 fw-semibold shadow-sm">
                    <i class="fas fa-plus me-1"></i> {{ __('Add Category') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <!-- Table Card -->
        <div class="table-custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-4" style="width: 90px">{{ __('ID') }}</th>
                            <th class="text-start ps-3">{{ __('Name') }}</th>
                            <th class="text-end pe-4" style="width: 220px">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td class="text-start ps-4 text-secondary fw-semibold">#{{ $category->id }}</td>
                                <td class="text-start ps-3 fw-semibold text-main">{{ $category->name }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                        <a href="{{ route('dashboard.category.edit', $category->id) }}"
                                           class="btn-action-ghost btn-action-ghost-neutral">
                                            <i class="fas fa-edit small text-primary"></i>
                                            <span>{{ __('Edit') }}</span>
                                        </a>

                                        @if ($category->role !== 'admin')
                                            <form method="POST"
                                                  action="{{ route('dashboard.category.destroy', $category->id) }}"
                                                  class="d-inline m-0"
                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this category?') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action-ghost btn-action-ghost-danger">
                                                    <i class="fas fa-trash-alt small"></i>
                                                    <span>{{ __('Delete') }}</span>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-5 text-secondary">
                                    <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-40"></i>
                                    {{ __('No categories found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="p-3 border-top border-subtle">
                    {{ $categories->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
