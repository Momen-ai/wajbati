<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header :title="__('Ratings')" />

        <!-- Table Card -->
        <div class="table-custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-4" style="width: 80px">{{ __('ID') }}</th>
                            <th class="text-start ps-3">{{ __('User') }}</th>
                            <th class="text-start ps-3">{{ __('Chef') }}</th>
                            <th class="text-start ps-3" style="width: 120px">{{ __('Stars') }}</th>
                            <th class="text-start ps-3">{{ __('Comment') }}</th>
                            <th class="text-end pe-4" style="width: 140px">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ratings as $rating)
                            <tr>
                                <td class="text-start ps-4 text-secondary fw-semibold">#{{ $rating->id }}</td>
                                <td class="text-start ps-3 fw-semibold text-main">{{ $rating->user?->name ?? __('Unknown') }}</td>
                                <td class="text-start ps-3 text-secondary">{{ $rating->chef?->name ?? __('Unknown') }}</td>
                                <td class="text-start ps-3">
                                    <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">
                                        <i class="fas fa-star me-1"></i>{{ $rating->star }}
                                    </span>
                                </td>
                                <td class="text-start ps-3 text-secondary">{{ $rating->body ?? '-' }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                        <form method="POST" action="{{ route('dashboard.ratings.destroy', $rating->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn-action-ghost-danger"
                                                title="{{ __('Delete') }}"
                                                onclick="return confirm('{{ __('Are you sure you want to delete this rating?') }}')">
                                                <i class="fas fa-trash-alt"></i>
                                                <span class="d-none d-md-inline ms-1">{{ __('Delete') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-5">
                                    <i class="fas fa-star fs-2 mb-2 d-block opacity-50"></i>
                                    {{ __('No ratings found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($ratings->hasPages())
                <div class="p-3 border-top border-subtle">
                    {{ $ratings->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
