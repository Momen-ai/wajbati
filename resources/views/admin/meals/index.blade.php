<x-app-layout>
    <div class="card shadow-sm mb-4">

        {{-- Header --}}
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold">{{ __('Meals Management') }}</h4>

            <a href="{{ route('dashboard.meals.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> {{ __('Add Meal') }}
            </a>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Chef') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Price') }}</th>
                        <th style="width: 240px">{{ __('Actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($meals as $meal)
                        <tr>
                            <td>{{ $meal->id }}</td>
                            <td class="fw-semibold">{{ $meal->name }}</td>
                            <td>{{ $meal->chef?->name }}</td>
                            <td>{{ $meal->category?->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ money($meal->price) }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <a href="{{ route('dashboard.meals.show', $meal->id) }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        {{ __('Show') }}
                                    </a>

                                    <a href="{{ route('dashboard.meals.edit', $meal->id) }}"
                                        class="btn btn-primary btn-sm">
                                        {{ __('Edit') }}
                                    </a>

                                    <form action="{{ route('dashboard.meals.destroy', $meal->id) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">
                                {{ __('No meals found') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-4">
            {{ $meals->withQueryString()->links() }}
        </div>

    </div>
</x-app-layout>
