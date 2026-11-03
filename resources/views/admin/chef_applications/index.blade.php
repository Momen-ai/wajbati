<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header :title="__('Chef Applications')" />

        <!-- Status Filter Pills -->
        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            <a href="{{ route('dashboard.chef-applications.index') }}"
                class="btn btn-sm rounded-pill px-3 {{ request('status') == null ? 'btn-primary' : 'btn-outline-subtle text-secondary' }}">
                {{ __('All') }}
            </a>
            <a href="{{ route('dashboard.chef-applications.index', ['status' => 'pending']) }}"
                class="btn btn-sm rounded-pill px-3 {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline-subtle text-secondary' }}">
                {{ __('Pending') }}
            </a>
            <a href="{{ route('dashboard.chef-applications.index', ['status' => 'approved']) }}"
                class="btn btn-sm rounded-pill px-3 {{ request('status') == 'approved' ? 'btn-primary' : 'btn-outline-subtle text-secondary' }}">
                {{ __('Approved') }}
            </a>
            <a href="{{ route('dashboard.chef-applications.index', ['status' => 'rejected']) }}"
                class="btn btn-sm rounded-pill px-3 {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-outline-subtle text-secondary' }}">
                {{ __('Rejected') }}
            </a>
        </div>

        <!-- Table Card -->
        <div class="table-custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-4" style="width: 80px">{{ __('ID') }}</th>
                            <th class="text-start ps-3">{{ __('Applicant') }}</th>
                            <th class="text-start ps-3">{{ __('Kitchen Name') }}</th>
                            <th class="text-start ps-3">{{ __('Phone') }}</th>
                            <th class="text-start ps-3">{{ __('Address') }}</th>
                            <th class="text-start ps-3" style="width: 120px">{{ __('Status') }}</th>
                            <th class="text-start ps-3" style="width: 140px">{{ __('Submitted At') }}</th>
                            <th class="text-end pe-4" style="width: 180px">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($applications as $app)
                            <tr>
                                <td class="text-start ps-4 text-secondary fw-semibold">#{{ $app->id }}</td>
                                <td class="text-start ps-3 fw-semibold text-main">{{ $app->user?->name ?? __('Unknown') }}</td>
                                <td class="text-start ps-3 text-secondary">{{ $app->kitchen_name }}</td>
                                <td class="text-start ps-3 text-secondary">{{ $app->phone }}</td>
                                <td class="text-start ps-3 text-secondary">{{ $app->address ?? '-' }}</td>
                                <td class="text-start ps-3">
                                    @php
                                        $badge = match($app->status) {
                                            'approved' => 'bg-success text-white',
                                            'rejected' => 'bg-danger text-white',
                                            default => 'bg-warning text-dark'
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }} rounded-pill px-2.5 py-1">{{ __($app->status) }}</span>
                                </td>
                                <td class="text-start ps-3 text-secondary small">{{ $app->created_at->format('Y-m-d') }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                        @if($app->status === 'pending')
                                            <form method="POST" action="{{ route('dashboard.chef-applications.approve', $app->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-outline-success rounded-pill px-3"
                                                    onclick="return confirm('{{ __('Approve this application and promote user to Chef?') }}')">
                                                    <i class="fas fa-check me-1"></i> {{ __('Approve') }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('dashboard.chef-applications.reject', $app->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn-action-ghost-danger"
                                                    onclick="return confirm('{{ __('Reject this application?') }}')">
                                                    <i class="fas fa-times me-1"></i> {{ __('Reject') }}
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-secondary small">{{ __('Processed') }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-secondary py-5">
                                    <i class="fas fa-id-card fs-2 mb-2 d-block opacity-50"></i>
                                    {{ __('No chef applications found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($applications->hasPages())
                <div class="p-3 border-top border-subtle">
                    {{ $applications->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
