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
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 class="fw-bold mb-0 text-light">{{ $totalMeals }}</h3>
                <small class="text-light opacity-50 small text-uppercase fw-600">Total Meals</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-3 text-center hover-lift">
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <h3 class="fw-bold mb-0 text-light">{{ $totalOrders }}</h3>
                <small class="text-light opacity-50 small text-uppercase fw-600">Total Orders</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-3 text-center hover-lift">
                <div class="rounded-circle bg-success bg-opacity-10 text-success mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <h3 class="fw-bold mb-0 text-light me-2">{{ $growth['revenue'] }}</h3>
                </div>
                <small class="text-light opacity-50 small text-uppercase fw-600">Revenue Growth</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-3 text-center hover-lift">
                <div class="rounded-circle bg-info bg-opacity-10 text-info mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="fw-bold mb-0 text-light">{{ $completedOrders }}</h3>
                <small class="text-light opacity-50 small text-uppercase fw-600">Completed Orders</small>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-card border border-secondary border-opacity-10 rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-light m-0">Monthly Earnings ($)</h5>
                    <div class="text-muted small">Real-time revenue tracking</div>
                </div>
                <div style="height: 250px;">
                    <canvas id="chefRevenueChart"></canvas>
                </div>
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
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('chefRevenueChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(255, 122, 24, 0.2)');
        gradient.addColorStop(1, 'rgba(255, 122, 24, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [{
                    label: 'Earnings',
                    data: {!! json_encode($chartData['values']) !!},
                    borderColor: '#ff7a18',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#ff7a18',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endpush
@endsection
