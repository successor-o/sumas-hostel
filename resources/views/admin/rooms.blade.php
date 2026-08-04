@extends('layouts.dashboard')
@section('title', 'Room Management')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Room Management</span></nav>
    <h2>Room Management</h2>
    <p class="text-muted small mb-0">Manage individual rooms, capacity, and live occupancy.</p>
  </div>
  <button class="btn btn-sumas-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRoomModal"><i class="fa-solid fa-plus me-2"></i>Add Room</button>
</div>

<div class="panel mb-4">
  <form method="GET" action="{{ route('admin.rooms') }}" class="row g-2">
    <div class="col-lg-4"><div class="dash-search" style="max-width:none;"><i class="fa-solid fa-magnifying-glass"></i><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search room number..."></div></div>
    <div class="col-6 col-lg-2">
      <select name="hostel_id" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">All Hostels</option>
        @foreach($hostels as $h)<option value="{{ $h->id }}" {{ request('hostel_id') == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>@endforeach
      </select>
    </div>
    <div class="col-6 col-lg-2">
      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="">Any Status</option>
        <option value="vacant" {{ request('status') === 'vacant' ? 'selected' : '' }}>Vacant</option>
        <option value="partial" {{ request('status') === 'partial' ? 'selected' : '' }}>Partially Occupied</option>
        <option value="full" {{ request('status') === 'full' ? 'selected' : '' }}>Full</option>
        <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
      </select>
    </div>
    <div class="col-6 col-lg-2"><button class="btn btn-sumas-outline btn-sm w-100" type="submit">Filter</button></div>
  </form>
</div>

<div class="row g-3">
  @forelse($rooms as $room)
  @php
    $occ = $room->occupiedBeds();
    $cap = $room->capacity;
    $dashOffset = $cap > 0 ? round(150.7 * (1 - $occ / $cap), 1) : 150.7;
    $statusLabel = match($room->status) { 'vacant' => 'Vacant', 'partial' => $occ.'/'.$cap.' Beds', 'full' => 'Full', 'maintenance' => 'Maintenance', default => ucfirst($room->status) };
    $statusClass = match($room->status) { 'vacant' => 'vacant', 'full' => 'full', 'maintenance' => 'maintenance', default => 'pending' };
  @endphp
  <div class="col-sm-6 col-lg-3">
    <div class="entity-card">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div><h6 class="mb-0">Room {{ $room->room_number }}</h6><span class="text-muted small">{{ $room->hostel->name }} &middot; {{ $room->floor }}</span></div>
        <span class="badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
      </div>
      <div class="d-flex align-items-center gap-3 mt-3">
        <div class="cap-ring">
          <svg width="56" height="56"><circle class="bg" cx="28" cy="28" r="24"></circle><circle class="fg" cx="28" cy="28" r="24" stroke-dasharray="150.7" stroke-dashoffset="{{ $dashOffset }}"></circle></svg>
          <div class="val">{{ $occ }}/{{ $cap }}</div>
        </div>
        <div class="small text-muted">{{ $occ }} of {{ $cap }} beds occupied<br>{{ $room->availableBeds() }} beds available</div>
      </div>
      <button class="btn btn-sumas-outline btn-sm w-100 mt-3" data-bs-toggle="modal" data-bs-target="#editRoomModal{{ $room->id }}">Manage Room</button>
    </div>
  </div>
  @empty
  <p class="text-muted text-center py-4">No rooms match your filters.</p>
  @endforelse
</div>

<div class="d-flex justify-content-center mt-4">{{ $rooms->links() }}</div>

@foreach($rooms as $room)
<div class="modal fade" id="editRoomModal{{ $room->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
        @csrf @method('PUT')
        <div class="modal-header"><h5 class="modal-title">Manage Room {{ $room->room_number }}</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label small fw-600">Room Number</label><input type="text" name="room_number" class="form-control" value="{{ $room->room_number }}"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Hostel Block</label><input type="text" class="form-control" value="{{ $room->hostel->name }}" disabled></div>
            <div class="col-md-6"><label class="form-label small fw-600">Capacity</label><input type="number" name="capacity" class="form-control" value="{{ $room->capacity }}" min="1" max="10"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Status</label>
              <select name="status" class="form-select">
                <option value="vacant" {{ $room->status === 'vacant' ? 'selected' : '' }}>Vacant</option>
                <option value="partial" {{ $room->status === 'partial' ? 'selected' : '' }}>Partially Occupied</option>
                <option value="full" {{ $room->status === 'full' ? 'selected' : '' }}>Full</option>
                <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label small fw-600">Current Occupants</label>
              <ul class="list-group">
                @forelse($room->activeAllocations as $alloc)
                  <li class="list-group-item d-flex justify-content-between align-items-center small">{{ $alloc->user->name }} <span class="badge bg-secondary">Bed {{ $alloc->bed_number }}</span></li>
                @empty
                  <li class="list-group-item small text-muted">No occupants yet.</li>
                @endforelse
              </ul>
            </div>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" onsubmit="return confirm('Delete this room? This cannot be undone.');">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger btn-sm">Delete Room</button></form>
          <div><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button> <button type="submit" class="btn btn-sumas-primary">Save Changes</button></div>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<div class="modal fade" id="addRoomModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.rooms.store') }}">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Add New Room</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label small fw-600">Room Number</label><input type="text" name="room_number" class="form-control" placeholder="e.g. A-115" required></div>
            <div class="col-md-6"><label class="form-label small fw-600">Hostel Block</label>
              <select name="hostel_id" class="form-select" required>
                @foreach($hostels as $h)<option value="{{ $h->id }}">{{ $h->name }}</option>@endforeach
              </select>
            </div>
            <div class="col-md-6"><label class="form-label small fw-600">Floor</label>
              <select name="floor" class="form-select">
                <option>Ground Floor</option><option>1st Floor</option><option>2nd Floor</option><option>3rd Floor</option>
              </select>
            </div>
            <div class="col-md-6"><label class="form-label small fw-600">Capacity (Beds)</label><input type="number" name="capacity" class="form-control" value="4" min="1" max="10" required></div>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sumas-primary">Save Room</button></div>
      </form>
    </div>
  </div>
</div>
@endsection
