@extends('layouts.dashboard')
@section('title', 'My Profile')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Profile</span></nav>
    <h2>My Profile</h2>
    <p class="text-muted small mb-0">Manage your administrator account.</p>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="panel text-center">
      @if($admin->avatar)
        <img src="{{ asset('storage/'.$admin->avatar) }}" class="rounded-circle mx-auto mb-3" style="width:96px;height:96px;object-fit:cover;" alt="{{ $admin->name }}">
      @else
        <div class="table-avatar mx-auto mb-3" style="width:96px;height:96px;font-size:2rem;">{{ $admin->initials() }}</div>
      @endif
      <h5 class="mb-0">{{ $admin->name }}</h5>
      <span class="text-muted small">{{ $admin->role }}</span>
      <div class="mt-2"><span class="badge-soft-gold">Hostel Office</span></div>
      <hr>
      <div class="text-start small">
        <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Email</span><strong>{{ $admin->email }}</strong></div>
        <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Phone</span><strong>{{ $admin->phone ?: '—' }}</strong></div>
        <div class="d-flex justify-content-between py-2 border-bottom"><span class="text-muted">Staff ID</span><strong>{{ $admin->staff_id }}</strong></div>
        <div class="d-flex justify-content-between py-2"><span class="text-muted">Joined</span><strong>{{ $admin->created_at->format('M Y') }}</strong></div>
      </div>
      <button class="btn btn-sumas-outline w-100 mt-3" data-bs-toggle="modal" data-bs-target="#uploadPicModal"><i class="fa-solid fa-camera me-2"></i>Change Photo</button>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="panel mb-3">
      <div class="panel-head"><h5>Account Information</h5></div>
      <form method="POST" action="{{ route('admin.profile.update') }}" class="row g-3">
        @csrf @method('PUT')
        <div class="col-md-6"><label class="form-label small fw-600">Full Name</label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}"><div class="invalid-feedback">{{ $errors->first('name') }}</div></div>
        <div class="col-md-6"><label class="form-label small fw-600">Email</label><input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}"><div class="invalid-feedback">{{ $errors->first('email') }}</div></div>
        <div class="col-md-6"><label class="form-label small fw-600">Phone</label><input type="tel" name="phone" class="form-control" value="{{ old('phone', $admin->phone) }}"></div>
        <div class="col-md-6"><label class="form-label small fw-600">Role</label><input type="text" class="form-control" value="{{ $admin->role }}" disabled></div>
        <div class="col-12"><button type="submit" class="btn btn-sumas-primary">Save Changes</button></div>
      </form>
    </div>
    <div class="panel">
      <div class="panel-head"><h5>Change Password</h5></div>
      <form method="POST" action="{{ route('admin.profile.password') }}" class="row g-3">
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
      <form method="POST" action="{{ route('admin.profile.photo') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Update Profile Photo</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body text-center">
          <div class="table-avatar mx-auto mb-3" style="width:100px;height:100px;font-size:2rem;">{{ $admin->initials() }}</div>
          <input type="file" name="avatar" class="form-control" accept="image/*" required>
          <p class="text-muted small mt-2 mb-0">JPG or PNG. Max size 2MB.</p>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sumas-primary">Upload</button></div>
      </form>
    </div>
  </div>
</div>
@endsection
