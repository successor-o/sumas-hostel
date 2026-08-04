@extends('layouts.dashboard')
@section('title', 'Students')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('admin.dashboard') }}">Admin</a> / <span class="active">Students</span></nav>
    <h2>Students</h2>
    <p class="text-muted small mb-0">Manage all registered hostel-eligible students.</p>
  </div>
  <button class="btn btn-sumas-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal"><i class="fa-solid fa-plus me-2"></i>Add Student</button>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon brown"><i class="fa-solid fa-user-graduate"></i></div></div><h3>{{ $totalStudents }}</h3><div class="label">Total Students</div></div></div>
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon green"><i class="fa-solid fa-house-user"></i></div></div><h3>{{ $housedCount }}</h3><div class="label">Currently Housed</div></div></div>
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon orange"><i class="fa-solid fa-clock"></i></div></div><h3>{{ $pendingCount }}</h3><div class="label">Awaiting Allocation</div></div></div>
  <div class="col-6 col-lg-3"><div class="stat-card"><div class="top"><div class="icon blue"><i class="fa-solid fa-user-plus"></i></div></div><h3>{{ $newThisMonth }}</h3><div class="label">New This Month</div></div></div>
</div>

<div class="panel mb-4">
  <form method="GET" action="{{ route('admin.students') }}" class="panel-head">
    <div class="row g-2 w-100">
      <div class="col-lg-4"><div class="dash-search" style="max-width:none;"><i class="fa-solid fa-magnifying-glass"></i><input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by name or matric number..."></div></div>
      <div class="col-6 col-lg-2">
        <select name="faculty" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">All Faculties</option>
          @foreach($faculties as $f)<option value="{{ $f }}" {{ request('faculty') === $f ? 'selected' : '' }}>{{ $f }}</option>@endforeach
        </select>
      </div>
      <div class="col-6 col-lg-2">
        <select name="level" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">All Levels</option>
          @foreach(['100 Level','200 Level','300 Level','400 Level','500 Level'] as $lvl)<option value="{{ $lvl }}" {{ request('level') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>@endforeach
        </select>
      </div>
      <div class="col-6 col-lg-2">
        <select name="gender" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">All Genders</option>
          <option value="Male" {{ request('gender') === 'Male' ? 'selected' : '' }}>Male</option>
          <option value="Female" {{ request('gender') === 'Female' ? 'selected' : '' }}>Female</option>
        </select>
      </div>
      <div class="col-6 col-lg-2">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Account Status</option>
          <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
          <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
          <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>
      <div class="col-6 col-lg-2">
        <select name="housing" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">Housing Status</option>
          <option value="housed" {{ request('housing') === 'housed' ? 'selected' : '' }}>Housed</option>
          <option value="not_housed" {{ request('housing') === 'not_housed' ? 'selected' : '' }}>Not Housed</option>
        </select>
      </div>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table table-sumas align-middle mb-0">
      <thead><tr><th>Student</th><th>Matric No.</th><th>Faculty</th><th>Level</th><th>Gender</th><th>Account Status</th><th>Hostel</th><th>Status</th><th></th></tr></thead>
      <tbody>
        @forelse($students as $student)
        <tr>
          <td class="d-flex align-items-center gap-2"><div class="table-avatar">{{ $student->initials() }}</div><span class="fw-600 small">{{ $student->name }}</span></td>
          <td>{{ $student->matric_number }}</td><td>{{ $student->faculty }}</td><td>{{ $student->level }}</td><td>{{ $student->gender }}</td>
          <td><span class="badge-status {{ $student->status }}">{{ ucfirst($student->status) }}</span></td>
          <td>{{ $student->activeAllocation ? $student->activeAllocation->hostel->name : '—' }}</td>
          <td><span class="badge-status {{ $student->activeAllocation ? 'active' : 'pending' }}">{{ $student->activeAllocation ? 'Housed' : 'Not Housed' }}</span></td>
          <td class="table-actions">
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#viewStudentModal{{ $student->id }}" title="View"><i class="fa-solid fa-eye"></i></button>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->id }}" title="Edit"><i class="fa-solid fa-pen"></i></button>
            <button class="btn btn-light btn-sm text-danger" data-bs-toggle="modal" data-bs-target="#deleteStudentModal{{ $student->id }}" title="Delete"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
        @empty
        <tr><td colspan="9" class="text-center text-muted py-4">No students match your filters.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
    <span class="small text-muted">Showing {{ $students->firstItem() ?? 0 }}&ndash;{{ $students->lastItem() ?? 0 }} of {{ $students->total() }} students</span>
    <div>{{ $students->onEachSide(1)->links() }}</div>
  </div>
</div>

@foreach($students as $student)
<div class="modal fade" id="viewStudentModal{{ $student->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Student Profile</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="text-center mb-3"><div class="table-avatar mx-auto mb-2" style="width:70px;height:70px;font-size:1.4rem;">{{ $student->initials() }}</div><h5 class="mb-0">{{ $student->name }}</h5><span class="text-muted small">{{ $student->matric_number }}</span></div>
        <div class="row g-3 small">
          <div class="col-6"><span class="text-muted d-block">Faculty</span><strong>{{ $student->faculty }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Level</span><strong>{{ $student->level }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Gender</span><strong>{{ $student->gender }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Phone</span><strong>{{ $student->phone ?: '—' }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Email</span><strong>{{ $student->email }}</strong></div>
          <div class="col-6"><span class="text-muted d-block">Status</span><span class="badge-status {{ $student->activeAllocation ? 'active' : 'pending' }}">{{ $student->activeAllocation ? 'Housed' : 'Not Housed' }}</span></div>
          <div class="col-12"><hr><span class="text-muted d-block">Current Allocation</span><strong>{{ $student->activeAllocation ? $student->activeAllocation->hostel->name.' · Room '.$student->activeAllocation->room->room_number.' · Bed '.$student->activeAllocation->bed_number : 'None yet' }}</strong></div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Close</button><button type="button" class="btn btn-sumas-primary" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->id }}">Edit Profile</button></div>
    </div>
  </div>
</div>

<div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.students.update', $student) }}">
        @csrf @method('PUT')
        <div class="modal-header"><h5 class="modal-title">Edit Student</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label small fw-600">Full Name</label><input type="text" name="name" class="form-control" value="{{ $student->name }}"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Matric Number</label><input type="text" name="matric_number" class="form-control" value="{{ $student->matric_number }}"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Faculty</label>
              <select name="faculty" class="form-select">
                @foreach(['Clinical Medicine','Basic Medical Sciences','Allied Health Sciences','Applied Sciences'] as $f)<option {{ $student->faculty === $f ? 'selected' : '' }}>{{ $f }}</option>@endforeach
              </select>
            </div>
            <div class="col-md-6"><label class="form-label small fw-600">Level</label>
              <select name="level" class="form-select">
                @foreach(['100 Level','200 Level','300 Level','400 Level','500 Level','Postgraduate'] as $lvl)<option {{ $student->level === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>@endforeach
              </select>
            </div>
            <div class="col-md-6"><label class="form-label small fw-600">Phone</label><input type="tel" name="phone" class="form-control" value="{{ $student->phone }}"></div>
            <div class="col-md-6"><label class="form-label small fw-600">Email</label><input type="email" name="email" class="form-control" value="{{ $student->email }}"></div>
            <div class="col-12"><label class="form-label small fw-600">Account Status</label>
              <select name="status" class="form-select">
                <option value="pending" {{ $student->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $student->status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $student->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sumas-primary">Save Changes</button></div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteStudentModal{{ $student->id }}" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center p-4">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:64px;height:64px;background:var(--sumas-danger-bg);color:var(--sumas-danger);font-size:1.6rem;"><i class="fa-solid fa-trash"></i></div>
        <h5>Delete {{ $student->name }}?</h5>
        <p class="text-muted small">This will permanently remove the student record and their hostel history. This action cannot be undone.</p>
        <div class="d-flex gap-2 justify-content-center mt-3">
          <button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button>
          <form method="POST" action="{{ route('admin.students.destroy', $student) }}">@csrf @method('DELETE')<button type="submit" class="btn btn-danger text-white">Yes, Delete</button></form>
        </div>
      </div>
    </div>
  </div>
</div>
@endforeach

<div class="modal fade" id="addStudentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.students.store') }}">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Add New Student</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6"><label class="form-label small fw-600">Full Name</label><input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required></div>
            <div class="col-md-6"><label class="form-label small fw-600">Matric Number</label><input type="text" name="matric_number" class="form-control" placeholder="SUMAS/24/2001" required></div>
            <div class="col-md-6"><label class="form-label small fw-600">Faculty</label><select name="faculty" class="form-select"><option>Clinical Medicine</option><option>Basic Medical Sciences</option><option>Allied Health Sciences</option><option>Applied Sciences</option></select></div>
            <div class="col-md-6"><label class="form-label small fw-600">Level</label><select name="level" class="form-select"><option>100 Level</option><option>200 Level</option><option>300 Level</option></select></div>
            <div class="col-md-6"><label class="form-label small fw-600">Gender</label><select name="gender" class="form-select"><option>Male</option><option>Female</option></select></div>
            <div class="col-md-6"><label class="form-label small fw-600">Phone</label><input type="tel" name="phone" class="form-control" placeholder="0800 000 0000"></div>
            <div class="col-12"><label class="form-label small fw-600">Email</label><input type="email" name="email" class="form-control" placeholder="student@sumas.edu.ng" required></div>
          </div>
          <p class="text-muted small mt-3 mb-0">Default password will be set to <code>password</code> &mdash; the student should change it after first login.</p>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-sumas-outline" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-sumas-primary">Add Student</button></div>
      </form>
    </div>
  </div>
</div>
@endsection
