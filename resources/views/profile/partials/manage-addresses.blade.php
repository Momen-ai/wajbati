<section>
    <header>
        <h2 class="h5 fw-bold text-light mb-1">
            {{ __('Manage Addresses') }}
        </h2>
        <p class="text-secondary small mb-4">
            {{ __('Add or remove your delivery addresses.') }}
        </p>
    </header>

    <div class="mt-4 space-y-4">
        @foreach($user->addresses as $address)
            <div class="d-flex align-items-center justify-content-between p-3 rounded-3 border border-secondary border-opacity-10 mb-3" style="background: var(--surface-2);">
                <div>
                    <span class="badge rounded-pill bg-primary bg-opacity-10 text-primary uppercase small px-3 py-1">
                        {{ __($address->type) }}
                    </span>
                    <p class="mt-2 text-light small mb-0">{{ $address->address }}, {{ $address->city }}</p>
                </div>
                <form method="post" action="{{ route('profile.address.destroy', $address) }}">
                    @csrf
                    @method('delete')
                    <x-danger-button>
                        {{ __('Remove') }}
                    </x-danger-button>
                </form>
            </div>
        @endforeach

        <form method="post" action="{{ route('profile.address.store') }}" class="space-y-3 mt-4 pt-3 border-top border-secondary border-opacity-15">
            @csrf
            <div class="mb-3">
                <x-input-label for="type" :value="__('Type')" />
                <select id="type" name="type" class="form-select text-light border border-secondary border-opacity-25" style="background: var(--surface-2);">
                    <option value="home">{{ __('Home') }}</option>
                    <option value="work">{{ __('Work') }}</option>
                    <option value="other">{{ __('Other') }}</option>
                </select>
            </div>
            <div class="mb-3">
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" placeholder="{{ __('Riyadh, Jeddah...') }}" />
            </div>
            <div class="mb-3">
                <x-input-label for="address" :value="__('Address Detail')" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" placeholder="{{ __('Street name, Building number...') }}" required />
            </div>
            <div class="pt-2">
                <x-primary-button>{{ __('Add Address') }}</x-primary-button>
            </div>
        </form>
    </div>
</section>
