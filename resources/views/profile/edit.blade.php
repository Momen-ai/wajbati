<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 fw-bold text-light mb-0">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-3 py-md-4">
        <div class="container px-3 px-md-4 space-y-4" style="max-width: 900px;">
            <div class="p-3 p-md-5 rounded-4 border border-secondary border-opacity-10 shadow-sm mb-4" style="background: var(--surface-1);">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-3 p-md-5 rounded-4 border border-secondary border-opacity-10 shadow-sm mb-4" style="background: var(--surface-1);">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-3 p-md-5 rounded-4 border border-secondary border-opacity-10 shadow-sm mb-4" style="background: var(--surface-1);">
                <div class="max-w-xl">
                    @include('profile.partials.manage-addresses')
                </div>
            </div>

            <div class="p-3 p-md-5 rounded-4 border border-secondary border-opacity-10 shadow-sm mb-4" style="background: var(--surface-1);">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
