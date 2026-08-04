@extends('layouts.auth')
@section('title', 'Student Registration')

@section('content')
<div class="auth-page">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10">
        <div class="auth-card">
          <div class="row g-0">
            <div class="col-lg-5 auth-side">
              <a href="{{ route('home') }}" class="d-inline-flex align-items-center gap-2 mb-4 text-decoration-none">
                <img src="{{ asset('assets/images/sumas-logo.png') }}" style="height:38px;" alt="SUMAS logo">
                <span class="text-white fw-600">SUMAS Hostels</span>
              </a>
              <h3 class="text-white mb-3">Create your student account.</h3>
              <p class="text-white-50">Registration takes less than five minutes and gives you access to hostel applications for every session.</p>
              <div class="d-flex gap-3 mt-4">
                <div class="crest-badge sm gold"><i class="fa-solid fa-house-user"></i></div>
                <div><strong class="d-block text-white small">One account, every session</strong><span class="text-white-50 small">Apply again next year without re-registering.</span></div>
              </div>
              <img src="{{ asset('assets/images/female-hostel.jpg') }}" class="rounded-4 mt-4" style="height:150px;object-fit:cover;width:100%;opacity:0.85;" alt="Female hostel block">
            </div>
            <div class="col-lg-7">
              <div class="p-4 p-md-5">
                <h4 class="mb-1">Register as a new student</h4>
                <p class="text-muted small mb-4">All fields marked * are required.</p>
                <form method="POST" action="{{ route('register.store') }}" class="needs-validation" novalidate>
                  @csrf
                  <div class="row g-3">
                    <div class="col-md-6">
                      <label class="form-label small fw-600">Full Name *</label>
                      <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required placeholder="e.g. Chidera Nwosu">
                      <div class="invalid-feedback">{{ $errors->first('name') ?: 'Please enter your full name.' }}</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-600">Matric Number *</label>
                      <input type="text" name="matric_number" value="{{ old('matric_number') }}" class="form-control @error('matric_number') is-invalid @enderror" required placeholder="SUMAS/22/1045">
                      <div class="invalid-feedback">{{ $errors->first('matric_number') ?: 'Please enter your matric number.' }}</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-600">School Email *</label>
                      <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required placeholder="you@sumas.edu.ng">
                      <div class="invalid-feedback">{{ $errors->first('email') ?: 'A valid school email is required.' }}</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-600">Phone Number *</label>
                      <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" required placeholder="+234 800 000 0000">
                      <div class="invalid-feedback">Please enter a phone number.</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-600">Faculty *</label>
                      <select name="faculty" class="form-select @error('faculty') is-invalid @enderror" required>
                        <option value="" {{ old('faculty') ? '' : 'selected' }} disabled>Select faculty</option>
                        @foreach(['Faculty of Clinical Medicine', 'Faculty of Basic Medical Sciences', 'Faculty of Allied Health Sciences', 'Faculty of Applied Sciences'] as $f)
                          <option {{ old('faculty') === $f ? 'selected' : '' }}>{{ $f }}</option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback">Please select a faculty.</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-600">Level *</label>
                      <select name="level" class="form-select @error('level') is-invalid @enderror" required>
                        <option value="" {{ old('level') ? '' : 'selected' }} disabled>Select level</option>
                        @foreach(['100 Level', '200 Level', '300 Level', '400 Level', '500 Level'] as $lvl)
                          <option {{ old('level') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                        @endforeach
                      </select>
                      <div class="invalid-feedback">Please select your level.</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-600">Gender *</label>
                      <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                        <option value="" {{ old('gender') ? '' : 'selected' }} disabled>Select gender</option>
                        <option {{ old('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option {{ old('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                      </select>
                      <div class="invalid-feedback">Please select your gender.</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-600">Password *</label>
                      <input type="password" id="regPassword" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="6" placeholder="Create a password">
                      <div class="pw-strength"><div class="bar"></div></div>
                      <div class="invalid-feedback">{{ $errors->first('password') ?: 'Password must be at least 6 characters.' }}</div>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label small fw-600">Confirm Password *</label>
                      <input type="password" name="password_confirmation" class="form-control" required minlength="6" placeholder="Repeat your password">
                    </div>
                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="terms" id="agreeTerms" required>
                        <label class="form-check-label small" for="agreeTerms">I agree to the hostel rules and the University's terms of accommodation.</label>
                        <div class="invalid-feedback">You must agree before continuing.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button type="submit" class="btn btn-sumas-primary w-100 py-2">Create Account <i class="fa-solid fa-user-plus ms-2"></i></button>
                    </div>
                  </div>
                </form>
                <p class="text-center small text-muted mt-4 mb-0">Already have an account? <a href="{{ route('login') }}" class="text-brown fw-600">Log in</a></p>
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
