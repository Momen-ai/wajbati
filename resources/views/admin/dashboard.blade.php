<x-app-layout>
    <div class="container-fluid py-5 px-lg-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-end mb-5 animate-fade-in">
            <div>
                <h1 class="fw-bold tracking-tight mb-2">{{ __('Welcome Back') }}, <span class="text-primary">{{ Auth::user()->name }}</span></h1>
                <p class="text-secondary mb-0">{{ __("Here's a premium overview of your Wajbati platform performance.") }}</p>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-white premium-card py-2 px-4 fw-600 border-0 shadow-sm">
                    <i class="fas fa-download me-2 text-primary"></i> {{ __('Export Report') }}
                </button>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="row g-4 mb-5">
            <!-- Revenue -->
            <div class="col-xl-3 col-md-6 animate-fade-in" style="animation-delay: 0.1s">
                <div class="premium-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-primary-soft text-primary">
                            <i class="fas fa-wallet fa-lg"></i>
                        </div>
                        <span class="growth-tag growth-up">{{ $growth['revenue'] }}</span>
                    </div>
                    <div class="stat-label">{{ __('Total Revenue') }}</div>
                    <div class="stat-value">{{ money($totalRevenue) }}</div>
                </div>
            </div>

            <!-- Orders -->
            <div class="col-xl-3 col-md-6 animate-fade-in" style="animation-delay: 0.2s">
                <div class="premium-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-success-soft text-success">
                            <i class="fas fa-shopping-bag fa-lg"></i>
                        </div>
                        <span class="growth-tag growth-up">{{ $growth['orders'] }}</span>
                    </div>
                    <div class="stat-label">{{ __('Completed Orders') }}</div>
                    <div class="stat-value">{{ $ordersCount }}</div>
                </div>
            </div>

            <!-- Users -->
            <div class="col-xl-3 col-md-6 animate-fade-in" style="animation-delay: 0.3s">
                <div class="premium-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-warning-soft text-warning">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                        <span class="growth-tag growth-up">{{ $growth['users'] }}</span>
                    </div>
                    <div class="stat-label">{{ __('Active Users') }}</div>
                    <div class="stat-value">{{ $usersCount }}</div>
                </div>
            </div>

            <!-- Pending -->
            <div class="col-xl-3 col-md-6 animate-fade-in" style="animation-delay: 0.4s">
                <div class="premium-card p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="icon-box bg-danger-soft text-danger">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                    </div>
                    <div class="stat-label">{{ __('Pending Orders') }}</div>
                    <div class="stat-value">{{ $pendingOrdersCount }}</div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <!-- Chart Container -->
            <div class="col-xl-12 animate-fade-in" style="animation-delay: 0.5s">
                <div class="premium-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-700 m-0">{{ __('Revenue Analytics (Last 6 Months)') }}</h5>
                        <div class="text-secondary small fw-600">{{ __('Earnings Overview') }}</div>
                    </div>
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row g-4">
            <!-- Recent Orders -->
            <div class="col-xl-8 animate-fade-in" style="animation-delay: 0.6s">
                <div class="premium-card h-100">
                    <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-700 m-0">{{ __('Recent Transactions') }}</h5>
                        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-sm text-primary fw-600">{{ __('View All') }}</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-premium mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('Ref ID') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Chef') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestOrders as $order)
                                <tr>
                                    <td class="fw-600 text-secondary">#ORD-{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initial me-3" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                {{ substr($order->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <span class="fw-600">{{ $order->user->name ?? __('Guest User') }}</span>
                                        </div>
                                    </td>
                                    <td class="text-secondary">{{ $order->chef->kitchen_name ?? __('N/A') }}</td>
                                    <td class="fw-700">{{ money($order->total) }}</td>
                                    <td>
                                        <span class="badge-premium {{ $order->status == 'completed' ? 'bg-success-soft text-success' : 'bg-warning-soft text-warning' }}">
                                            {{ __(ucfirst($order->status)) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Newest Users -->
            <div class="col-xl-4 animate-fade-in" style="animation-delay: 0.7s">
                <div class="premium-card h-100">
                    <div class="p-4 border-bottom">
                        <h5 class="fw-700 m-0">{{ __('New Registrations') }}</h5>
                    </div>
                    <div class="p-4">
                        @foreach($latestUsers as $user)
                        <div class="d-flex align-items-center justify-content-between mb-4 last-child-mb-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial me-3 bg-light text-primary">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-700 text-sm">{{ $user->name }}</div>
                                    <div class="text-muted text-xs">{{ $user->email }}</div>
                                </div>
                            </div>
                            <span class="badge-premium bg-light text-secondary">{{ __(ucfirst($user->role)) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="p-4 bg-light border-top text-center rounded-bottom-20">
                        <a href="{{ route('dashboard.users.index') }}" class="text-primary text-sm fw-700 text-decoration-none">{{ __('Manage User Database') }} <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            // Create Gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [{
                        label: '{{ __('Monthly Revenue') }}',
                        data: {!! json_encode($chartData['values']) !!},
                        borderColor: '#6366f1',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.03)' },
                            ticks: { font: { size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
