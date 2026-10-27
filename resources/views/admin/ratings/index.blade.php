<x-app-layout>
    <div class="card shadow-sm">

        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="fw-bold mb-0">{{ __('Ratings') }}</h4>
            <a href="{{ route('dashboard.ratings.create') }}" class="btn btn-success">
                {{ __('Add Rating') }}
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Chef') }}</th>
                        <th>{{ __('Stars') }}</th>
                        <th>{{ __('Comment') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ratings as $rating)
                        <tr>
                            <td>{{ $rating->id }}</td>
                            <td>{{ $rating->user?->name ?? __('Unknown') }}</td>
                            <td>{{ $rating->chef?->name ?? __('Unknown') }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">
                                    {{ $rating->star }} ★
                                </span>
                            </td>
                            <td>{{ $rating->body ?? '-' }}</td>
                            <td>
                                <a href="{{ route('dashboard.ratings.edit', $rating->id) }}"
                                    class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
                                <form method="POST" action="{{ route('dashboard.ratings.destroy', $rating->id) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete this rating?') }}')">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">
                                {{ __('No ratings found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-4">
            {{ $ratings->withQueryString()->links() }}
        </div>

    </div>
</x-app-layout>
