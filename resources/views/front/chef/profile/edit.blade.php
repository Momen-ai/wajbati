@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-card border border-secondary border-opacity-10">
                <div class="card-header bg-card border-bottom border-secondary border-opacity-10 pt-4 px-4">
                    <h4 class="fw-bold mb-0 text-light">Kitchen Settings</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('chef.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Avatar Section -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="me-4 position-relative">
                                @if(auth()->user()->image_path)
                                    <img src="{{ asset('storage/' . auth()->user()->image_path) }}" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center shadow" style="width: 100px; height: 100px;">
                                        <i class="fas fa-user-chef fa-2x text-light"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label class="form-label fw-bold text-light">Profile / Kitchen Image</label>
                                <input type="file" name="image" class="form-control form-control-sm bg-secondary text-light border border-secondary border-opacity-10" accept="image/*">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">Kitchen Name</label>
                                <input type="text" name="kitchen_name" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ old('kitchen_name', auth()->user()->kitchen_name ?? auth()->user()->name . '\'s Kitchen') }}" placeholder="e.g. Mama's Kitchen">
                                <div class="form-text text-light opacity-75">This will be displayed as your brand name.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">Chef Name</label>
                                <input type="text" name="name" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ old('name', auth()->user()->name) }}" required>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-bold text-light">Bio / About</label>
                                <textarea name="bio" class="form-control bg-secondary text-light border border-secondary border-opacity-10" rows="3" placeholder="Tell customers about your cooking style...">{{ old('bio', auth()->user()->bio) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">Phone Number</label>
                                <input type="text" name="phone" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ old('phone', auth()->user()->phone) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-light">Address / Location</label>
                                <input type="text" name="address" class="form-control bg-secondary text-light border border-secondary border-opacity-10" value="{{ old('address', auth()->user()->address) }}" placeholder="City, Area">
                            </div>
                        </div>

                        <hr class="my-4 opacity-25 text-light">

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-pill text-light">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
