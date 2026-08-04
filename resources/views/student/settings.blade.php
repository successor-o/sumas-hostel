@extends('layouts.dashboard')
@section('title', 'Settings')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('student.dashboard') }}">Student Portal</a> / <span class="active">Settings</span></nav>
    <h2>Settings</h2>
    <p class="text-muted small mb-0">Manage your account preferences.</p>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-3">
    <div class="panel p-2">
      <div class="list-group list-group-flush">
        <a href="#notifPrefs" class="list-group-item list-group-item-action border-0 rounded-3 mb-1 active" data-bs-toggle="list"><i class="fa-solid fa-bell me-2"></i>Notifications</a>
        <a href="#privacyPrefs" class="list-group-item list-group-item-action border-0 rounded-3 mb-1" data-bs-toggle="list"><i class="fa-solid fa-lock me-2"></i>Privacy</a>
        <a href="#accountPrefs" class="list-group-item list-group-item-action border-0 rounded-3" data-bs-toggle="list"><i class="fa-solid fa-user-gear me-2"></i>Account</a>
      </div>
    </div>
  </div>
  <div class="col-lg-9">
    <form method="POST" action="{{ route('student.settings.update') }}">
      @csrf @method('PUT')
      <div class="tab-content">
        <div class="tab-pane fade show active" id="notifPrefs">
          <div class="panel">
            <div class="panel-head"><h5>Notification Preferences</h5></div>
            @php $prefs = $student->settings ?? []; @endphp
            <div class="d-flex align-items-center justify-content-between border rounded-3 p-3 mb-2"><div><strong class="small d-block">Email Notifications</strong><span class="text-muted small">Get emailed about application and allocation updates.</span></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="email_notifications" value="1" {{ !empty($prefs['email_notifications']) ? 'checked' : '' }}></div></div>
            <div class="d-flex align-items-center justify-content-between border rounded-3 p-3 mb-2"><div><strong class="small d-block">SMS Alerts</strong><span class="text-muted small">Receive a text message for urgent hostel notices.</span></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="sms_alerts" value="1" {{ !empty($prefs['sms_alerts']) ? 'checked' : '' }}></div></div>
            <div class="d-flex align-items-center justify-content-between border rounded-3 p-3"><div><strong class="small d-block">Maintenance Alerts</strong><span class="text-muted small">Be notified of scheduled water/power maintenance.</span></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="maintenance_alerts" value="1" {{ !empty($prefs['maintenance_alerts']) ? 'checked' : '' }}></div></div>
          </div>
        </div>
        <div class="tab-pane fade" id="privacyPrefs">
          <div class="panel">
            <div class="panel-head"><h5>Privacy</h5></div>
            <div class="d-flex align-items-center justify-content-between border rounded-3 p-3 mb-2"><div><strong class="small d-block">Share Contact with Roommates</strong><span class="text-muted small">Allow roommates to see your phone number.</span></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div></div>
            <div class="d-flex align-items-center justify-content-between border rounded-3 p-3"><div><strong class="small d-block">Visible in Hostel Directory</strong><span class="text-muted small">Show your name in the hostel warden's resident list.</span></div><div class="form-check form-switch"><input class="form-check-input" type="checkbox" checked></div></div>
          </div>
        </div>
        <div class="tab-pane fade" id="accountPrefs">
          <div class="panel">
            <div class="panel-head"><h5>Account</h5></div>
            <p class="text-muted small">Update your login email and other account details from the <a href="{{ route('student.profile') }}" class="text-brown fw-600">Profile</a> page.</p>
            <hr>
            <div class="d-flex align-items-center justify-content-between border rounded-3 p-3" style="border-color:var(--sumas-danger) !important;">
              <div><strong class="small d-block text-danger">Deactivate Account</strong><span class="text-muted small">Temporarily disable your student portal access.</span></div>
              <button type="button" class="btn btn-outline-danger btn-sm" disabled>Deactivate</button>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-3"><button type="submit" class="btn btn-sumas-primary">Save Changes</button></div>
    </form>
  </div>
</div>
@endsection
