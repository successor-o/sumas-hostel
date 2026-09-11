@extends('layouts.dashboard')
@section('title', 'My Allocation')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('student.dashboard') }}">Student Portal</a> / <span class="active">My Allocation</span></nav>
    <h2>My Allocation</h2>
    <p class="text-muted small mb-0">Your current hostel accommodation details.</p>
  </div>
  @if($allocation)
  <div class="d-flex gap-2 no-print">
    <button class="btn btn-sumas-outline btn-sm" onclick="window.print()"><i class="fa-solid fa-print me-2"></i>Print Slip</button>
    <button class="btn btn-sumas-primary btn-sm" data-bs-toggle="modal" data-bs-target="#slipModal"><i class="fa-solid fa-file-lines me-2"></i>View Full Slip</button>
  </div>
  @endif
</div>

@if($allocation)
<div class="row g-3">
  <div class="col-lg-7">
    <div class="panel">
      <div class="panel-head"><h5>Allocation Details</h5><span class="badge-status active">Active</span></div>
      <div class="row g-3 align-items-center">
        <div class="col-md-5"><img src="{{ $allocation->hostel->imageUrl() }}" class="img-fluid rounded-4" style="height:200px;object-fit:cover;width:100%;" alt="{{ $allocation->hostel->name }}"></div>
        <div class="col-md-7">
          <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Hostel Block</span><strong>{{ $allocation->hostel->name }}</strong></div>
          <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Room Number</span><strong>{{ $allocation->room->room_number }}</strong></div>
          <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Bed Number</span><strong>Bed {{ $allocation->bed_number }}</strong></div>
          <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Floor</span><strong>{{ $allocation->room->floor }}</strong></div>
          <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Date Allocated</span><strong>{{ $allocation->allocated_at?->format('F j, Y') }}</strong></div>
          <div class="d-flex justify-content-between py-2 small"><span class="text-muted">Academic Session</span><strong>{{ $allocation->session }}</strong></div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="panel h-100">
      <div class="panel-head"><h5>Roommates</h5><span class="sub">{{ $roommates->count() }} other student(s) in Room {{ $allocation->room->room_number }}</span></div>
      @forelse($roommates as $mate)
        <div class="d-flex align-items-center gap-2 py-2 border-bottom"><div class="table-avatar">{{ $mate->initials() }}</div><div><div class="small fw-600">{{ $mate->name }}</div><div class="text-muted" style="font-size:0.72rem;">{{ $mate->level }} {{ $mate->faculty }}</div></div></div>
      @empty
        <p class="text-muted small">No roommates yet &mdash; you have this room to yourself for now.</p>
      @endforelse
    </div>
  </div>
</div>

<div class="modal fade" id="slipModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header no-print"><h5 class="modal-title">Official Allocation Slip</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <img src="{{ asset('assets/images/sumas-logo.png') }}" style="height:52px;" alt="SUMAS logo">
          <h5 class="mt-2 mb-0">{{ config('sumas.institution_name') }}</h5>
          <span class="text-muted small">Official Hostel Allocation Slip</span>
        </div>
        <hr>
        <div class="row g-3 small">
          <div class="col-6"><span class="text-muted d-block">Student Name</span><strong>{{ $student->name }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Matric Number</span><strong>{{ $student->matric_number }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Hostel Block</span><strong>{{ $allocation->hostel->name }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Room / Bed</span><strong>{{ $allocation->room->room_number }} / Bed {{ $allocation->bed_number }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Date Allocated</span><strong>{{ $allocation->allocated_at?->format('F j, Y') }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Academic Session</span><strong>{{ $allocation->session }}</strong></div>
        </div>
        <hr>
        <p class="small text-muted mb-0">This slip confirms official hostel accommodation for the student named above for the current academic session. Present this slip to the hostel warden upon check-in.</p>
      </div>
      <div class="modal-footer no-print"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-sumas-primary" onclick="window.print()"><i class="fa-solid fa-print me-2"></i>Print Slip</button></div>
    </div>
  </div>
</div>
@elseif($application && $application->status === 'approved')
<div class="panel">
  <div class="panel-head"><h5>Application Approved</h5><span class="badge-status approved">Approved</span></div>
  <div class="row g-3 align-items-center">
    <div class="col-md-5">
      <img src="{{ $application->hostel->imageUrl() }}" class="img-fluid rounded-4" style="height:200px;object-fit:cover;width:100%;" alt="{{ $application->hostel->name }}">
    </div>
    <div class="col-md-7">
      <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Hostel Applied For</span><strong>{{ $application->hostel->name }}</strong></div>
      <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Application Status</span><strong class="text-success">Approved</strong></div>
      <div class="d-flex justify-content-between border-bottom py-2 small"><span class="text-muted">Reviewed On</span><strong>{{ $application->reviewed_at?->format('F j, Y') }}</strong></div>
      <div class="d-flex justify-content-between py-2 small"><span class="text-muted">Academic Session</span><strong>{{ $application->session }}</strong></div>
    </div>
  </div>
  <div class="alert alert-info mt-3 mb-0 small" role="alert">
    <i class="fa-solid fa-circle-info me-2"></i>Your application has been approved! A room will be assigned to you shortly by the Hostel Office. Check back soon.
  </div>
</div>
@elseif($application && $application->status === 'rejected')
<div class="panel text-center py-5">
  <i class="fa-solid fa-circle-xmark fs-1 text-danger mb-3 d-block"></i>
  <h5>Application Rejected</h5>
  <p class="text-muted small mb-1">Your hostel application for <strong>{{ $application->hostel->name }}</strong> was not approved.</p>
  @if($application->rejection_reason)
    <p class="text-muted small mb-3"><strong>Reason:</strong> {{ $application->rejection_reason }}</p>
  @endif
  <a href="{{ route('student.application') }}" class="btn btn-sumas-primary btn-sm">Submit a New Application</a>
</div>
@elseif($application && $application->status === 'pending')
<div class="panel text-center py-5">
  <i class="fa-solid fa-hourglass-half fs-1 text-warning mb-3 d-block"></i>
  <h5>Application Pending Review</h5>
  <p class="text-muted small mb-3">Your application for <strong>{{ $application->hostel->name }}</strong> is awaiting review by the Hostel Office.</p>
  <div class="text-muted small">Submitted on {{ $application->created_at->format('F j, Y') }}</div>
</div>
@else
<div class="panel text-center py-5">
  <i class="fa-solid fa-house-circle-xmark fs-1 text-muted mb-3 d-block"></i>
  <h5>No active allocation yet</h5>
  <p class="text-muted small mb-3">Once your application is approved and a room is assigned by the Hostel Office, it will appear here.</p>
  <a href="{{ route('student.application') }}" class="btn btn-sumas-primary btn-sm">Submit an Application</a>
</div>
@endif
@endsection
