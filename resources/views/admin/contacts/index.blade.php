<x-app-layout>
    <div class="mb-4">
        <!-- Header -->
        <x-ui.page-header :title="__('Contacts')" />

        <!-- Table Card -->
        <div class="table-custom-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start ps-4" style="width: 80px">{{ __('ID') }}</th>
                            <th class="text-start ps-3">{{ __('Sender') }}</th>
                            <th class="text-start ps-3" style="width: 140px">{{ __('Type') }}</th>
                            <th class="text-start ps-3">{{ __('Title') }}</th>
                            <th class="text-start ps-3" style="width: 120px">{{ __('Status') }}</th>
                            <th class="text-end pe-4" style="width: 180px">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contacts as $contact)
                            <tr>
                                <td class="text-start ps-4 text-secondary fw-semibold">#{{ $contact->id }}</td>
                                <td class="text-start ps-3">
                                    @if ($contact->user)
                                        <span class="badge bg-primary text-white rounded-pill px-2.5 py-1">
                                            {{ $contact->user->name }} ({{ __('User') }})
                                        </span>
                                    @elseif ($contact->chef)
                                        <span class="badge bg-warning text-dark rounded-pill px-2.5 py-1">
                                            {{ $contact->chef->name }} ({{ __('Chef') }})
                                        </span>
                                    @else
                                        <span class="text-secondary">{{ __('Guest') }}</span>
                                    @endif
                                </td>
                                <td class="text-start ps-3 text-secondary">{{ __(ucfirst($contact->type)) }}</td>
                                <td class="text-start ps-3 fw-semibold text-main">{{ $contact->title }}</td>
                                <td class="text-start ps-3">
                                    @if ($contact->is_read)
                                        <span class="badge bg-success text-white rounded-pill px-2.5 py-1">{{ __('Read') }}</span>
                                    @else
                                        <span class="badge bg-secondary text-white rounded-pill px-2.5 py-1">{{ __('Unread') }}</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex align-items-center justify-content-end gap-2">
                                        <a href="{{ route('dashboard.contacts.show', $contact->id) }}"
                                            class="btn-action-ghost-neutral"
                                            title="{{ __('View') }}">
                                            <i class="fas fa-eye"></i>
                                            <span class="d-none d-md-inline ms-1">{{ __('View') }}</span>
                                        </a>

                                        <form method="POST" action="{{ route('dashboard.contacts.destroy', $contact->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn-action-ghost-danger"
                                                title="{{ __('Delete') }}"
                                                onclick="return confirm('{{ __('Are you sure want to remove?') }}')">
                                                <i class="fas fa-trash-alt"></i>
                                                <span class="d-none d-md-inline ms-1">{{ __('Delete') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-5">
                                    <i class="fas fa-inbox fs-2 mb-2 d-block opacity-50"></i>
                                    {{ __('No new messages') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($contacts->hasPages())
                <div class="p-3 border-top border-subtle">
                    {{ $contacts->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
