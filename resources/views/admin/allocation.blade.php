@extends('layouts.dashboard')
@section('title', 'Hostel Allocation')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Hostel Allocation</span></nav>
    <h2>Hostel Allocation</h2>
    <p class="text-muted small mb-0">Assign approved applicants to hostel rooms and issue allocation slips.</p>
  </div>
  <button class="btn btn-sumas-primary btn-sm" data-bs-toggle="modal" data-bs-target="#allocateModal" {{ $awaitingAllocation->isEmpty() ? 'disabled' : '' }}><i class="fa-solid fa-key me-2"></i>New Allocation</button>
</div>

@if($awaitingAllocation->isNotEmpty())
<div class="alert d-flex align-items-center gap-2 mb-4" style="background:var(--sumas-warning-bg); color:var(--sumas-warning); border:none; border-radius:var(--r-md);">
  <i class="fa-solid fa-triangle-exclamation fs-5"></i>
  <div><strong>{{ $awaitingAllocation->count() }} approved application(s)</strong> still need a room assigned.</div>
</div>
@endif

<div class="panel">
  <form method="GET" action="{{ route('admin.allocation') }}" class="row g-2 mb-3">
    <div class="col-lg-5"><div class="dash-search" style="max-width:none;"><i class="fa-solid fa-magnifying-glass"></i><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by student name..."></div></div>
    <div class="col-6 col-lg-3">
      <select name="hostel_id" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">All Hostels</option>
        @foreach($hostels as $h)<option value="{{ $h->id }}" {{ request('hostel_id') == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>@endforeach
      </select>
    </div>
    <div class="col-6 col-lg-4">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">Allocation Status: All</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="vacated" {{ request('status') === 'vacated' ? 'selected' : '' }}>Vacated</option>
      </select>
    </div>
  </form>
  <div class="table-responsive">
    <table class="table table-sumas align-middle mb-0">
      <thead><tr><th>Student</th><th>Hostel Assigned</th><th>Room / Bed</th><th>Date Allocated</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
        @forelse($allocations as $alloc)
        <tr>
          <td class="d-flex align-items-center gap-2"><div class="table-avatar">{{ $alloc->user->initials() }}</div><div><div class="fw-600 small">{{ $alloc->user->name }}</div><div class="text-muted" style="font-size:0.72rem;">{{ $alloc->user->matric_number }}</div></div></td>
          <td>{{ $alloc->hostel->name }}</td><td>Room {{ $alloc->room->room_number }}, Bed {{ $alloc->bed_number }}</td><td>{{ $alloc->allocated_at?->format('M d, Y') }}</td>
          <td><span class="badge-status {{ $alloc->status === 'active' ? 'active' : 'maintenance' }}">{{ ucfirst($alloc->status) }}</span></td>
          <td class="table-actions">
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#slipModal{{ $alloc->id }}" title="View Slip"><i class="fa-solid fa-file-lines"></i></button>
            @if($alloc->status === 'active')
            <form method="POST" action="{{ route('admin.allocation.vacate', $alloc) }}" class="d-inline" onsubmit="return confirm('Mark this allocation as vacated?');">@csrf<button type="submit" class="btn btn-light btn-sm text-danger" title="Vacate"><i class="fa-solid fa-door-open"></i></button></form>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted py-4">No allocations match your filters.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-3">{{ $allocations->links() }}</div>
</div>

@foreach($allocations as $alloc)
<div class="modal fade" id="slipModal{{ $alloc->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header no-print"><h5 class="modal-title">Allocation Slip</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <img src="{{ asset('assets/images/sumas-logo.png') }}" style="height:52px;" alt="SUMAS logo">
          <h5 class="mt-2 mb-0">{{ config('sumas.institution_name') }}</h5>
          <span class="text-muted small">Official Hostel Allocation Slip</span>
        </div>
        <hr>
        <div class="row g-3 small">
          <div class="col-6"><span class="text-muted d-block">Student Name</span><strong>{{ $alloc->user->name }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Matric Number</span><strong>{{ $alloc->user->matric_number }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Hostel Block</span><strong>{{ $alloc->hostel->name }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Room / Bed</span><strong>{{ $alloc->room->room_number }} / Bed {{ $alloc->bed_number }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Date Allocated</span><strong>{{ $alloc->allocated_at?->format('F j, Y') }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Academic Session</span><strong>{{ $alloc->session }}</strong></div>
        </div>
        <hr>
        <p class="small text-muted mb-0">This slip confirms official hostel accommodation for the student named above.</p>
        <div class="text-end small text-muted mt-4">Issued by: {{ $alloc->allocatedBy?->name ?? 'Hostel Office' }}</div>
      </div>
      <div class="modal-footer no-print"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-sumas-primary" onclick="window.print()"><i class="fa-solid fa-print me-2"></i>Print Slip</button></div>
    </div>
  </div>
</div>
@endforeach

<div class="modal fade" id="allocateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.allocation.store') }}">
        @csrf
        <div class="modal-header"><h5 class="modal-title">New Hostel Allocation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label small fw-600">Approved Student</label>
              <select name="hostel_application_id" id="allocAppSelect" class="form-select" required>
                <option value="" selected disabled>Choose a student</option>
                @foreach($awaitingAllocation as $app)
                  <option value="{{ $app->id }}" data-hostel-id="{{ $app->hostel_id }}">{{ $app->user->name }} &mdash; {{ $app->user->matric_number }} ({{ $app->hostel->name }})</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="form-label small fw-600">Available Room</label>
              <select name="room_id" id="allocRoomSelect" class="form-select" required>
                <option value="" selected disabled>Select a student first</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sumas-primary">Confirm Allocation</button></div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('allocAppSelect')?.addEventListener('change', function () {
  var hostelId = this.options[this.selectedIndex].getAttribute('data-hostel-id');
  var roomSelect = document.getElementById('allocRoomSelect');
  roomSelect.innerHTML = '<option>Loading rooms...</option>';

  fetch('{{ route('admin.allocation.rooms') }}?hostel_id=' + hostelId, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
    .then(function (res) { return res.json(); })
    .then(function (rooms) {
      if (!rooms.length) {
        roomSelect.innerHTML = '<option value="" disabled selected>No available rooms in this hostel</option>';
        return;
      }
      roomSelect.innerHTML = rooms.map(function (r) {
        return '<option value="' + r.id + '">' + r.label + '</option>';
      }).join('');
    });
});
</script>
@endpush
