@extends('layouts.dashboard')
@section('title', 'Hostel Applications')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Hostel Applications</span></nav>
    <h2>Hostel Applications</h2>
    <p class="text-muted small mb-0">Review and act on student hostel applications.</p>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon orange"><i class="fa-solid fa-hourglass-half"></i></div></div><h3>{{ $pendingCount }}</h3><div class="label">Pending</div></div></div>
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon green"><i class="fa-solid fa-circle-check"></i></div></div><h3>{{ $approvedCount }}</h3><div class="label">Approved</div></div></div>
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon red"><i class="fa-solid fa-circle-xmark"></i></div></div><h3>{{ $rejectedCount }}</h3><div class="label">Rejected</div></div></div>
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon brown"><i class="fa-solid fa-file-lines"></i></div></div><h3>{{ $totalCount }}</h3><div class="label">Total This Session</div></div></div>
</div>

<div class="panel">
  <ul class="nav nav-tabs nav-tabs-sumas mb-3">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabPending">Pending <span class="badge bg-warning text-dark ms-1">{{ $pendingCount }}</span></button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabApproved">Approved</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabRejected">Rejected</button></li>
  </ul>

  <form method="GET" action="{{ route('admin.applications') }}" class="row g-2 mb-3">
    <div class="col-lg-5"><div class="dash-search" style="max-width:none;"><i class="fa-solid fa-magnifying-glass"></i><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or matric number..."></div></div>
    <div class="col-6 col-lg-3">
      <select name="hostel_id" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">All Hostels</option>
        @foreach($hostels as $h)<option value="{{ $h->id }}" {{ request('hostel_id') == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>@endforeach
      </select>
    </div>
    <div class="col-6 col-lg-3">
      <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>Sort: Newest First</option>
        <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Sort: Oldest First</option>
      </select>
    </div>
  </form>

  <div class="tab-content">
    <div class="tab-pane fade show active" id="tabPending">
      <div class="table-responsive">
        <table class="table table-sumas align-middle mb-0">
          <thead><tr><th>Applicant</th><th>Preferred Hostel</th><th>Application ID</th><th>Date</th><th>Status</th><th></th></tr></thead>
          <tbody>
            @forelse($pending as $app)
            <tr>
              <td class="d-flex align-items-center gap-2"><div class="table-avatar">{{ $app->user->initials() }}</div><div><div class="fw-600 small">{{ $app->user->name }}</div><div class="text-muted" style="font-size:0.72rem;">{{ $app->user->matric_number }}</div></div></td>
              <td>{{ $app->hostel->name }}</td><td>#APP-{{ str_pad($app->id, 4, '0', STR_PAD_LEFT) }}</td><td>{{ $app->created_at->format('M d, Y') }}</td>
              <td><span class="badge-status pending">Pending</span></td>
              <td class="table-actions"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#appDetailsModal{{ $app->id }}"><i class="fa-solid fa-eye"></i></button></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No pending applications.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-3">{{ $pending->links() }}</div>
    </div>
    <div class="tab-pane fade" id="tabApproved">
      <div class="table-responsive">
        <table class="table table-sumas align-middle mb-0">
          <thead><tr><th>Applicant</th><th>Hostel</th><th>Date Approved</th><th>Status</th><th></th></tr></thead>
          <tbody>
            @forelse($approved as $app)
            <tr>
              <td class="d-flex align-items-center gap-2"><div class="table-avatar">{{ $app->user->initials() }}</div><div><div class="fw-600 small">{{ $app->user->name }}</div><div class="text-muted" style="font-size:0.72rem;">{{ $app->user->matric_number }}</div></div></td>
              <td>{{ $app->hostel->name }}</td><td>{{ $app->reviewed_at?->format('M d, Y') }}</td>
              <td><span class="badge-status approved">Approved</span></td>
              <td class="table-actions"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#appDetailsModal{{ $app->id }}"><i class="fa-solid fa-eye"></i></button></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No approved applications yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-3">{{ $approved->links() }}</div>
    </div>
    <div class="tab-pane fade" id="tabRejected">
      <div class="table-responsive">
        <table class="table table-sumas align-middle mb-0">
          <thead><tr><th>Applicant</th><th>Preferred Hostel</th><th>Reason</th><th>Date</th><th>Status</th><th></th></tr></thead>
          <tbody>
            @forelse($rejected as $app)
            <tr>
              <td class="d-flex align-items-center gap-2"><div class="table-avatar">{{ $app->user->initials() }}</div><div><div class="fw-600 small">{{ $app->user->name }}</div><div class="text-muted" style="font-size:0.72rem;">{{ $app->user->matric_number }}</div></div></td>
              <td>{{ $app->hostel->name }}</td><td>{{ $app->rejection_reason }}</td><td>{{ $app->reviewed_at?->format('M d, Y') }}</td>
              <td><span class="badge-status rejected">Rejected</span></td>
              <td class="table-actions"><button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#appDetailsModal{{ $app->id }}"><i class="fa-solid fa-eye"></i></button></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No rejected applications.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-3">{{ $rejected->links() }}</div>
    </div>
  </div>
</div>

@foreach($pending->concat($approved)->concat($rejected) as $app)
<div class="modal fade" id="appDetailsModal{{ $app->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Application #APP-{{ str_pad($app->id, 4, '0', STR_PAD_LEFT) }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="table-avatar" style="width:56px;height:56px;font-size:1.1rem;">{{ $app->user->initials() }}</div>
          <div><h6 class="mb-0">{{ $app->user->name }}</h6><span class="text-muted small">{{ $app->user->matric_number }} &middot; {{ $app->user->level }} {{ $app->user->faculty }}</span></div>
        </div>
        <div class="row g-3 small">
          <div class="col-6"><span class="text-muted d-block">Preferred Hostel</span><strong>{{ $app->hostel->name }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Gender</span><strong>{{ $app->user->gender }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Date Submitted</span><strong>{{ $app->created_at->format('F j, Y') }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Status</span><span class="badge-status {{ $app->status }}">{{ ucfirst($app->status) }}</span></div>
          <div class="col-12"><span class="text-muted d-block">Reason</span>{{ $app->reason }}</div>
          @if($app->notes)<div class="col-12"><span class="text-muted d-block">Notes from Applicant</span>"{{ $app->notes }}"</div>@endif
          @if($app->status === 'rejected')<div class="col-12"><span class="text-muted d-block">Rejection Reason</span>{{ $app->rejection_reason }}</div>@endif
        </div>
      </div>
      @if($app->status === 'pending')
      <div class="modal-footer">
        <button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Close</button>
        <form method="POST" action="{{ route('admin.applications.reject', $app) }}" class="d-inline">
          @csrf
          <input type="hidden" name="rejection_reason" value="Did not meet hostel eligibility criteria.">
          <button type="submit" class="btn btn-danger text-white">Reject</button>
        </form>
        <form method="POST" action="{{ route('admin.applications.approve', $app) }}" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-sumas-primary">Approve</button>
        </form>
      </div>
      @else
      <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Close</button></div>
      @endif
    </div>
  </div>
</div>
@endforeach
@endsection
