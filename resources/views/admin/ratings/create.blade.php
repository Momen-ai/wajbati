<x-app-layout>
    <div class="container mt-5 col-md-8">

        <div class="card">
            <div class="card-header bg-dark text-white">{{ __('Add Rating') }}</div>

            <div class="card-body">
                <form method="POST" action="{{ route('dashboard.ratings.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">{{ __('User') }}</label>
                        <select name="user_id" class="form-select">
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Chef') }}</label>
                        <select name="chef_id" class="form-select">
                            @foreach ($chefs as $chef)
                                <option value="{{ $chef->id }}">{{ $chef->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Stars') }}</label>
                        <select name="star" class="form-select">
                            @for ($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} ★</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('Comment') }}</label>
                        <textarea name="body" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard.ratings.index') }}" class="btn btn-secondary">
                            {{ __('Cancel') }}
                        </a>
                        <button class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
