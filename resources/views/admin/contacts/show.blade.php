<x-app-layout>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between">
                        <h5 class="mb-0">{{ __('Message Details') }}</h5>
                        <span class="badge bg-info text-dark">
                            {{ __(ucfirst($contact->type)) }}
                        </span>
                    </div>

                    <div class="card-body">

                        <p>
                            <strong>{{ __('Sender') }}:</strong>
                            @if ($contact->user)
                                {{ $contact->user->name }} ({{ __('User') }}) - {{ $contact->user->email }}
                            @elseif ($contact->chef)
                                {{ $contact->chef->name }} ({{ __('Chef') }}) - {{ $contact->chef->email }}
                            @else
                                {{ $contact->name ?? __('Guest') }} ({{ __('Guest') }})
                            @endif
                        </p>

                        @if ($contact->email)
                            <p><strong>{{ __('Email') }}:</strong> {{ $contact->email }}</p>
                        @endif

                        @if ($contact->phone)
                            <p><strong>{{ __('Phone') }}:</strong> {{ $contact->phone }}</p>
                        @endif

                        <p><strong>{{ __('Status') }}:</strong> <span class="badge {{ $contact->is_read ? 'bg-success' : 'bg-warning' }}">{{ $contact->is_read ? __('Read') : __('Unread') }}</span></p>

                        <p><strong>{{ __('Title') }}:</strong> {{ $contact->title }}</p>

                        <hr>

                        <p class="p-3 bg-light rounded text-dark">{{ $contact->message }}</p>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                @php $replyEmail = $contact->email ?? ($contact->user?->email ?? $contact->chef?->email); @endphp
                                @if ($replyEmail)
                                    <a href="mailto:{{ $replyEmail }}?subject=Re: {{ rawurlencode($contact->title) }}" class="btn btn-primary btn-sm">
                                        {{ __('Reply via Email') }}
                                    </a>
                                @endif

                                <form method="POST" action="{{ route('dashboard.contacts.toggle-read', $contact->id) }}" class="d-inline ms-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        {{ $contact->is_read ? __('Mark as Unread') : __('Mark as Read') }}
                                    </button>
                                </form>
                            </div>

                            <a href="{{ route('dashboard.contacts.index') }}" class="btn btn-secondary">
                                {{ __('Back') }}
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
