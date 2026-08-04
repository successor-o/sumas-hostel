@extends('layouts.dashboard')
@section('title', 'Hostel Rules')

@section('content')
<div class="dash-heading">
  <div>
    <nav class="breadcrumb-dash mb-1"><a href="{{ route('student.dashboard') }}">Student Portal</a> / <span class="active">Hostel Rules</span></nav>
    <h2>Hostel Rules &amp; Regulations</h2>
    <p class="text-muted small mb-0">Please read carefully &mdash; these apply to every resident.</p>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="panel">
      <div class="panel-head"><h5>Code of Conduct</h5></div>
      <div class="accordion accordion-sumas" id="rulesAccordion">
        @php
          $rules = [
            ['t' => '1. Curfew & Access', 'd' => 'Hostel gates close at 10:00 PM and reopen at 5:00 AM. Residents must sign in and out at the gatehouse. Overnight guests are not permitted without prior written approval from the warden.'],
            ['t' => '2. Visitors', 'd' => 'Visitors are received only in designated common areas between 8:00 AM and 8:00 PM. Opposite-gender visitors are not permitted beyond the reception area.'],
            ['t' => '3. Cleanliness', 'd' => 'Residents are responsible for keeping their rooms and shared spaces clean. Routine inspections are carried out monthly, with results shared through the notifications page.'],
            ['t' => '4. Prohibited Items', 'd' => 'Cooking appliances beyond the designated kitchenette, alcohol, and open flames are prohibited in rooms for fire safety reasons.'],
            ['t' => '5. Noise & Conduct', 'd' => 'Quiet hours run from 10:00 PM to 6:00 AM. Disruptive or abusive conduct toward other residents or staff may result in loss of hostel accommodation.'],
            ['t' => '6. Damages & Maintenance', 'd' => 'Residents are financially responsible for any damage to furniture or fittings beyond normal wear. Report maintenance issues promptly through the Hostel Office.'],
            ['t' => '7. Vacating the Room', 'd' => 'Rooms must be vacated within 72 hours of the last examination of the session, or upon withdrawal, suspension, or graduation.'],
          ];
        @endphp
        @foreach($rules as $i => $rule)
        <div class="accordion-item">
          <h2 class="accordion-header"><button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#r{{ $i }}">{{ $rule['t'] }}</button></h2>
          <div id="r{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#rulesAccordion"><div class="accordion-body">{{ $rule['d'] }}</div></div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="panel">
      <div class="panel-head"><h5>Quick Reference</h5></div>
      <div class="d-flex gap-2 mb-3 small"><i class="fa-solid fa-clock text-gold mt-1"></i><span>Gate closes 10:00 PM &middot; opens 5:00 AM</span></div>
      <div class="d-flex gap-2 mb-3 small"><i class="fa-solid fa-user-group text-gold mt-1"></i><span>Visiting hours: 8:00 AM &ndash; 8:00 PM</span></div>
      <div class="d-flex gap-2 mb-3 small"><i class="fa-solid fa-ban text-gold mt-1"></i><span>No cooking appliances or open flames</span></div>
      <div class="d-flex gap-2 small"><i class="fa-solid fa-broom text-gold mt-1"></i><span>Monthly room inspections</span></div>
      <hr>
      <a href="{{ route('contact') }}" class="btn btn-sumas-outline w-100 btn-sm"><i class="fa-solid fa-headset me-2"></i>Report a Concern</a>
    </div>
  </div>
</div>
@endsection
