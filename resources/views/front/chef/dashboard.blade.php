@extends('layouts.frontend')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-light">Welcome back, {{ Auth::user()->name }}!</h2>
            <p class="text-light">Here's what's happening with your kitchen today.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-3 text-center hover-lift">
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-utensils fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-0 text-light">{{ $totalMeals }}</h3>
                <small class="text-light">Total Meals</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-3 text-center hover-lift">
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-shopping-bag fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-0 text-light">{{ $totalOrders }}</h3>
                <small class="text-light">Total Orders</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-3 text-center hover-lift">
                <div class="rounded-circle bg-info bg-opacity-10 text-info mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-clock fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-0 text-light">{{ $pendingOrders }}</h3>
                <small class="text-light">Pending Orders</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-3 text-center hover-lift">
                <div class="rounded-circle bg-success bg-opacity-10 text-success mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="fas fa-check-circle fa-lg"></i>
                </div>
                <h3 class="fw-bold mb-0 text-light">{{ $completedOrders }}</h3>
                <small class="text-light">Completed</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 h-100">
                <div class="card-header bg-card border-bottom border-secondary border-opacity-10 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-light">Recent Orders</h5>
                    <a href="{{ route('chef.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover align-middle mb-0">
                            <thead class="bg-secondary">
                                <tr>
                                    <th class="ps-4 border-0">Order ID</th>
                                    <th class="border-0">Customer</th>
                                    <th class="border-0">Total</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0 pe-4 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td class="ps-4">#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td class="fw-bold">${{ $order->total }}</td>
                                    <td>
                                        <span class="badge rounded-pill bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'completed' ? 'success' : 'primary') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('chef.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Details</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-light">No orders found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 mb-4">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-plus-circle fa-3x text-primary opacity-25"></i>
                    </div>
                    <h5 class="fw-bold text-light">Growing your menu?</h5>
                    <p class="text-light small">Add your latest delicious creations to attract more customers.</p>
                    <a href="{{ route('chef.meals.create') }}" class="btn btn-primary w-100 rounded-pill shadow">Add New Meal</a>
                </div>
            </div>
            
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 text-light">Kitchen Profile</h5>
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-secondary text-light me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-store text-light"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-truncate text-light" style="max-width: 150px;">{{ Auth::user()->kitchen_name ?? 'My Kitchen' }}</div>
                            <small class="text-light">Chef Account</small>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100 rounded-pill btn-sm">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
