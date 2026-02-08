<x-app-layout>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">New Contact</h5>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.contacts.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="contact">Contact</option>
                                    <option value="complaint">Complaint</option>
                                    <option value="support">Support</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea name="message" rows="5" class="form-control" required></textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('dashboard.contacts.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                                <button class="btn btn-primary">
                                    Send
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
