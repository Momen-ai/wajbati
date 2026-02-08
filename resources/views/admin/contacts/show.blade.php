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
                                {{ $contact->user->name }} (User)
                            @elseif ($contact->chef)
                                {{ $contact->chef->name }} (Chef)
                            @else
                                Guest
                            @endif
                        </p>

                        <p><strong>Title:</strong> {{ $contact->title }}</p>

                        <hr>

                        <p>{{ $contact->message }}</p>

                        <hr>

                        <div class="d-flex justify-content-end">
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
