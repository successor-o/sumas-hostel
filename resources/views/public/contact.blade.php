@extends('layouts.public')
@section('title', 'Contact Us')

@section('content')
@if(session('status'))
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1080;">
  <div id="contactToast" class="toast align-items-center border-0 show" role="alert">
    <div class="d-flex">
      <div class="toast-body d-flex align-items-center gap-2"><i class="fa-solid fa-circle-check" style="color:var(--sumas-success)"></i> {{ session('status') }}</div>
      <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
@endif

<header class="page-header">
  <div class="container">
    <span class="eyebrow">We're Here To Help</span>
    <h1>Contact Us</h1>
    <nav class="breadcrumb-sumas small mt-2"><a href="{{ route('home') }}">Home</a> <i class="fa-solid fa-angle-right mx-2" style="color:rgba(255,255,255,0.4)"></i> <span class="active">Contact</span></nav>
  </div>
</header>

<section class="section-pad">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-5">
        <span class="eyebrow">Get In Touch</span>
        <h2 class="mb-4">Hostel Office Details</h2>
        <div class="contact-info-item"><div class="icon"><i class="fa-solid fa-location-dot"></i></div><div><strong class="d-block">Campus Address</strong><span class="text-muted small">{{ config('sumas.campus_address') }}</span></div></div>
        <div class="contact-info-item"><div class="icon"><i class="fa-solid fa-phone"></i></div><div><strong class="d-block">Phone</strong><span class="text-muted small">{{ config('sumas.support_phone') }}</span></div></div>
        <div class="contact-info-item"><div class="icon"><i class="fa-solid fa-envelope"></i></div><div><strong class="d-block">Email</strong><span class="text-muted small">{{ config('sumas.support_email') }}</span></div></div>
        <div class="contact-info-item"><div class="icon"><i class="fa-solid fa-clock"></i></div><div><strong class="d-block">Office Hours</strong><span class="text-muted small">Monday &ndash; Friday, 8:00 AM &ndash; 5:00 PM</span></div></div><div class="map-wrap mt-4"><img src="{{ asset('assets/images/campus-gate.jpg') }}" class="w-100" style="object-fit:cover; height:250px;" alt="SUMAS campus gate"></div>
      </div>
      <div class="col-lg-7">
        <div class="glass-card p-4 p-md-5" style="background:#fff;">
          <h5 class="mb-4">Send Us a Message</h5>
          <form id="contactForm" class="needs-validation" novalidate method="POST" action="{{ route('contact.store') }}">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small fw-600">Full Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. Chidera Nwosu">
                <div class="invalid-feedback">{{ $errors->first('name') ?: 'Please enter your name.' }}</div>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-600">Matric Number</label>
                <input type="text" name="matric_number" class="form-control" value="{{ old('matric_number') }}" placeholder="e.g. SUMAS/22/1045">
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-600">Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="you@example.com">
                <div class="invalid-feedback">{{ $errors->first('email') ?: 'A valid email is required.' }}</div>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-600">Subject</label>
                <select name="subject" class="form-select @error('subject') is-invalid @enderror" required>
                  <option value="" {{ old('subject') ? '' : 'selected' }} disabled>Choose a topic</option>
                  @foreach(['Application Enquiry', 'Allocation Status', 'Maintenance Request', 'General Enquiry'] as $subject)
                    <option {{ old('subject') === $subject ? 'selected' : '' }}>{{ $subject }}</option>
                  @endforeach
                </select>
                <div class="invalid-feedback">Please choose a subject.</div>
              </div>
              <div class="col-12">
                <label class="form-label small fw-600">Message</label>
                <textarea name="message" class="form-control @error('message') is-invalid @enderror" rows="5" required placeholder="Tell us how we can help...">{{ old('message') }}</textarea>
                <div class="invalid-feedback">{{ $errors->first('message') ?: 'Please enter a message.' }}</div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-sumas-primary px-4"><i class="fa-solid fa-paper-plane me-2"></i>Send Message</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
