@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-card border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                    <h4 class="fw-bold mb-0">Add New Meal</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('chef.meals.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Meal Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Traditional Mandi">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Price ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control" required placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="" disabled selected>Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Describe the ingredients and portion size..."></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Meal Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <div class="form-text">Supported formats: jpg, jpeg, png, webp. Max size: 2MB.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('chef.meals.index') }}" class="btn btn-light text-muted">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5 rounded-pill shadow-sm">Save Meal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
