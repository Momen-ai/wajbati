<x-app-layout>
    <div class="container mt-4">

        <div class="d-flex justify-content-between mb-3">
            <h4>Contacts</h4>
            <a href="{{ route('dashboard.contacts.create') }}" class="btn btn-success">
                New Message
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>#</th>
                            <th>Sender</th>
                            <th>Type</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th width="180">Actions</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        @foreach ($contacts as $contact)
                            <tr>
                                <td>{{ $contact->id }}</td>

                                <td>
                                    @if ($contact->user)
                                        <span class="badge bg-primary">
                                            {{ $contact->user->name }} (User)
                                        </span>
                                    @elseif ($contact->chef)
                                        <span class="badge bg-warning text-dark">
                                            {{ $contact->chef->name }} (Chef)
                                        </span>
                                    @else
                                        <span class="text-muted">Guest</span>
                                    @endif
                                </td>

                                <td>{{ ucfirst($contact->type) }}</td>
                                <td>{{ $contact->title }}</td>

                                <td>
                                    @if ($contact->is_read)
                                        <span class="badge bg-success">Read</span>
                                    @else
                                        <span class="badge bg-secondary">Unread</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('dashboard.contacts.show', $contact->id) }}"
                                        class="btn btn-sm btn-primary">
                                        View
                                    </a>

                                    <form method="POST"
                                        action="{{ route('dashboard.contacts.destroy', $contact->id) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{ $contacts->links() }}

    </div>
</x-app-layout>
