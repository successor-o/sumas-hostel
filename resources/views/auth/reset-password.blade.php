@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
@php
  $isAdmin = $guard === 'admin';
  $submitRoute = $isAdmin ? route('admin.password.update') : route('password.update');
@endphp
<div class="auth-page">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="text-center mb-4"><a href="{{ route('home') }}"><img src="{{ asset('assets/images/sumas-logo.png') }}" style="height:52px;" alt="SUMAS logo"></a></div>
        <div class="auth-card p-4 p-md-5">
          <div class="crest-badge gold mb-4"><i class="fa-solid fa-lock-open"></i></div>
          <h4 class="mb-1">Choose a new password</h4>
          <p class="text-muted small mb-4">Make it at least 6 characters.</p>
          <form class="needs-validation" novalidate method="POST" action="{{ $submitRoute }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-3">
              <label class="form-label small fw-600">Email</label>
              <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control @error('email') is-invalid @enderror" required>
              <div class="invalid-feedback">{{ $errors->first('email') }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-600">New Password</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="6">
              <div class="invalid-feedback">{{ $errors->first('password') ?: 'Password must be at least 6 characters.' }}</div>
            </div>
            <div class="mb-4">
              <label class="form-label small fw-600">Confirm New Password</label>
              <input type="password" name="password_confirmation" class="form-control" required minlength="6">
            </div>
            <button type="submit" class="btn btn-sumas-primary w-100 py-2">Reset Password <i class="fa-solid fa-check ms-2"></i></button>
          </form>
        </div>
        <p class="text-center small text-muted mt-4">&copy; {{ date('Y') }} {{ config('sumas.institution_name') }} &middot; Hostel Allocation &amp; Management System</p>
      </div>
    </div>
  </div>
</div>
@endsection
