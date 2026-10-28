@extends('layouts.frontend')

@section('content')
    <div class="py-5 mb-5 border-bottom border-subtle bg-surface-1">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-3 text-main">{{ __('Meet Our Chefs') }}</h1>
            <p class="lead text-secondary mx-auto mb-0" style="max-width: 650px;">
                {{ __('Discover the talented families behind the delicious homemade meals. Each kitchen has a story and a unique taste.') }}
            </p>
        </div>
    </div>

    <div class="container mb-5 pb-5">
        <div class="row g-4">
            @forelse($chefs as $chef)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                    <x-ui.chef-card :chef="$chef" />
                </div>
            @empty
                <div class="col-12 py-5">
                    <x-ui.empty-state
                        icon="fas fa-hat-chef"
                        :title="__('No family kitchens found.')"
                        :actionText="__('Discover Meals')"
                        :actionUrl="route('meals.index')" />
                </div>
            @endforelse
        </div>

        @if($chefs->hasPages())
            <div class="mt-5 d-flex justify-content-center">
                {{ $chefs->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
