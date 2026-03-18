@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-light">My Kitchen Meals</h2>
        <a href="{{ route('chef.meals.create') }}" class="btn btn-primary rounded-pill px-4 shadow">
            <i class="fas fa-plus me-2"></i> Add New Meal
        </a>
    </div>

    @if($meals->count() > 0)
    <div class="card shadow-card border border-secondary border-opacity-10">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle mb-0">
                    <thead class="bg-secondary">
                        <tr class="text-light">
                            <th class="p-3 ps-4 border-0">Meal</th>
                            <th class="p-3 border-0">Price</th>
                            <th class="p-3 border-0">Category</th>
                            <th class="p-3 border-0">Last Updated</th>
                            <th class="p-3 border-0 text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($meals as $meal)
                        <tr>
                            <td class="p-3 ps-4">
                                <div class="d-flex align-items-center">
                                    @if($meal->image)
                                    <img src="{{ asset('storage/' . $meal->image->image_path) }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                                <div class="rounded me-3 bg-secondary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-utensils text-light"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold text-light">{{ $meal->name }}</div>
                                                <small class="text-light text-truncate" style="max-width: 150px; display:inline-block;">{{ $meal->description }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-3 fw-bold text-light">${{ $meal->price }}</td>
                                    <td class="p-3"><span class="badge bg-secondary text-light border">{{ $meal->category->name ?? 'Uncategorized' }}</span></td>
                                    <td class="p-3 text-light small">{{ $meal->updated_at->format('M d, Y') }}</td>
                            <td class="p-3 text-end pe-4">
                                <a href="{{ route('chef.meals.edit', $meal->id) }}" class="btn btn-sm btn-outline-secondary me-2"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('chef.meals.destroy', $meal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this meal?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $meals->links('pagination::bootstrap-5') }}
    </div>
    @else
    <div class="text-center py-5 bg-card shadow-card border border-secondary border-opacity-10 rounded">
        <div class="mb-3"><i class="fas fa-utensils fa-4x text-light opacity-25"></i></div>
        <h3 class="text-light">No meals added yet</h3>
        <p class="text-light mb-4">Start by adding your special dishes to the menu!</p>
        <a href="{{ route('chef.meals.create') }}" class="btn btn-outline-primary rounded-pill">Add First Meal</a>
    </div>
    @endif
</div>
@endsection
