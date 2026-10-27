<x-app-layout>
    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">
            <h4>{{ __('Contacts') }}</h4>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>{{ __('Sender') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th width="180">{{ __('Actions') }}</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $contact->id }}</td>

                                <td>
                                    @if ($contact->user)
                                        <span class="badge bg-primary">
                                            {{ $contact->user->name }} ({{ __('User') }})
                                        </span>
                                    @elseif ($contact->chef)
                                        <span class="badge bg-warning text-dark">
                                            {{ $contact->chef->name }} ({{ __('Chef') }})
                                        </span>
                                    @else
                                        <span class="text-muted">{{ __('Guest') }}</span>
                                    @endif
                                </td>

                                <td>{{ __(ucfirst($contact->type)) }}</td>
                                <td>{{ $contact->title }}</td>

                                <td>
                                    @if ($contact->is_read)
                                        <span class="badge bg-success">{{ __('Read') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Unread') }}</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('dashboard.contacts.show', $contact->id) }}"
                                        class="btn btn-sm btn-primary">
                                        {{ __('View') }}
                                    </a>

                                    <form method="POST"
                                        action="{{ route('dashboard.contacts.destroy', $contact->id) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $contacts->withQueryString()->links() }}
        </div>

    </div>
</x-app-layout>
