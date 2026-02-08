<x-app-layout>
    <div class="card shadow-sm mb-4">

        {{-- Header --}}
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h4 class="mb-0 fw-bold">Categories Management</h4>

            <a href="{{ route('dashboard.category.create') }}" class="btn btn-success">
                Add Category
            </a>
        </div>


        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">

                <thead class="table-dark">
                    <tr>
                        <th style="width: 60px">ID</th>
                        <th>Name</th>
                        <th style="width: 220px">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">

                                    <a class="btn btn-sm btn-primary"
                                        href="{{ route('dashboard.category.edit', $category->id) }}">
                                        Edit
                                    </a>

                                    @if ($category->role !== 'admin')
                                        <form method="POST"
                                            action="{{ route('dashboard.category.destroy', $category->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted py-4">
                                No categories found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>
</x-app-layout>
