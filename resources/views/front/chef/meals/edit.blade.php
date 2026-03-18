@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-card border border-secondary border-opacity-10">
                <div class="card-header bg-card border-bottom border-secondary border-opacity-10 pt-4 px-4">
                    <h4 class="fw-bold mb-0 text-light">Edit Meal: {{ $meal->name }}</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('chef.meals.update', $meal->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-light">Meal Name</label>
                            <input type="text" name="name" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ $meal->name }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">Price ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ $meal->price }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">Category</label>
                                <select name="category_id" class="form-select bg-secondary text-light border border-secondary border-opacity-10" required>
                                    <option value="" disabled>Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $meal->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-light">Description</label>
                            <textarea name="description" class="form-control bg-secondary text-light border border-secondary border-opacity-10" rows="4">{{ $meal->description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-light">Meal Image</label>
                            @if($meal->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $meal->image->image_path) }}" alt="Current Image" class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            @endif
                            <input type="file" name="image" class="form-control bg-secondary text-light border border-secondary border-opacity-10" accept="image/*">
                            <div class="form-text text-light opacity-75">Leave empty to keep current image.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('chef.meals.index') }}" class="btn btn-secondary text-light">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow">Update Meal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
