<section>
    <header>
        <h2 class="h5 fw-bold text-light mb-1">
            {{ __('Profile Information') }}
        </h2>

        <p class="text-secondary small mb-4">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-4" enctype="multipart/form-data">
        @csrf
        @method('patch')

        @if($user->role === 'chef')
            @php
                $user->loadMissing('image');
                $userHasImage = $user->relationLoaded('image') && $user->image && $user->image->image_path;
            @endphp
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="flex-shrink-0">
                    @if($userHasImage)
                        <img class="rounded-circle object-fit-cover border border-primary border-opacity-30" style="width: 72px; height: 72px;" src="{{ asset('storage/' . $user->image->image_path) }}" alt="{{ $user->name }}">
                    @else
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-secondary border border-secondary border-opacity-20" style="width: 72px; height: 72px; background: var(--surface-2);">
                            <i class="fas fa-camera fa-lg"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <label class="form-label text-secondary small fw-medium mb-1">{{ __('Profile Photo') }}</label>
                    <input type="file" name="image" class="form-control form-control-sm text-light border-secondary border-opacity-25" style="background: var(--surface-2);"/>
                </div>
            </div>
        @endif

        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="small mt-2 text-warning">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="text-primary text-decoration-underline ms-1">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 fw-medium small text-success">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="mb-3">
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        @if($user->role === 'chef')
            <hr class="border-secondary border-opacity-20 my-4">
            <h5 class="fw-bold text-light mb-3">{{ __('Kitchen Information') }}</h5>

            <div class="mb-3">
                <x-input-label for="kitchen_name" :value="__('Kitchen Name')" />
                <x-text-input id="kitchen_name" name="kitchen_name" type="text" class="mt-1 block w-full" :value="old('kitchen_name', $user->kitchen_name)" :placeholder="__('e.g. Grandma\'s Kitchen')" />
                <x-input-error class="mt-2" :messages="$errors->get('kitchen_name')" />
            </div>

            <div class="mb-3">
                <x-input-label for="address" :value="__('Address')" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" :placeholder="__('Kitchen Location')" />
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

            <div class="mb-3">
                <x-input-label for="bio" :value="__('About You / Bio')" />
                <textarea id="bio" name="bio" class="mt-1 block w-full rounded-3 px-3 py-2 text-light border border-secondary border-opacity-25" style="background: var(--surface-2); outline: none;" rows="4" placeholder="{{ __('Tell customers about your kitchen and specialty...') }}">{{ old('bio', $user->bio) }}</textarea>
                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
            </div>
        @endif

        <div class="d-flex align-items-center gap-3 pt-2">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-success small mb-0"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
