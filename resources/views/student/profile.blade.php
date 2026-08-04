@extends('layouts.dashboard')
@section('title', 'My Profile')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('student.dashboard') }}">Student Portal</a> / <span class="active">Profile</span></nav>
    <h2>My Profile</h2>
    <p class="text-muted small mb-0">View and update your personal information.</p>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="panel text-center">
      @if($student->avatar)
        <img src="{{ asset('storage/'.$student->avatar) }}" class="rounded-circle mx-auto mb-3" style="width:96px;height:96px;object-fit:cover;" alt="{{ $student->name }}">
      @else
        <div class="table-avatar mx-auto mb-3" style="width:96px;height:96px;font-size:2rem;">{{ $student->initials() }}</div>
      @endif
      <h5 class="mb-0">{{ $student->name }}</h5>
      <span class="text-muted small">{{ $student->matric_number }}</span>
      <div class="mt-2"><span class="badge-status {{ $student->activeAllocation ? 'active' : 'pending' }}">{{ $student->activeAllocation ? 'Housed' : 'Not Housed' }}</span></div>
      <hr>
      <div class="text-start small">
        <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Faculty</span><strong>{{ $student->faculty }}</strong></div>
        <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Level</span><strong>{{ $student->level }}</strong></div>
        <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Gender</span><strong>{{ $student->gender }}</strong></div>
        <div class="d-flex justify-content-between py-2"><span class="text-muted">Hostel</span><strong>{{ $student->activeAllocation ? $student->activeAllocation->hostel->name.', '.$student->activeAllocation->room->room_number : 'None' }}</strong></div>
      </div>
      <button class="btn btn-sumas-outline w-100 mt-3" data-bs-toggle="modal" data-bs-target="#uploadPicModal"><i class="fa-solid fa-camera me-2"></i>Change Photo</button>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="panel mb-3">
      <div class="panel-head"><h5>Personal Information</h5></div>
      <form method="POST" action="{{ route('student.profile.update') }}" class="row g-3">
        @csrf @method('PUT')
        <div class="col-md-6"><label class="form-label small fw-600">Full Name</label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $student->name) }}"><div class="invalid-feedback">{{ $errors->first('name') }}</div></div>
        <div class="col-md-6"><label class="form-label small fw-600">Matric Number</label><input type="text" class="form-control" value="{{ $student->matric_number }}" disabled></div>
        <div class="col-md-6"><label class="form-label small fw-600">School Email</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $student->email) }}"><div class="invalid-feedback">{{ $errors->first('email') }}</div></div>
        <div class="col-md-6"><label class="form-label small fw-600">Phone Number</label><input type="tel" name="phone" class="form-control" value="{{ old('phone', $student->phone) }}"></div>
        <div class="col-md-6"><label class="form-label small fw-600">Faculty</label>
          <select name="faculty" class="form-select">
            @foreach(['Clinical Medicine', 'Basic Medical Sciences', 'Allied Health Sciences', 'Applied Sciences'] as $f)
              <option {{ $student->faculty === $f ? 'selected' : '' }}>{{ $f }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6"><label class="form-label small fw-600">Level</label>
          <select name="level" class="form-select">
            @foreach(['100 Level', '200 Level', '300 Level', '400 Level', '500 Level', 'Postgraduate'] as $lvl)
              <option {{ $student->level === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6"><label class="form-label small fw-600">Emergency Contact Name</label><input type="text" name="emergency_contact_name" class="form-control" value="{{ old('emergency_contact_name', $student->emergency_contact_name) }}"></div>
        <div class="col-md-6"><label class="form-label small fw-600">Emergency Contact Phone</label><input type="tel" name="emergency_contact_phone" class="form-control" value="{{ old('emergency_contact_phone', $student->emergency_contact_phone) }}"></div>
        <div class="col-12"><button type="submit" class="btn btn-sumas-primary">Save Changes</button></div>
      </form>
    </div>
    <div class="panel">
      <div class="panel-head"><h5>Change Password</h5></div>
      <form method="POST" action="{{ route('student.profile.password') }}" class="row g-3">
        @csrf @method('PUT')
        <div class="col-md-4"><label class="form-label small fw-600">Current Password</label><input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="••••••••"><div class="invalid-feedback">{{ $errors->first('current_password') }}</div></div>
        <div class="col-md-4"><label class="form-label small fw-600">New Password</label><input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"><div class="invalid-feedback">{{ $errors->first('password') }}</div></div>
        <div class="col-md-4"><label class="form-label small fw-600">Confirm Password</label><input type="password" name="password_confirmation" class="form-control" placeholder="••••••••"></div>
        <div class="col-12"><button type="submit" class="btn btn-sumas-primary">Update Password</button></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="uploadPicModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('student.profile.photo') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Update Profile Photo</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body text-center">
          <div class="table-avatar mx-auto mb-3" style="width:100px;height:100px;font-size:2rem;">{{ $student->initials() }}</div>
          <input type="file" name="avatar" class="form-control" accept="image/*" required>
          <p class="text-muted small mt-2 mb-0">JPG or PNG. Max size 2MB.</p>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sumas-primary">Upload</button></div>
      </form>
    </div>
  </div>
</div>
@endsection
