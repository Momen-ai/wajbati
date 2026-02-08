@extends('layouts.frontend')

@section('content')
<div class="container py-5">
  <div class="row align-items-stretch g-5">
    
    <div class="col-lg-5">
        <div class="pe-lg-5">
            <h6 class="text-primary fw-bold text-uppercase mb-2">Contact Us</h6>
            <h1 class="display-5 fw-bold mb-4">Get in touch with our friendly team</h1>
            <p class="text-muted mb-5">We'd love to hear from you! Please fill out the form or reach out to us using the contact details.</p>

            <div class="d-flex align-items-center mb-4">
                <div class="bg-light rounded-circle p-3 me-3 text-primary"><i class="fas fa-envelope fa-lg"></i></div>
                <div>
                    <h6 class="fw-bold mb-0">Email</h6>
                    <p class="text-muted mb-0">contact@wajbati.com</p>
                </div>
            </div>
            
            <div class="d-flex align-items-center mb-4">
                <div class="bg-light rounded-circle p-3 me-3 text-primary"><i class="fas fa-phone-alt fa-lg"></i></div>
                <div>
                    <h6 class="fw-bold mb-0">Phone</h6>
                    <p class="text-muted mb-0">+1 (555) 000-0000</p>
                </div>
            </div>

            <div class="d-flex align-items-center mb-5">
                <div class="bg-light rounded-circle p-3 me-3 text-primary"><i class="fas fa-map-marker-alt fa-lg"></i></div>
                <div>
                    <h6 class="fw-bold mb-0">Office</h6>
                    <p class="text-muted mb-0">123 Foodie Street, Kitchen City</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-lg border-0 p-4 p-md-5 h-100">
            <form action="{{ route('contact.submit') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input name="name" class="form-control form-control-lg bg-light border-0" placeholder="Your name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control form-control-lg bg-light border-0" placeholder="you@company.com" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Subject</label>
                        <input name="subject" class="form-control form-control-lg bg-light border-0" placeholder="How can we help?">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="message" class="form-control form-control-lg bg-light border-0" rows="6" placeholder="Tell us more..." required></textarea>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary btn-lg w-100" type="submit">Send Message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

  </div>
</div>
@endsection
