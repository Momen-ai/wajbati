<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header :title="__('Order Status Audit Log')" />

        <!-- Table Card -->
        <div class="table-custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-4" style="width: 80px">{{ __('Log ID') }}</th>
                            <th class="text-start ps-3" style="width: 110px">{{ __('Order ID') }}</th>
                            <th class="text-start ps-3">{{ __('Changed By') }}</th>
                            <th class="text-start ps-3">{{ __('From Status') }}</th>
                            <th class="text-start ps-3">{{ __('To Status') }}</th>
                            <th class="text-start ps-3">{{ __('Note / Reason') }}</th>
                            <th class="text-start ps-3" style="width: 140px">{{ __('Override (Forced)') }}</th>
                            <th class="text-end pe-4" style="width: 170px">{{ __('Timestamp') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="text-start ps-4 text-secondary fw-semibold">#{{ $log->id }}</td>
                                <td class="text-start ps-3">
                                    <a href="{{ route('dashboard.orders.show', $log->order_id) }}" class="fw-bold text-primary">
                                        #{{ $log->order_id }}
                                    </a>
                                </td>
                                <td class="text-start ps-3 fw-semibold text-main">{{ $log->user?->name ?? __('System') }}</td>
                                <td class="text-start ps-3"><span class="badge bg-secondary text-white rounded-pill px-2.5 py-1">{{ __($log->from_status) }}</span></td>
                                <td class="text-start ps-3"><span class="badge bg-primary text-white rounded-pill px-2.5 py-1">{{ __($log->to_status) }}</span></td>
                                <td class="text-start ps-3 text-secondary">{{ $log->note ?? '-' }}</td>
                                <td class="text-start ps-3">
                                    @if($log->forced)
                                        <span class="badge bg-danger text-white rounded-pill px-2.5 py-1">{{ __('Yes') }}</span>
                                    @else
                                        <span class="badge bg-success text-white rounded-pill px-2.5 py-1">{{ __('No') }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4 text-secondary small">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-secondary py-5">
                                    <i class="fas fa-history fs-2 mb-2 d-block opacity-50"></i>
                                    {{ __('No audit log entries found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="p-3 border-top border-subtle">
                    {{ $logs->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
