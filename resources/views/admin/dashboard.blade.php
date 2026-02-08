<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        :root {
            --primary: #6366f1;
            --primary-soft: #eef2ff;
            --secondary: #64748b;
            --success: #10b981;
            --success-soft: #ecfdf5;
            --warning: #f59e0b;
            --warning-soft: #fffbeb;
            --danger: #ef4444;
            --danger-soft: #fef2f2;
            --dark: #0f172a;
            --surface: #ffffff;
            --background: #f8fafc;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--background);
            color: var(--dark);
        }

        .premium-card {
            background: var(--surface);
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .premium-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
            transform: translateY(-4px);
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .premium-card:hover .icon-box {
            transform: scale(1.1) rotate(-5deg);
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--secondary);
            font-weight: 500;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.02em;
        }

        .growth-tag {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 99px;
            margin-left: 8px;
        }

        .growth-up { background: var(--success-soft); color: var(--success); }

        .table-premium thead th {
            background: #fcfcfd;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 600;
            color: var(--secondary);
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 24px;
        }

        .table-premium tbody td {
            padding: 16px 24px;
            font-size: 0.875rem;
            vertical-align: middle;
            border-bottom: 1px solid #f8fafc;
        }

        .avatar-initial {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            color: white;
        }

        .badge-premium {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary), #a855f7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: rgb(60, 1, 223);
        }
    </style>

    <div class="container-fluid py-5 px-lg-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-end mb-5 animate-fade-in">
            <div>
                <h1 class="fw-bold tracking-tight mb-2">Welcome Back, <span class="gradient-text">{{ Auth::user()->name }}</span></h1>
                <p class="text-secondary mb-0">Here's a premium overview of your Wajbati platform performance.</p>
            </div>
            <div class="d-none d-md-block">
                <button class="btn btn-white premium-card py-2 px-4 fw-600 border-0 shadow-sm">
                    <i class="fas fa-download me-2 text-primary"></i> Export Report
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
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-value">${{ number_format($totalRevenue, 2) }}</div>
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
                    <div class="stat-label">Completed Orders</div>
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
                    <div class="stat-label">Active Users</div>
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
                    <div class="stat-label">Pending Orders</div>
                    <div class="stat-value">{{ $pendingOrdersCount }}</div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row g-4">
            <!-- Recent Orders -->
            <div class="col-xl-8 animate-fade-in" style="animation-delay: 0.5s">
                <div class="premium-card h-100">
                    <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="fw-700 m-0">Recent Transactions</h5>
                        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-sm text-primary fw-600">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-premium mb-0">
                            <thead>
                                <tr>
                                    <th>Ref ID</th>
                                    <th>Customer</th>
                                    <th>Chef</th>
                                    <th>Amount</th>
                                    <th>Status</th>
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
                                            <span class="fw-600">{{ $order->user->name ?? 'Guest User' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-secondary">{{ $order->chef->kitchen_name ?? 'N/A' }}</td>
                                    <td class="fw-700">${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge-premium {{ $order->status == 'completed' ? 'bg-success-soft text-success' : 'bg-warning-soft text-warning' }}">
                                            {{ ucfirst($order->status) }}
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
            <div class="col-xl-4 animate-fade-in" style="animation-delay: 0.6s">
                <div class="premium-card h-100">
                    <div class="p-4 border-bottom">
                        <h5 class="fw-700 m-0">New Registrations</h5>
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
                            <span class="badge-premium bg-light text-secondary">{{ ucfirst($user->role) }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="p-4 bg-light border-top text-center rounded-bottom-20">
                        <a href="{{ route('dashboard.users.index') }}" class="text-primary text-sm fw-700 text-decoration-none">Manage User Database <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
