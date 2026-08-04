@extends('layouts.dashboard')
@section('title', 'Hostel Application')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('student.dashboard') }}">Student Portal</a> / <span class="active">Hostel Application</span></nav>
    <h2>Hostel Application</h2>
    <p class="text-muted small mb-0">Session {{ config('sumas.session') }}</p>
  </div>
</div>

@if($existingApplication)
<div class="alert d-flex align-items-center gap-2 mb-4" style="background:var(--sumas-{{ $existingApplication->status === 'approved' ? 'success' : ($existingApplication->status === 'rejected' ? 'danger' : 'warning') }}-bg); color:var(--sumas-{{ $existingApplication->status === 'approved' ? 'success' : ($existingApplication->status === 'rejected' ? 'danger' : 'warning') }}); border:none; border-radius:var(--r-md);">
  <i class="fa-solid {{ $existingApplication->status === 'approved' ? 'fa-circle-check' : ($existingApplication->status === 'rejected' ? 'fa-circle-xmark' : 'fa-hourglass-half') }} fs-5"></i>
  <div>
    <strong>Your application is currently {{ $existingApplication->status }}</strong> for {{ $existingApplication->hostel->name }}.
    @if($existingApplication->status === 'rejected')
      Reason: {{ $existingApplication->rejection_reason }}. You may submit a new application below.
    @elseif($existingApplication->status === 'pending')
      You'll be notified once the Hostel Office reviews it. Submitting again below will update this application.
    @else
      Visit "My Allocation" to see your assigned room once allocated.
    @endif
  </div>
</div>
@endif

<div class="row g-3">
  <div class="col-lg-8">
    <div class="panel">
      <div class="panel-head"><h5>{{ $existingApplication ? 'Update Application' : 'New Application Form' }}</h5><span class="sub">All fields required unless noted optional</span></div>
      <form method="POST" action="{{ route('student.application.store') }}" class="needs-validation" novalidate>
        @csrf
        <h6 class="text-muted small text-uppercase mt-2 mb-3" style="letter-spacing:0.05em;">1. Applicant Details</h6>
        <div class="row g-3 mb-4">
          <div class="col-md-6"><label class="form-label small fw-600">Full Name</label><input type="text" class="form-control" value="{{ $student->name }}" disabled></div>
          <div class="col-md-6"><label class="form-label small fw-600">Matric Number</label><input type="text" class="form-control" value="{{ $student->matric_number }}" disabled></div>
          <div class="col-md-6"><label class="form-label small fw-600">Faculty</label><input type="text" class="form-control" value="{{ $student->faculty }}" disabled></div>
          <div class="col-md-6"><label class="form-label small fw-600">Level</label><input type="text" class="form-control" value="{{ $student->level }}" disabled></div>
        </div>

        <h6 class="text-muted small text-uppercase mt-2 mb-3" style="letter-spacing:0.05em;">2. Hostel Preference</h6>
        <div class="row g-3 mb-4">
          <div class="col-md-6">
            <label class="form-label small fw-600">Preferred Hostel Block</label>
            <select name="hostel_id" class="form-select @error('hostel_id') is-invalid @enderror" required>
              <option value="" selected disabled>Choose a hostel</option>
              @foreach($hostels as $hostel)
                <option value="{{ $hostel->id }}" {{ old('hostel_id', $existingApplication->hostel_id ?? '') == $hostel->id ? 'selected' : '' }} {{ $hostel->availableBeds() <= 0 ? 'disabled' : '' }}>
                  {{ $hostel->name }} ({{ $hostel->availableBeds() }} beds open)
                </option>
              @endforeach
            </select>
            <div class="invalid-feedback">Please select a hostel.</div>
          </div>
          <div class="col-md-6">
            <label class="form-label small fw-600">Reason for Application</label>
            <select name="reason" class="form-select @error('reason') is-invalid @enderror" required>
              <option value="" selected disabled>Select a reason</option>
              @foreach(['Live far from campus', 'Medical / accessibility need', 'Security concerns off-campus', 'Other'] as $reason)
                <option {{ old('reason', $existingApplication->reason ?? '') === $reason ? 'selected' : '' }}>{{ $reason }}</option>
              @endforeach
            </select>
            <div class="invalid-feedback">Please select a reason.</div>
          </div>
          <div class="col-12">
            <label class="form-label small fw-600">Preferred Roommate (optional)</label>
            <input type="text" name="preferred_roommate" class="form-control" value="{{ old('preferred_roommate', $existingApplication->preferred_roommate ?? '') }}" placeholder="Name and matric number, if applicable">
          </div>
        </div>

        <h6 class="text-muted small text-uppercase mt-2 mb-3" style="letter-spacing:0.05em;">3. Supporting Notes</h6>
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label small fw-600">Additional Notes (optional)</label>
            <textarea name="notes" class="form-control" rows="4" placeholder="Anything else the hostel office should know...">{{ old('notes', $existingApplication->notes ?? '') }}</textarea>
          </div>
          <div class="col-12">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="agree_rules" id="agreeRules" required>
              <label class="form-check-label small" for="agreeRules">I confirm the information above is accurate and I agree to abide by the <a href="{{ route('student.rules') }}" class="text-brown fw-600">Hostel Rules</a>.</label>
              <div class="invalid-feedback">You must agree before submitting.</div>
            </div>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-sumas-primary px-4">Submit Application <i class="fa-solid fa-paper-plane ms-2"></i></button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="panel mb-3">
      <div class="panel-head"><h5>Application Guide</h5></div>
      <div class="d-flex gap-2 mb-3 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i><span>Applications are reviewed in 3&ndash;5 working days.</span></div>
      <div class="d-flex gap-2 mb-3 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i><span>You'll get a notification once a decision is made.</span></div>
      <div class="d-flex gap-2 mb-3 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i><span>Only one active application is allowed per session.</span></div>
      <div class="d-flex gap-2 small"><i class="fa-solid fa-circle-check text-gold mt-1"></i><span>Check "My Allocation" once approved to view your room.</span></div>
    </div>
    <div class="panel">
      <div class="panel-head"><h5>Need Help?</h5></div>
      <p class="text-muted small">Contact the Hostel Office if you have questions about your application.</p>
      <a href="{{ route('contact') }}" class="btn btn-sumas-outline w-100 btn-sm"><i class="fa-solid fa-headset me-2"></i>Contact Hostel Office</a>
    </div>
  </div>
</div>
@endsection
