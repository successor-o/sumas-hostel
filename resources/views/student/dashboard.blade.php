@extends('layouts.dashboard')
@section('title', 'My Dashboard')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('student.dashboard') }}">Student Portal</a> / <span class="active">Dashboard</span></nav>
    <h2>Welcome back, {{ explode(' ', $student->name)[0] }} 👋</h2>
    <p id="liveClock"></p>
  </div>
  <a href="{{ route('student.application') }}" class="btn btn-sumas-primary btn-sm"><i class="fa-solid fa-file-lines me-2"></i>{{ $latestApplication ? 'Update Application' : 'New Application' }}</a>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-6 col-lg-3">
    <div class="stat-card"><div class="top"><div class="icon green"><i class="fa-solid fa-house-user"></i></div></div>
      <h3 style="font-size:1.2rem;">{{ $allocation->hostel->name ?? 'Not Allocated' }}</h3><div class="label">Current Hostel</div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="stat-card"><div class="top"><div class="icon gold"><i class="fa-solid fa-door-closed"></i></div></div>
      <h3 style="font-size:1.2rem;">{{ $allocation ? 'Room '.$allocation->room->room_number : '—' }}</h3><div class="label">Room / Bed {{ $allocation->bed_number ?? '' }}</div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="stat-card"><div class="top"><div class="icon blue"><i class="fa-solid fa-file-lines"></i></div></div>
      <h3 style="font-size:1.2rem;">{{ $latestApplication ? ucfirst($latestApplication->status) : 'None' }}</h3><div class="label">Application Status</div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="stat-card"><div class="top"><div class="icon brown"><i class="fa-solid fa-calendar-check"></i></div></div>
      <h3 style="font-size:1.2rem;">{{ config('sumas.session') }}</h3><div class="label">Academic Session</div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-7">
    <div class="panel h-100">
      <div class="panel-head">
        <div><h5>My Allocation Summary</h5><span class="sub">Current hostel accommodation</span></div>
        <a href="{{ route('student.allocation') }}" class="small text-brown fw-600">View Full Details</a>
      </div>
      @if($allocation)
      <div class="row g-3 align-items-center">
        <div class="col-md-5"><img src="{{ $allocation->hostel->imageUrl() }}" class="img-fluid rounded-4" style="height:180px;object-fit:cover;width:100%;" alt="{{ $allocation->hostel->name }}"></div>
        <div class="col-md-7">
          <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Hostel Block</span><strong>{{ $allocation->hostel->name }}</strong></div>
          <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Room Number</span><strong>{{ $allocation->room->room_number }}</strong></div>
          <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Bed Number</span><strong>Bed {{ $allocation->bed_number }}</strong></div>
          <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Date Allocated</span><strong>{{ $allocation->allocated_at?->format('F j, Y') }}</strong></div>
          <div class="d-flex justify-content-between py-2 small"><span class="text-muted">Status</span><span class="badge-status active">Active</span></div>
          <a href="{{ route('student.allocation') }}" class="btn btn-sumas-primary btn-sm w-100 mt-2"><i class="fa-solid fa-print me-2"></i>View / Print Slip</a>
        </div>
      </div>
      @else
      <div class="text-center py-4">
        <i class="fa-solid fa-house-circle-xmark fs-1 text-muted mb-3 d-block"></i>
        <p class="text-muted mb-3">You have not been allocated a room yet.</p>
        <a href="{{ route('student.application') }}" class="btn btn-sumas-primary btn-sm">Submit an Application</a>
      </div>
      @endif
    </div>
  </div>
  <div class="col-lg-5">
    <div class="panel h-100">
      <div class="panel-head"><h5>Application Timeline</h5></div>
      @forelse($timeline as $app)
        <div class="activity-item">
          <div class="dot" style="background:var(--sumas-{{ $app->status === 'approved' ? 'success' : ($app->status === 'rejected' ? 'danger' : 'warning') }}-bg);color:var(--sumas-{{ $app->status === 'approved' ? 'success' : ($app->status === 'rejected' ? 'danger' : 'warning') }});">
            <i class="fa-solid {{ $app->status === 'approved' ? 'fa-circle-check' : ($app->status === 'rejected' ? 'fa-circle-xmark' : 'fa-hourglass-half') }}"></i>
          </div>
          <div><p><strong>Application {{ ucfirst($app->status) }}</strong> &mdash; {{ $app->hostel->name }}</p><small>{{ $app->updated_at->format('F j, Y') }}</small></div>
        </div>
      @empty
        <p class="text-muted small">No application history yet.</p>
      @endforelse
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-4">
    <div class="panel">
      <div class="panel-head"><h5>Quick Actions</h5></div>
      <div class="d-grid gap-2">
        <a href="{{ route('student.application') }}" class="btn btn-sumas-outline text-start"><i class="fa-solid fa-file-lines me-2"></i>Submit / Update Application</a>
        <a href="{{ route('student.allocation') }}" class="btn btn-sumas-outline text-start"><i class="fa-solid fa-print me-2"></i>Print Allocation Slip</a>
        <a href="{{ route('student.rules') }}" class="btn btn-sumas-outline text-start"><i class="fa-solid fa-book me-2"></i>Read Hostel Rules</a>
        <a href="{{ route('contact') }}" class="btn btn-sumas-outline text-start"><i class="fa-solid fa-headset me-2"></i>Contact Hostel Office</a>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="panel">
      <div class="panel-head"><h5>Recent Notifications</h5><a href="{{ route('student.notifications') }}" class="small text-brown fw-600">View all</a></div>
      @forelse($notifications as $n)
        <div class="notif-item {{ $n->isUnread() ? 'unread' : '' }}">
          <div class="icon" style="background:var(--sumas-{{ $n->type }}-bg);color:var(--sumas-{{ $n->type }});"><i class="fa-solid {{ $n->iconFor() }}"></i></div>
          <div><p class="mb-1 small"><strong>{{ $n->title }}</strong> {{ $n->message }}</p><small>{{ $n->created_at->diffForHumans() }}</small></div>
        </div>
      @empty
        <p class="text-muted small">You're all caught up.</p>
      @endforelse
    </div>
  </div>
</div>
@endsection
