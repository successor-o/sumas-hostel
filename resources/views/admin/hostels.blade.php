@extends('layouts.dashboard')
@section('title', 'Hostel Management')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Hostel Management</span></nav>
    <h2>Hostel Management</h2>
    <p class="text-muted small mb-0">Add, edit, and monitor every hostel block on campus.</p>
  </div>
  <button class="btn btn-sumas-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addHostelModal"><i class="fa-solid fa-plus me-2"></i>Add Hostel</button>
</div>

<div class="row g-4">
  @foreach($hostels as $hostel)
  <div class="col-md-6 col-lg-4">
    <div class="hostel-card">
      <div class="img-wrap">
        <img src="{{ $hostel->imageUrl() }}" alt="{{ $hostel->name }}">
        <span class="tag">{{ $hostel->category }}</span>
        <span class="status-pill status-{{ strtolower($hostel->occupancyStatusLabel()) }}">{{ $hostel->status }}</span>
      </div>
      <div class="body">
        <h5 class="mb-1">{{ $hostel->name }}</h5>
        <p class="text-muted small mb-2"><i class="fa-solid fa-location-dot me-1"></i>{{ config('sumas.campus_address') }}</p>
        <div class="meta"><span><i class="fa-solid fa-bed"></i> {{ $hostel->totalBeds() }} Beds</span><span><i class="fa-solid fa-door-open"></i> {{ $hostel->rooms_count }} Rooms</span></div>
        <div class="occ-bar mb-2"><div class="fill" style="width:{{ $hostel->occupancyPercent() }}%;"></div></div>
        <div class="d-flex justify-content-between small text-muted mb-3"><span>{{ $hostel->occupancyPercent() }}% Occupied</span><span>{{ $hostel->availableBeds() }} open</span></div>
        <div class="d-flex gap-2">
          <button class="btn btn-sumas-outline btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#hostelDetailsModal{{ $hostel->id }}"><i class="fa-solid fa-eye me-1"></i>Details</button>
          <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editHostelModal{{ $hostel->id }}"><i class="fa-solid fa-pen"></i></button>
          <button class="btn btn-light btn-sm text-danger" data-bs-toggle="modal" data-bs-target="#deleteHostelModal{{ $hostel->id }}"><i class="fa-solid fa-trash"></i></button>
        </div>
      </div>
    </div>
  </div>
  @endforeach
</div>

@foreach($hostels as $hostel)
<div class="modal fade" id="hostelDetailsModal{{ $hostel->id }}" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">{{ $hostel->name }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <img src="{{ $hostel->imageUrl() }}" class="w-100 rounded-3 mb-3" style="height:240px;object-fit:cover;">
        <div class="row g-3 small">
          <div class="col-6"><span class="text-muted d-block">Category</span><strong>{{ $hostel->category }} Hostel</strong></div>
          <div class="col-6"><span class="text-muted d-block">Status</span><span class="badge-status active">{{ $hostel->status }}</span></div>
          <div class="col-6"><span class="text-muted d-block">Total Rooms</span><strong>{{ $hostel->rooms_count }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Total Beds</span><strong>{{ $hostel->totalBeds() }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Occupied Beds</span><strong>{{ $hostel->occupiedBeds() }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Warden</span><strong>{{ $hostel->warden ?: '—' }}</strong></div>
          <div class="col-12"><span class="text-muted d-block">Description</span>{{ $hostel->description ?: 'No description added yet.' }}</div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-sumas-primary" data-bs-toggle="modal" data-bs-target="#editHostelModal{{ $hostel->id }}">Edit Hostel</button></div>
    </div>
  </div>
</div>

<div class="modal fade" id="editHostelModal{{ $hostel->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.hostels.update', $hostel) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="modal-header"><h5 class="modal-title">Edit Hostel</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12"><label class="form-label small fw-600">Hostel Name</label><input type="text" name="name" class="form-control" value="{{ $hostel->name }}"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Category</label><select name="category" class="form-select"><option {{ $hostel->category === 'Male' ? 'selected' : '' }}>Male</option><option {{ $hostel->category === 'Female' ? 'selected' : '' }}>Female</option><option {{ $hostel->category === 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option></select></div>
            <div class="col-md-6"><label class="form-label small fw-600">Status</label><select name="status" class="form-select"><option {{ $hostel->status === 'Active' ? 'selected' : '' }}>Active</option><option {{ $hostel->status === 'Under Maintenance' ? 'selected' : '' }}>Under Maintenance</option><option {{ $hostel->status === 'Closed' ? 'selected' : '' }}>Closed</option></select></div>
            <div class="col-md-6"><label class="form-label small fw-600">Warden</label><input type="text" name="warden" class="form-control" value="{{ $hostel->warden }}"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Image</label><input type="file" name="image" class="form-control"></div>
            <div class="col-12"><label class="form-label small fw-600">Description</label><textarea name="description" class="form-control" rows="3">{{ $hostel->description }}</textarea></div>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sumas-primary">Save Changes</button></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteHostelModal{{ $hostel->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center p-4">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:64px;height:64px;background:var(--sumas-danger-bg);color:var(--sumas-danger);font-size:1.6rem;"><i class="fa-solid fa-trash"></i></div>
        <h5>Delete {{ $hostel->name }}?</h5>
        <p class="text-muted small">All associated rooms and allocation records will be removed. This action cannot be undone.</p>
        <div class="d-flex gap-2 justify-content-center mt-3">
          <button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button>
          <form method="POST" action="{{ route('admin.hostels.destroy', $hostel) }}">@csrf @method('DELETE')<button type="submit" class="btn btn-danger text-white">Yes, Delete</button></form>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach

<div class="modal fade" id="addHostelModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.hostels.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Add New Hostel</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12"><label class="form-label small fw-600">Hostel Name</label><input type="text" name="name" class="form-control" placeholder="e.g. Male Hostel - Block D" required></div>
            <div class="col-md-6"><label class="form-label small fw-600">Category</label><select name="category" class="form-select"><option>Male</option><option>Female</option><option>Postgraduate</option></select></div>
            <div class="col-md-6"><label class="form-label small fw-600">Warden</label><input type="text" name="warden" class="form-control" placeholder="Warden's name"></div>
            <div class="col-12"><label class="form-label small fw-600">Hostel Image</label><input type="file" name="image" class="form-control"></div>
            <div class="col-12"><label class="form-label small fw-600">Description</label><textarea name="description" class="form-control" rows="3" placeholder="Short description of the block..."></textarea></div>
          </div>
          <p class="text-muted small mt-3 mb-0">You can add rooms to this hostel afterward from Room Management.</p>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sumas-primary">Save Hostel</button></div>
      </form>
    </div>
  </div>
</div>
@endsection
