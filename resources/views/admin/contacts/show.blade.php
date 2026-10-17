<x-app-layout>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between">
                        <h5 class="mb-0">Message Details</h5>
                        <span class="badge bg-info text-dark">
                            {{ ucfirst($contact->type) }}
                        </span>
                    </div>

                    <div class="card-body">

                        <p>
                            <strong>Sender:</strong>
                            @if ($contact->user)
                                {{ $contact->user->name }} (User) - {{ $contact->user->email }}
                            @elseif ($contact->chef)
                                {{ $contact->chef->name }} (Chef) - {{ $contact->chef->email }}
                            @else
                                {{ $contact->name ?? 'Guest' }} (Guest)
                            @endif
                        </p>

                        @if ($contact->email)
                            <p><strong>Email:</strong> {{ $contact->email }}</p>
                        @endif

                        @if ($contact->phone)
                            <p><strong>Phone:</strong> {{ $contact->phone }}</p>
                        @endif

                        <p><strong>Status:</strong> <span class="badge {{ $contact->is_read ? 'bg-success' : 'bg-warning' }}">{{ $contact->is_read ? 'Read' : 'Unread' }}</span></p>

                        <p><strong>Title:</strong> {{ $contact->title }}</p>

                        <hr>

                        <p class="p-3 bg-light rounded text-dark">{{ $contact->message }}</p>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @php $replyEmail = $contact->email ?? ($contact->user?->email ?? $contact->chef?->email); @endphp
                                @if ($replyEmail)
                                    <a href="mailto:{{ $replyEmail }}?subject=Re: {{ rawurlencode($contact->title) }}" class="btn btn-primary btn-sm">
                                        Reply via Email
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('dashboard.contacts.toggle-read', $contact->id) }}" class="d-inline ms-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        Mark as {{ $contact->is_read ? 'Unread' : 'Read' }}
                                    </button>
                                </form>
                            </div>

                            <a href="{{ route('dashboard.contacts.index') }}" class="btn btn-secondary">
                                Back
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
