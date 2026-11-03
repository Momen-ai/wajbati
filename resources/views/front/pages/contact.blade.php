@extends('layouts.frontend')

@section('content')
    <!-- MOBILE VIEW (<768px): Contact & Support -->
    <div class="d-md-none px-3 pt-3 pb-5 has-bottom-bar" style="background: #0a0a0a; min-height: 100vh;">
        <!-- Header -->
        <div class="mb-3">
            <h1 class="fw-bolder text-light mb-1" style="font-size: 26px; letter-spacing: -0.02em;">
                {{ __('Get in Touch With Us') }}
            </h1>
            <p class="text-secondary small mb-0">
                {{ __('Have questions or need assistance? We are here to help.') }}
            </p>
        </div>

        @if (session('success'))
            <div class="alert alert-success rounded-4 mb-3 border border-success border-opacity-25 small" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Contact Form Card -->
        <div class="p-4 rounded-4 border border-secondary border-opacity-10 mb-4 shadow-lg" style="background: var(--surface-1);">
            <h2 class="fw-bold text-light mb-1" style="font-size: 17px;">{{ __('Send us a message') }}</h2>
            <p class="text-secondary small mb-3">{{ __('Fill out the details below and we will get back to you promptly.') }}</p>

            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="d-flex flex-column gap-3">
                    <div>
                        <label class="form-label text-light fw-semibold small mb-1">{{ __('Name') }}</label>
                        <input name="name" class="form-control text-light border border-secondary border-opacity-25"
                               style="background: var(--surface-2); font-size: 16px;" placeholder="{{ __('Your name') }}"
                               value="{{ old('name', auth()->user()?->name) }}" required>
                    </div>
                    <div>
                        <label class="form-label text-light fw-semibold small mb-1">{{ __('Email') }}</label>
                        <input name="email" type="email" class="form-control text-light border border-secondary border-opacity-25"
                               style="background: var(--surface-2); font-size: 16px;" placeholder="{{ __('you@company.com') }}"
                               value="{{ old('email', auth()->user()?->email) }}" required>
                    </div>
                    <div>
                        <label class="form-label text-light fw-semibold small mb-1">{{ __('Subject') }}</label>
                        <input name="subject" class="form-control text-light border border-secondary border-opacity-25"
                               style="background: var(--surface-2); font-size: 16px;" placeholder="{{ __('How can we help?') }}"
                               value="{{ old('subject') }}" required>
                    </div>
                    <div>
                        <label class="form-label text-light fw-semibold small mb-1">{{ __('Message') }}</label>
                        <textarea name="message" class="form-control text-light border border-secondary border-opacity-25"
                                  style="background: var(--surface-2); font-size: 16px;" rows="4"
                                  placeholder="{{ __('Tell us more...') }}" required>{{ old('message') }}</textarea>
                    </div>
                    <button class="btn btn-primary rounded-pill py-2.5 fw-bold shadow-lg mt-1" type="submit">
                        <i class="fas fa-paper-plane me-1"></i> {{ __('Send Message') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Channels Row -->
        <div class="p-3 rounded-4 border border-secondary border-opacity-10 mb-4" style="background: var(--surface-1);">
            <h6 class="fw-bold text-light mb-3" style="font-size: 14px;">{{ __('Contact Channels') }}</h6>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex align-items-center gap-3 p-2 rounded-3 border border-secondary border-opacity-10" style="background: var(--surface-2);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 36px; height: 36px; background: var(--surface-1);">
                        <i class="fas fa-envelope small"></i>
                    </div>
                    <div>
                        <span class="text-secondary d-block" style="font-size: 0.7rem;">{{ __('Email') }}</span>
                        <span class="text-light small fw-medium">{{ config('mail.from.address', 'support@wajbati.com') }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 p-2 rounded-3 border border-secondary border-opacity-10" style="background: var(--surface-2);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 36px; height: 36px; background: var(--surface-1);">
                        <i class="fas fa-phone-alt small"></i>
                    </div>
                    <div>
                        <span class="text-secondary d-block" style="font-size: 0.7rem;">{{ __('Phone') }}</span>
                        <span class="text-light small fw-medium">+970 599 000 000</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 p-2 rounded-3 border border-secondary border-opacity-10" style="background: var(--surface-2);">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 36px; height: 36px; background: var(--surface-1);">
                        <i class="fas fa-clock small"></i>
                    </div>
                    <div>
                        <span class="text-secondary d-block" style="font-size: 0.7rem;">{{ __('Customer Support Hours') }}</span>
                        <span class="text-light small fw-medium">{{ __('Saturday – Thursday: 9:00 AM – 11:00 PM') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile FAQ Accordion -->
        <div class="p-3 rounded-4 border border-secondary border-opacity-10" style="background: var(--surface-1);">
            <h6 class="fw-bold text-light mb-3" style="font-size: 14px;">{{ __('Frequently Asked Questions') }}</h6>
            <div class="accordion custom-faq-accordion" id="mobileContactFaqAccordion">
                <div class="accordion-item mb-2 rounded-3 overflow-hidden border border-secondary border-opacity-10" style="background: var(--surface-2);">
                    <h2 class="accordion-header" id="mFaqHOne">
                        <button class="accordion-button collapsed text-light fw-semibold p-3 shadow-none small" style="background: var(--surface-2);" type="button" data-bs-toggle="collapse" data-bs-target="#mFaqCOne">
                            {{ __('How does ordering from independent chefs and kitchens work?') }}
                        </button>
                    </h2>
                    <div id="mFaqCOne" class="accordion-collapse collapse" data-bs-parent="#mobileContactFaqAccordion">
                        <div class="accordion-body p-3 pt-1 text-secondary small border-top border-secondary border-opacity-10" style="font-size: 0.8rem;">
                            {{ __('Browse local chefs and dishes, customize your quantities, and add items to your cart. Once placed, the chef receives your order and prepares your meal fresh on demand before delivering it straight to your address.') }}
                        </div>
                    </div>
                </div>
                <div class="accordion-item mb-2 rounded-3 overflow-hidden border border-secondary border-opacity-10" style="background: var(--surface-2);">
                    <h2 class="accordion-header" id="mFaqHTwo">
                        <button class="accordion-button collapsed text-light fw-semibold p-3 shadow-none small" style="background: var(--surface-2);" type="button" data-bs-toggle="collapse" data-bs-target="#mFaqCTwo">
                            {{ __('How do you ensure food safety and kitchen hygiene?') }}
                        </button>
                    </h2>
                    <div id="mFaqCTwo" class="accordion-collapse collapse" data-bs-parent="#mobileContactFaqAccordion">
                        <div class="accordion-body p-3 pt-1 text-secondary small border-top border-secondary border-opacity-10" style="font-size: 0.8rem;">
                            {{ __('Every chef on Wajbati undergoes an identity and kitchen verification check. Chefs commit to strict cleanliness standards, use fresh market ingredients, and are held accountable through transparent verified customer ratings and reviews.') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DESKTOP VIEW (>=768px): Strictly Unchanged -->
    <div class="d-none d-md-block">
    <!-- Inner Page Banner (Frame f_025) -->
    <x-ui.page-banner
        :image="asset('assets/hero/f_025.webp')"
        :badge="__('Help & Support')"
        :title="__('Get in Touch With Us')"
        :subtitle="__('Have a question about an order, want to partner with us, or have feedback? Our friendly team is always here to help.')">
        <x-slot name="stats">
            <span class="badge rounded-pill bg-surface-2 text-primary border border-subtle px-3 py-1.5 fw-semibold small">
                <i class="far fa-clock me-1 text-primary"></i> {{ __('We reply within 24h') }}
            </span>
            <span class="badge rounded-pill bg-surface-2 text-main border border-subtle px-3 py-1.5 fw-semibold small">
                <i class="fas fa-headset text-success me-1"></i> {{ __('Dedicated Support') }}
            </span>
        </x-slot>
    </x-ui.page-banner>

    <div class="container py-5 ambient-glow-page">
        <!-- Main Form & Contact Channels (Two Columns) -->
        <div class="row align-items-stretch g-4 g-lg-5 mb-5">
            <!-- Left Column: Contact Channels & Location -->
            <div class="col-lg-5">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div>
                        <span class="badge rounded-pill px-3 py-1 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-20 fw-bold mb-3">
                            {{ __('Contact Channels') }}
                        </span>
                        <h2 class="h3 fw-bold mb-3 text-main">{{ __('We would love to hear from you') }}</h2>
                        <p class="text-secondary mb-4">{{ __("We'd love to hear from you! Please fill out the form or reach out to us using the contact details.") }}</p>

                        <div class="d-flex align-items-center mb-3 p-3 rounded-4 border border-subtle spotlight-card" data-spotlight>
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary me-3 flex-shrink-0 bg-surface-2 shadow-sm" style="width: 48px; height: 48px;">
                                <i class="fas fa-envelope fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-main">{{ __('Email') }}</h6>
                                <p class="text-secondary mb-0 small">{{ config('mail.from.address', 'support@wajbati.com') }}</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3 p-3 rounded-4 border border-subtle spotlight-card" data-spotlight>
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary me-3 flex-shrink-0 bg-surface-2 shadow-sm" style="width: 48px; height: 48px;">
                                <i class="fas fa-phone-alt fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-main">{{ __('Phone') }}</h6>
                                <p class="text-secondary mb-0 small">+970 599 000 000</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4 p-3 rounded-4 border border-subtle spotlight-card" data-spotlight>
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary me-3 flex-shrink-0 bg-surface-2 shadow-sm" style="width: 48px; height: 48px;">
                                <i class="fas fa-map-marker-alt fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0 text-main">{{ __('Office') }}</h6>
                                <p class="text-secondary mb-0 small">{{ __('Palestine, Gaza & West Bank') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Location & Operating Hours Card -->
                    <div class="p-4 rounded-4 border border-subtle bg-surface-2 mt-2">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="fas fa-building text-primary"></i>
                            <h6 class="fw-bold text-main mb-0">{{ __('Customer Support Hours') }}</h6>
                        </div>
                        <p class="text-secondary small mb-2">
                            {{ __('Saturday – Thursday: 9:00 AM – 11:00 PM') }}
                        </p>
                        <p class="text-secondary small mb-0">
                            {{ __('Friday: 1:00 PM – 11:00 PM') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Interactive Contact Form -->
            <div class="col-lg-7">
                <div class="card p-4 p-md-5 rounded-4 border border-subtle shadow-lg h-100 spotlight-card" data-spotlight style="background: var(--surface-1);">
                    @if (session('success'))
                        <div class="alert alert-success rounded-3 mb-4 border border-success border-opacity-25" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <h4 class="fw-bold text-main mb-2">{{ __('Send us a message') }}</h4>
                    <p class="text-secondary small mb-4">{{ __('Fill out the details below and we will get back to you promptly.') }}</p>

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-main fw-semibold small">{{ __('Name') }}</label>
                                <input name="name" class="form-control bg-surface-2 text-main border-subtle" placeholder="{{ __('Your name') }}" value="{{ old('name', auth()->user()?->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-main fw-semibold small">{{ __('Email') }}</label>
                                <input name="email" type="email" class="form-control bg-surface-2 text-main border-subtle" placeholder="{{ __('you@company.com') }}" value="{{ old('email', auth()->user()?->email) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-main fw-semibold small">{{ __('Subject') }}</label>
                                <input name="subject" class="form-control bg-surface-2 text-main border-subtle" placeholder="{{ __('How can we help?') }}" value="{{ old('subject') }}" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label text-main fw-semibold small">{{ __('Message') }}</label>
                                <textarea name="message" class="form-control bg-surface-2 text-main border-subtle" rows="5" placeholder="{{ __('Tell us more...') }}" required>{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-lg" type="submit" data-magnetic>
                                    <i class="fas fa-paper-plane me-2"></i> {{ __('Send Message') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- FAQ Accordion Section (Removes void above footer) -->
        <div class="pt-5 border-top border-subtle">
            <div class="text-center mb-5">
                <span class="badge rounded-pill px-3 py-1 bg-surface-2 text-primary border border-subtle small fw-bold mb-2">
                    {{ __('Common Inquiries') }}
                </span>
                <h3 class="fw-bold text-main mb-2">{{ __('Frequently Asked Questions') }}</h3>
                <p class="text-secondary small mb-0">{{ __('Everything you need to know about ordering, hygiene, and joining Wajbati.') }}</p>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="accordion custom-faq-accordion" id="contactFaqAccordion">
                        <!-- FAQ Item 1 -->
                        <div class="accordion-item mb-3 rounded-4 overflow-hidden border border-subtle bg-surface-1">
                            <h2 class="accordion-header" id="faqHeadingOne">
                                <button class="accordion-button collapsed bg-surface-1 text-main fw-semibold px-4 py-3 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="false" aria-controls="faqCollapseOne">
                                    <i class="fas fa-circle-question text-primary me-3"></i>
                                    {{ __('How does ordering from independent chefs and kitchens work?') }}
                                </button>
                            </h2>
                            <div id="faqCollapseOne" class="accordion-collapse collapse" aria-labelledby="faqHeadingOne" data-bs-parent="#contactFaqAccordion">
                                <div class="accordion-body px-4 pb-4 pt-1 text-secondary small border-top border-subtle">
                                    {{ __('Browse local chefs and dishes, customize your quantities, and add items to your cart. Once placed, the chef receives your order and prepares your meal fresh on demand before delivering it straight to your address.') }}
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 2 -->
                        <div class="accordion-item mb-3 rounded-4 overflow-hidden border border-subtle bg-surface-1">
                            <h2 class="accordion-header" id="faqHeadingTwo">
                                <button class="accordion-button collapsed bg-surface-1 text-main fw-semibold px-4 py-3 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                                    <i class="fas fa-shield-halved text-primary me-3"></i>
                                    {{ __('How do you ensure food safety and kitchen hygiene?') }}
                                </button>
                            </h2>
                            <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo" data-bs-parent="#contactFaqAccordion">
                                <div class="accordion-body px-4 pb-4 pt-1 text-secondary small border-top border-subtle">
                                    {{ __('Every chef on Wajbati undergoes an identity and kitchen verification check. Chefs commit to strict cleanliness standards, use fresh market ingredients, and are held accountable through transparent verified customer ratings and reviews.') }}
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 3 -->
                        <div class="accordion-item mb-3 rounded-4 overflow-hidden border border-subtle bg-surface-1">
                            <h2 class="accordion-header" id="faqHeadingThree">
                                <button class="accordion-button collapsed bg-surface-1 text-main fw-semibold px-4 py-3 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                                    <i class="fas fa-kitchen-set text-primary me-3"></i>
                                    {{ __('Can I apply to join Wajbati as a chef or kitchen partner?') }}
                                </button>
                            </h2>
                            <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree" data-bs-parent="#contactFaqAccordion">
                                <div class="accordion-body px-4 pb-4 pt-1 text-secondary small border-top border-subtle">
                                    {{ __('Yes! Anyone with a passion for cooking can submit an application via the Join as a Chef page. Our administration team reviews your menu and kitchen information within 24 to 48 hours to activate your kitchen dashboard.') }}
                                </div>
                            </div>
                        </div>

                        <!-- FAQ Item 4 -->
                        <div class="accordion-item mb-3 rounded-4 overflow-hidden border border-subtle bg-surface-1">
                            <h2 class="accordion-header" id="faqHeadingFour">
                                <button class="accordion-button collapsed bg-surface-1 text-main fw-semibold px-4 py-3 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFour" aria-expanded="false" aria-controls="faqCollapseFour">
                                    <i class="fas fa-box-open text-primary me-3"></i>
                                    {{ __('How does delivery fee and delivery packaging work?') }}
                                </button>
                            </h2>
                            <div id="faqCollapseFour" class="accordion-collapse collapse" aria-labelledby="faqHeadingFour" data-bs-parent="#contactFaqAccordion">
                                <div class="accordion-body px-4 pb-4 pt-1 text-secondary small border-top border-subtle">
                                    {{ __('Delivery is calculated transparently per kitchen so you always know your exact total. Dishes are packaged in heat-retaining food-grade containers to ensure meals arrive piping hot at your table.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
