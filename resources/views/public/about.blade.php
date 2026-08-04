@extends('layouts.public')
@section('title', 'About Hostel')

@section('content')
<header class="page-header">
  <div class="container">
    <span class="eyebrow">Get To Know Us</span>
    <h1>About The Hostel</h1>
    <nav class="breadcrumb-sumas small mt-2"><a href="{{ route('home') }}">Home</a> <i class="fa-solid fa-angle-right mx-2" style="color:rgba(255,255,255,0.4)"></i> <span class="active">About Hostel</span></nav>
  </div>
</header>

<section class="section-pad">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 reveal order-lg-2"><img src="{{ asset('assets/images/campus-gate.jpg') }}" class="img-fluid rounded-4 shadow-md" alt="SUMAS campus entrance gate"></div>
      <div class="col-lg-6 reveal order-lg-1">
        <span class="eyebrow">Our Story</span>
        <h2 class="mb-3">Purpose-built residences within a medical sciences campus</h2>
        <p class="text-muted">The State University of Medical and Applied Sciences (SUMAS) sits on a growing campus at Igbo Eno, Enugu State. Because many of our programmes involve clinical placements and early morning laboratory sessions, the University maintains dedicated hostel blocks on campus so students can live within walking distance of lecture halls, laboratories, and the teaching hospital.</p>
        <p class="text-muted">The Hostel Allocation and Management System was introduced to replace manual, paper-based bed assignment with a transparent digital process.</p>
        <div class="row g-3 mt-2">
          <div class="col-6"><div class="d-flex align-items-center gap-3"><div class="crest-badge sm"><i class="fa-solid fa-building-shield"></i></div><div><strong class="d-block">{{ $hostelCount ?? 6 }} Blocks</strong><span class="text-muted small">Male &amp; Female</span></div></div></div>
          <div class="col-6"><div class="d-flex align-items-center gap-3"><div class="crest-badge sm gold"><i class="fa-solid fa-user-graduate"></i></div><div><strong class="d-block">{{ $studentCount ?? 0 }}+</strong><span class="text-muted small">Students Housed</span></div></div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-pad bg-cream">
  <div class="container">
    <div class="text-center mb-5 reveal"><span class="eyebrow justify-content-center">Our Commitment</span><h2>What guides how we manage hostel life</h2></div>
    <div class="row g-4">
      <div class="col-md-4 reveal"><div class="feature-card text-center"><div class="icon-wrap mx-auto"><i class="fa-solid fa-scale-balanced"></i></div><h5>Fair Allocation</h5><p class="text-muted small mb-0">Rooms are assigned by transparent, documented criteria &mdash; never by favouritism.</p></div></div>
      <div class="col-md-4 reveal"><div class="feature-card text-center"><div class="icon-wrap mx-auto"><i class="fa-solid fa-user-shield"></i></div><h5>Student Safety</h5><p class="text-muted small mb-0">Gated, gender-separated blocks with resident wardens and nightly security patrols.</p></div></div>
      <div class="col-md-4 reveal"><div class="feature-card text-center"><div class="icon-wrap mx-auto"><i class="fa-solid fa-people-roof"></i></div><h5>Community Living</h5><p class="text-muted small mb-0">Shared common rooms and study areas designed to foster peer support.</p></div></div>
    </div>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-6 reveal"><img src="{{ asset('assets/images/male-hostel.jpg') }}" class="img-fluid rounded-4 shadow-sm mb-3" style="height:260px;object-fit:cover;width:100%;" alt="Male Hostel entrance"><h5>Male Hostel Blocks</h5><p class="text-muted small">Three blocks with shared reading rooms, common kitchens, and a resident hall officer's office at each entrance.</p></div>
      <div class="col-md-6 reveal"><img src="{{ asset('assets/images/female-hostel.jpg') }}" class="img-fluid rounded-4 shadow-sm mb-3" style="height:260px;object-fit:cover;width:100%;" alt="Female Hostel entrance"><h5>Female Hostel Blocks</h5><p class="text-muted small">Three gated blocks with a dedicated matron's office, visiting hours desk, and enclosed compound.</p></div>
    </div>
  </div>
</section>

<section class="section-pad-sm bg-brown">
  <div class="container text-center reveal">
    <h2 class="text-white mb-3">See which hostel blocks have space right now</h2>
    <a href="{{ route('hostels') }}" class="btn btn-sumas-gold btn-lg px-4">View Available Hostels</a>
  </div>
</section>
@endsection
