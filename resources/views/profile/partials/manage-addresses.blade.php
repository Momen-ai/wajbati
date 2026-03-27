<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Manage Addresses') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Add or remove your delivery addresses.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        @foreach($user->addresses as $address)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 uppercase">
                        {{ $address->type }}
                    </span>
                    <p class="mt-1 text-sm text-gray-900">{{ $address->address }}, {{ $address->city }}</p>
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

        <form method="post" action="{{ route('profile.address.store') }}" class="space-y-4">
            @csrf
            <div>
                <x-input-label for="type" :value="__('Type')" />
                <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="home">Home</option>
                    <option value="work">Work</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" placeholder="Riyadh, Jeddah..." />
            </div>
            <div>
                <x-input-label for="address" :value="__('Address Detail')" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" placeholder="Street name, Building number..." required />
            </div>
            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Add Address') }}</x-primary-button>
            </div>
        </form>
    </div>
</section>
