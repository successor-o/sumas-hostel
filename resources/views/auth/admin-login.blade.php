@extends('layouts.auth')
@section('title', 'Admin Login')

@section('content')
<div class="auth-page">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-9">
        <div class="auth-card">
          <div class="row g-0">
            <div class="col-lg-5 auth-side">
              <a href="{{ route('home') }}" class="d-inline-flex align-items-center gap-2 mb-4 text-decoration-none">
                <img src="{{ asset('assets/images/sumas-logo.png') }}" style="height:38px;" alt="SUMAS logo">
                <span class="text-white fw-600">SUMAS Hostels</span>
              </a>
              <div class="crest-badge gold mb-3"><i class="fa-solid fa-user-shield"></i></div>
              <h3 class="text-white mb-3">Hostel Office Console.</h3>
              <p class="text-white-50">Sign in to manage students, hostels, rooms, applications, and allocations across all SUMAS hostel blocks.</p>
              <ul class="list-unstyled mt-4">
                <li class="d-flex gap-2 mb-3 text-white-50 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i>Review and approve applications</li>
                <li class="d-flex gap-2 mb-3 text-white-50 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i>Monitor live occupancy</li>
                <li class="d-flex gap-2 mb-3 text-white-50 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i>Generate hostel reports</li>
              </ul>
              <img src="{{ asset('assets/images/campus-building.jpg') }}" class="rounded-4 mt-3" style="height:130px;object-fit:cover;width:100%;opacity:0.85;" alt="SUMAS campus building">
            </div>
            <div class="col-lg-7">
              <div class="p-4 p-md-5">
                <div class="d-flex gap-2 mb-4"><span class="badge-soft-gold">Administrator Access</span></div>
                <h4 class="mb-1">Log in to the admin console</h4>
                <p class="text-muted small mb-4">Enter your staff email and password to continue.</p>

                @if(session('status'))
                  <div class="alert alert-success small">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.login.attempt') }}" class="needs-validation" novalidate>
                  @csrf
                  <div class="mb-3">
                    <label class="form-label small fw-600">Staff Email</label>
                    <div class="input-group">
                      <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user-shield text-muted"></i></span>
                      <input type="email" name="email" value="{{ old('email') }}" class="form-control border-start-0 @error('email') is-invalid @enderror" required placeholder="admin@sumas.edu.ng" style="border-radius:0 10px 10px 0;">
                      <div class="invalid-feedback">{{ $errors->first('email') ?: 'Please enter your staff email.' }}</div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label small fw-600">Password</label>
                    <div class="input-group">
                      <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                      <input type="password" id="adminLoginPassword" name="password" class="form-control border-start-0 border-end-0" required placeholder="Enter your password">
                      <button class="btn btn-outline-secondary border-start-0 pw-toggle" type="button" data-target="#adminLoginPassword" style="border-radius:0 10px 10px 0;"><i class="fa-solid fa-eye"></i></button>
                      <div class="invalid-feedback">Please enter your password.</div>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="remember" id="adminRememberMe"><label class="form-check-label small" for="adminRememberMe">Remember me</label></div>
                    <a href="{{ route('admin.password.request') }}" class="small text-brown fw-600">Forgot Password?</a>
                  </div>
                  <button type="submit" class="btn btn-sumas-primary w-100 py-2 mb-3">Log In to Console <i class="fa-solid fa-arrow-right ms-2"></i></button>
                </form>
                <hr class="my-4">
                <p class="text-center small text-muted mb-2">Not a hostel administrator?</p>
                <a href="{{ route('login') }}" class="btn btn-sumas-outline w-100 btn-sm"><i class="fa-solid fa-user-graduate me-2"></i>Go to Student Login</a>
                <div class="alert alert-light border mt-4 small mb-0">
                  <strong>Demo credentials:</strong><br>
                  admin@sumas.edu.ng / password
                </div>
              </div>
            </div>
          </div>
        </div>
        <p class="text-center small text-muted mt-4">&copy; {{ date('Y') }} {{ config('sumas.institution_name') }} &middot; Hostel Allocation &amp; Management System</p>
      </div>
    </div>
  </div>
</div>
@endsection
