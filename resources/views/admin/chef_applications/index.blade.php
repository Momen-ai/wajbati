<x-app-layout>
    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h4 class="mb-0 fw-bold">{{ __('Chef Applications') }}</h4>

            <div class="btn-group">
                <a href="{{ route('dashboard.chef-applications.index') }}"
                    class="btn btn-outline-secondary {{ request('status') == null ? 'active' : '' }}">
                    {{ __('All') }}
                </a>
                <a href="{{ route('dashboard.chef-applications.index', ['status' => 'pending']) }}"
                    class="btn btn-outline-secondary {{ request('status') == 'pending' ? 'active' : '' }}">
                    {{ __('Pending') }}
                </a>
                <a href="{{ route('dashboard.chef-applications.index', ['status' => 'approved']) }}"
                    class="btn btn-outline-secondary {{ request('status') == 'approved' ? 'active' : '' }}">
                    {{ __('Approved') }}
                </a>
                <a href="{{ route('dashboard.chef-applications.index', ['status' => 'rejected']) }}"
                    class="btn btn-outline-secondary {{ request('status') == 'rejected' ? 'active' : '' }}">
                    {{ __('Rejected') }}
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Applicant') }}</th>
                        <th>{{ __('Kitchen Name') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Address') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Submitted At') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $app)
                        <tr>
                            <td>{{ $app->id }}</td>
                            <td class="fw-semibold">{{ $app->user?->name ?? __('Unknown') }}</td>
                            <td>{{ $app->kitchen_name }}</td>
                            <td>{{ $app->phone }}</td>
                            <td>{{ $app->address ?? '-' }}</td>
                            <td>
                                @php
                                    $badge = match($app->status) {
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                        default => 'bg-warning text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $badge }}">{{ __($app->status) }}</span>
                            </td>
                            <td>{{ $app->created_at->format('Y-m-d') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    @if($app->status === 'pending')
                                        <form method="POST" action="{{ route('dashboard.chef-applications.approve', $app->id) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-success" onclick="return confirm('{{ __('Approve this application and promote user to Chef?') }}')">
                                                {{ __('Approve') }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('dashboard.chef-applications.reject', $app->id) }}">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('Reject this application?') }}')">
                                                {{ __('Reject') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">{{ __('Processed') }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted py-4">{{ __('No chef applications found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $applications->withQueryString()->links() }}
        </div>
    </div>
</x-app-layout>
