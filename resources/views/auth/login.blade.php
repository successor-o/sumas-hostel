@extends('layouts.auth')
@section('title', 'Student Login')

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
              <h3 class="text-white mb-3">Welcome back.</h3>
              <p class="text-white-50">Log in to check your application status, view your allocation, and manage your hostel profile.</p>
              <ul class="list-unstyled mt-4">
                <li class="d-flex gap-2 mb-3 text-white-50 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i>Track your application in real time</li>
                <li class="d-flex gap-2 mb-3 text-white-50 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i>Download your allocation slip</li>
                <li class="d-flex gap-2 mb-3 text-white-50 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i>Get instant hostel notifications</li>
              </ul>
              <img src="{{ asset('assets/images/campus-gate.jpg') }}" class="rounded-4 mt-3" style="height:130px;object-fit:cover;width:100%;opacity:0.85;" alt="SUMAS campus gate">
            </div>
            <div class="col-lg-7">
              <div class="p-4 p-md-5">
                <div class="d-flex gap-2 mb-4"><span class="badge-soft-gold">Student Portal</span></div>
                <h4 class="mb-1">Log in to your account</h4>
                <p class="text-muted small mb-4">Enter your matric number and password to continue.</p>

                @if(session('status'))
                  <div class="alert alert-success small">{{ session('status') }}</div>
                @endif
                @if($errors->any() && !$errors->has('login'))
                  <div class="alert alert-danger small">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login.attempt') }}" class="needs-validation" novalidate>
                  @csrf
                  <div class="mb-3">
                    <label class="form-label small fw-600">Matric Number or Email</label>
                    <div class="input-group">
                      <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-user text-muted"></i></span>
                      <input type="text" name="login" value="{{ old('login') }}" class="form-control border-start-0 @error('login') is-invalid @enderror" required placeholder="SUMAS/22/1045" style="border-radius:0 10px 10px 0;">
                      <div class="invalid-feedback">{{ $errors->first('login') ?: 'Please enter your matric number or email.' }}</div>
                    </div>
                  </div>
                  <div class="mb-3">
                    <label class="form-label small fw-600">Password</label>
                    <div class="input-group">
                      <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-lock text-muted"></i></span>
                      <input type="password" id="loginPassword" name="password" class="form-control border-start-0 border-end-0" required placeholder="Enter your password">
                      <button class="btn btn-outline-secondary border-start-0 pw-toggle" type="button" data-target="#loginPassword" style="border-radius:0 10px 10px 0;"><i class="fa-solid fa-eye"></i></button>
                      <div class="invalid-feedback">Please enter your password.</div>
                    </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="remember" id="rememberMe"><label class="form-check-label small" for="rememberMe">Remember me</label></div>
                    <a href="{{ route('password.request') }}" class="small text-brown fw-600">Forgot Password?</a>
                  </div>
                  <button type="submit" class="btn btn-sumas-primary w-100 py-2 mb-3">Log In <i class="fa-solid fa-arrow-right ms-2"></i></button>
                  <p class="text-center small text-muted mb-0">Don't have an account? <a href="{{ route('register') }}" class="text-brown fw-600">Register here</a></p>
                </form>
{{--                <hr class="my-4">--}}
{{--                <p class="text-center small text-muted mb-2">Are you a hostel administrator?</p>--}}
{{--                <a href="{{ route('admin.login') }}" class="btn btn-sumas-outline w-100 btn-sm"><i class="fa-solid fa-user-shield me-2"></i>Go to Admin Login</a>--}}
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
