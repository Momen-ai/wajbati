<x-app-layout>
    <div class="container-fluid py-4 px-lg-4">
        <!-- Header -->
        <x-ui.page-header
            :title="__('Welcome Back') . ', ' . Auth::user()->name"
            :subtitle="__('Here\'s a premium overview of your Wajbati platform performance.')">
            <x-slot name="actions">
                <a href="{{ route('dashboard.orders.export-csv') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="fas fa-file-csv me-1"></i>{{ __('Export Report') }}
                </a>
            </x-slot>
        </x-ui.page-header>

        <!-- Stats Grid with Strong Visual Hierarchy -->
        <div class="row g-4 mb-4">
            <!-- Hero Stat: Total Revenue -->
            <div class="col-12 col-xl-4">
                <x-ui.stat-card
                    icon="fas fa-wallet"
                    :label="__('Total Revenue')"
                    :value="money($totalRevenue)"
                    :trend="$growth['revenue']"
                    :trendUp="!str_contains($growth['revenue'], '-')"
                    :isHero="true"
                    class="h-100" />
            </div>

            <!-- Secondary Stats: 3 Cards Grid -->
            <div class="col-12 col-xl-8">
                <div class="row g-4 h-100">
                    <div class="col-md-4">
                        <x-ui.stat-card
                            icon="fas fa-shopping-bag"
                            :label="__('Total Orders')"
                            :value="$ordersCount"
                            :numericValue="$ordersCount"
                            :trend="$growth['orders']"
                            :trendUp="!str_contains($growth['orders'], '-')"
                            class="h-100" />
                    </div>

                    <div class="col-md-4">
                        <x-ui.stat-card
                            icon="fas fa-users"
                            :label="__('Active Users')"
                            :value="$usersCount"
                            :numericValue="$usersCount"
                            :trend="$growth['users']"
                            :trendUp="!str_contains($growth['users'], '-')"
                            class="h-100" />
                    </div>

                    <div class="col-md-4">
                        <x-ui.stat-card
                            icon="fas fa-clock"
                            :label="__('Pending Orders')"
                            :value="$pendingOrdersCount"
                            :numericValue="$pendingOrdersCount"
                            class="h-100" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="spotlight-card p-4 rounded-4" data-spotlight>
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2 mb-4">
                        <div>
                            <h5 class="fw-bold text-main mb-1">{{ __('Revenue Analytics (Last 6 Months)') }}</h5>
                            <span class="text-secondary small">{{ __('Earnings Overview') }}</span>
                        </div>
                        <span class="badge badge-primary">{{ __('Monthly Revenue') }}</span>
                    </div>

                    <div style="height: 320px; position: relative;">
                        <canvas id="revenueChart"></canvas>
                        @if(array_sum($chartData['values']) == 0)
                            <div class="position-absolute top-50 start-50 translate-middle text-center pe-none">
                                <span class="badge bg-surface-2 text-secondary border border-subtle px-3 py-2 rounded-pill small">
                                    <i class="fas fa-chart-line me-1 text-primary"></i> {{ __('No revenue recorded for this period yet') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row: Recent Orders & Newest Users -->
        <div class="row g-4 align-items-start">
            <!-- Recent Transactions Table -->
            <div class="col-12 col-xl-8">
                <div class="spotlight-card p-4 rounded-4 d-flex flex-column" data-spotlight>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-main mb-0">{{ __('Recent Transactions') }}</h5>
                        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-link text-primary text-decoration-none small fw-semibold p-0">
                            {{ __('View All') }} <i class="fas fa-arrow-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} ms-1"></i>
                        </a>
                    </div>

                    <div class="table-responsive flex-grow-1">
                        <table class="table table-hover align-middle mb-0">
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
                                @forelse($latestOrders as $order)
                                    <tr>
                                        <td class="text-secondary fw-semibold">#ORD-{{ $order->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-initial" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                                    {{ mb_substr($order->user->name ?? 'U', 0, 1) }}
                                                </div>
                                                <span class="fw-semibold text-main text-truncate" style="max-width: 140px;">
                                                    {{ $order->user->name ?? __('Guest User') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-secondary">{{ $order->chef->kitchen_name ?? __('N/A') }}</td>
                                        <td class="fw-bold text-primary">{{ money($order->total) }}</td>
                                        <td>
                                            @php
                                                $badgeType = match($order->status) {
                                                    'completed', 'delivered' => 'success',
                                                    'pending' => 'warning',
                                                    'cancelled', 'rejected' => 'danger',
                                                    default => 'primary'
                                                };
                                            @endphp
                                            <x-ui.badge :type="$badgeType">
                                                {{ __(ucfirst(str_replace('_', ' ', $order->status))) }}
                                            </x-ui.badge>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-secondary">
                                            {{ __('No records found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Newest Users -->
            <div class="col-12 col-xl-4">
                <div class="spotlight-card p-4 rounded-4 d-flex flex-column" data-spotlight>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-main mb-0">{{ __('New Registrations') }}</h5>
                        <span class="badge bg-surface-2 text-secondary border border-subtle">
                            {{ count($latestUsers) }}
                        </span>
                    </div>

                    <div class="d-flex flex-column gap-3 flex-grow-1">
                        @forelse($latestUsers as $user)
                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 hover-bg-surface-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-initial">
                                        {{ mb_substr($user->name, 0, 1) }}
                                    </div>
                                    <div style="min-width: 0;">
                                        <div class="fw-bold text-main text-truncate" style="max-width: 130px;">{{ $user->name }}</div>
                                        <div class="text-secondary small text-truncate" style="max-width: 130px;">{{ $user->email }}</div>
                                    </div>
                                </div>
                                <x-ui.badge :type="$user->role === 'admin' ? 'danger' : ($user->role === 'chef' ? 'primary' : 'secondary')">
                                    {{ __(ucfirst($user->role)) }}
                                </x-ui.badge>
                            </div>
                        @empty
                            <p class="text-secondary text-center py-4 mb-0">{{ __('No records found') }}</p>
                        @endforelse
                    </div>

                    <div class="pt-3 mt-3 border-top border-subtle text-center">
                        <a href="{{ route('dashboard.users.index') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100">
                            {{ __('Manage User Database') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const chartCanvas = document.getElementById('revenueChart');
            if (!chartCanvas) return;

            const ctx = chartCanvas.getContext('2d');
            
            // Orange Brand Gradient
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(255, 122, 24, 0.35)');
            gradient.addColorStop(1, 'rgba(255, 122, 24, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels']) !!},
                    datasets: [{
                        label: '{{ __('Monthly Revenue') }}',
                        data: {!! json_encode($chartData['values']) !!},
                        borderColor: '#ff7a18',
                        borderWidth: 3,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#ff7a18',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#161616',
                            titleColor: '#f0f0f0',
                            bodyColor: '#ff7a18',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.04)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#a0a0a0',
                                font: { family: 'Cairo, Inter, sans-serif' }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            min: 0,
                            suggestedMax: 100,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.04)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#a0a0a0',
                                font: { family: 'Cairo, Inter, sans-serif' },
                                precision: 0
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
