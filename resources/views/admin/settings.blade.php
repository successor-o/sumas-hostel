@extends('layouts.dashboard')
@section('title', 'Settings')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Settings</span></nav>
    <h2>System Settings</h2>
    <p class="text-muted small mb-0">Configure how the hostel system behaves.</p>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-3">
    <div class="panel p-2">
      <div class="list-group list-group-flush">
        <a href="#generalSettings" class="list-group-item list-group-item-action border-0 rounded-3 mb-1 active" data-bs-toggle="list"><i class="fa-solid fa-sliders me-2"></i>General</a>
        <a href="#sessionSettings" class="list-group-item list-group-item-action border-0 rounded-3 mb-1" data-bs-toggle="list"><i class="fa-solid fa-calendar me-2"></i>Application Session</a>
        <a href="#notifSettings" class="list-group-item list-group-item-action border-0 rounded-3 mb-1" data-bs-toggle="list"><i class="fa-solid fa-bell me-2"></i>Notifications</a>
        <a href="#securitySettings" class="list-group-item list-group-item-action border-0 rounded-3" data-bs-toggle="list"><i class="fa-solid fa-shield-halved me-2"></i>Security</a>
      </div>
    </div>
  </div>
  <div class="col-lg-9">
    <div class="tab-content">
      <div class="tab-pane fade show active" id="generalSettings">
        <div class="panel">
          <div class="panel-head"><h5>General Settings</h5></div>
          <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-3">
            @csrf @method('PUT')
            <div class="col-md-6"><label class="form-label small fw-600">Institution Name</label><input type="text" name="institution_name" class="form-control" value="{{ config('sumas.institution_name') }}"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Support Email</label><input type="email" name="support_email" class="form-control" value="{{ config('sumas.support_email') }}"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Default Currency</label><select class="form-select"><option>NGN (₦)</option><option>USD ($)</option></select></div>
            <div class="col-md-6"><label class="form-label small fw-600">Timezone</label><select class="form-select"><option selected>Africa/Lagos (WAT)</option></select></div>
            <div class="col-12"><button type="submit" class="btn btn-sumas-primary">Save Changes</button></div>
          </form>
        </div>
      </div>
      <div class="tab-pane fade" id="sessionSettings">
        <div class="panel">
          <div class="panel-head"><h5>Application Session</h5></div>
          <form method="POST" action="{{ route('admin.settings.update') }}" class="row g-3">
            @csrf @method('PUT')
            <div class="col-md-6"><label class="form-label small fw-600">Current Academic Session</label><input type="text" name="academic_session" class="form-control" value="{{ config('sumas.session') }}" placeholder="e.g. 2026/2027"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Application Window</label><input type="text" class="form-control" value="Jul 1 – Aug 31, {{ date('Y') }}" disabled></div>
            <div class="col-12 d-flex align-items-center justify-content-between border rounded-3 p-3">
              <div><strong class="small d-block">Accept New Applications</strong><span class="text-muted small">Toggle off to freeze new hostel applications.</span></div>
              <div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div>
            </div>
            <div class="col-12"><button type="submit" class="btn btn-sumas-primary">Save Session</button></div>
          </form>
          <p class="text-muted small mt-3 mb-0">The academic session is stored in the database and used to tag new applications and allocations.</p>
        </div>
      </div>
      <div class="tab-pane fade" id="notifSettings">
        <div class="panel">
          <div class="panel-head"><h5>Notification Preferences</h5></div>
          <div class="d-flex align-items-center justify-content-between border rounded-3 p-3 mb-2"><div><strong class="small d-block">Email Alerts</strong><span class="text-muted small">Get emailed when an application needs review.</span></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div></div>
          <div class="d-flex align-items-center justify-content-between border rounded-3 p-3 mb-2"><div><strong class="small d-block">Occupancy Alerts</strong><span class="text-muted small">Notify when a block reaches 90% capacity.</span></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div></div>
          <div class="d-flex align-items-center justify-content-between border rounded-3 p-3"><div><strong class="small d-block">Weekly Summary</strong><span class="text-muted small">Receive a weekly report every Monday.</span></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox"></div></div>
        </div>
      </div>
      <div class="tab-pane fade" id="securitySettings">
        <div class="panel">
          <div class="panel-head"><h5>Security</h5></div>
          <p class="text-muted small">Update your password from the <a href="{{ route('admin.profile') }}" class="text-brown fw-600">Profile</a> page.</p>
          <div class="d-flex align-items-center justify-content-between border rounded-3 p-3">
            <div><strong class="small d-block">Two-Factor Authentication</strong><span class="text-muted small">Add an extra layer of protection to your admin login.</span></div>
            <div class="form-check form-switch"><input class="form-check-input" type="checkbox" disabled></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
