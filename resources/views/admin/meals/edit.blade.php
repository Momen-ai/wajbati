<x-app-layout>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Edit Meal</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.meals.update', $meal->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Chef --}}
                            <div class="mb-3">
                                <label class="form-label">Chef</label>
                                <select name="chef_id" class="form-select" required>
                                    <option value="">Select Chef</option>
                                    @foreach ($chefs as $chef)
                                        <option value="{{ $chef->id }}" @selected($meal->chef_id == $chef->id)>
                                            {{ $chef->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Category --}}
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- None --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected($meal->category_id == $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="form-label">Meal Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $meal->name }}"
                                    required>
                            </div>

                            {{-- Description --}}
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" rows="4" class="form-control" placeholder="Meal description">{{ $meal->description }}</textarea>
                            </div>

                            {{-- Price --}}
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" step="0.01" name="price" class="form-control"
                                    value="{{ $meal->price }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" id="image" name="image" class="form-control"
                                    value="{{ $meal->image }}">
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('dashboard.meals.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Update Meal
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
