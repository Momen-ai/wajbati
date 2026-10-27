<x-app-layout>
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h4 class="mb-0 fw-bold">{{ __('Order Status Audit Log') }}</h4>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>{{ __('Log ID') }}</th>
                        <th>{{ __('Order ID') }}</th>
                        <th>{{ __('Changed By') }}</th>
                        <th>{{ __('From Status') }}</th>
                        <th>{{ __('To Status') }}</th>
                        <th>{{ __('Note / Reason') }}</th>
                        <th>{{ __('Override (Forced)') }}</th>
                        <th>{{ __('Timestamp') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>
                                <a href="{{ route('dashboard.orders.show', $log->order_id) }}" class="fw-bold">
                                    #{{ $log->order_id }}
                                </a>
                            </td>
                            <td>{{ $log->user?->name ?? __('System') }}</td>
                            <td><span class="badge bg-secondary">{{ __($log->from_status) }}</span></td>
                            <td><span class="badge bg-primary">{{ __($log->to_status) }}</span></td>
                            <td>{{ $log->note ?? '-' }}</td>
                            <td>
                                @if($log->forced)
                                    <span class="badge bg-danger">{{ __('Yes') }}</span>
                                @else
                                    <span class="badge bg-success">{{ __('No') }}</span>
                                @endif
                            </td>
                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4">{{ __('No audit log entries found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
