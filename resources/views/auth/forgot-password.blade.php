@extends('layouts.auth')
@section('title', 'Forgot Password')

@section('content')
@php
  $isAdmin = $guard === 'admin';
  $submitRoute = $isAdmin ? route('admin.password.email') : route('password.email');
  $backRoute = $isAdmin ? route('admin.login') : route('login');
@endphp
<div class="auth-page">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="text-center mb-4"><a href="{{ route('home') }}"><img src="{{ asset('assets/images/sumas-logo.png') }}" style="height:52px;" alt="SUMAS logo"></a></div>
        <div class="auth-card p-4 p-md-5">
          @if(session('status'))
            <div class="text-center">
              <div class="crest-badge gold mb-4 mx-auto"><i class="fa-solid fa-envelope-circle-check"></i></div>
              <h4 class="mb-2">Check your inbox</h4>
              <p class="text-muted small mb-4">{{ session('status') }} In this demo build (no mail server configured), the reset link is written to <code>storage/logs/laravel.log</code> instead of being emailed.</p>
              <a href="{{ $backRoute }}" class="btn btn-sumas-outline w-100">Return to Login</a>
            </div>
          @else
            <div class="crest-badge gold mb-4"><i class="fa-solid fa-key"></i></div>
            <h4 class="mb-1">Forgot your password?</h4>
            <p class="text-muted small mb-4">Enter the email linked to your {{ $isAdmin ? 'admin' : 'student' }} account and we'll generate a reset link.</p>
            <form class="needs-validation" novalidate method="POST" action="{{ $submitRoute }}">
              @csrf
              <div class="mb-3">
                <label class="form-label small fw-600">{{ $isAdmin ? 'Staff' : 'School' }} Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required placeholder="you@sumas.edu.ng">
                <div class="invalid-feedback">{{ $errors->first('email') ?: 'Please enter a valid email address.' }}</div>
              </div>
              <button type="submit" class="btn btn-sumas-primary w-100 py-2 mb-3">Send Reset Link <i class="fa-solid fa-paper-plane ms-2"></i></button>
            </form>
            <p class="text-center small text-muted mb-0"><a href="{{ $backRoute }}" class="text-brown fw-600"><i class="fa-solid fa-arrow-left me-1"></i> Back to Login</a></p>
          @endif
        </div>
        <p class="text-center small text-muted mt-4">&copy; {{ date('Y') }} {{ config('sumas.institution_name') }} &middot; Hostel Allocation &amp; Management System</p>
      </div>
    </div>
  </div>
</div>
@endsection
