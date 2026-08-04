@extends('layouts.public')
@section('title', 'Hostel Facilities')

@section('content')
<header class="page-header">
  <div class="container">
    <span class="eyebrow">On-Campus Amenities</span>
    <h1>Hostel Facilities</h1>
    <nav class="breadcrumb-sumas small mt-2"><a href="{{ route('home') }}">Home</a> <i class="fa-solid fa-angle-right mx-2" style="color:rgba(255,255,255,0.4)"></i> <span class="active">Facilities</span></nav>
  </div>
</header>

<section class="section-pad">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-6 col-lg-4 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-shield-halved"></i></div><h5>24/7 Security</h5><p class="text-muted small mb-0">Perimeter fencing, controlled gate access, and security personnel patrol every block through the night.</p></div></div>
      <div class="col-md-6 col-lg-4 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-bolt"></i></div><h5>Backup Power</h5><p class="text-muted small mb-0">Generator support during outages so reading and rest never depend on the grid alone.</p></div></div>
      <div class="col-md-6 col-lg-4 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-droplet"></i></div><h5>Borehole Water Supply</h5><p class="text-muted small mb-0">Dedicated boreholes and overhead tanks keep taps running in every block.</p></div></div>
      <div class="col-md-6 col-lg-4 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-wifi"></i></div><h5>Campus Wi-Fi</h5><p class="text-muted small mb-0">Subsidised internet access in common rooms and hostel reading areas.</p></div></div>
      <div class="col-md-6 col-lg-4 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-book-open-reader"></i></div><h5>Reading Rooms</h5><p class="text-muted small mb-0">Quiet, well-lit common rooms set aside for group and individual study.</p></div></div>
      <div class="col-md-6 col-lg-4 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-kitchen-set"></i></div><h5>Shared Kitchenettes</h5><p class="text-muted small mb-0">Cooking areas on each floor with gas points and washing stations.</p></div></div>
      <div class="col-md-6 col-lg-4 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-shirt"></i></div><h5>Laundry Points</h5><p class="text-muted small mb-0">Open-air laundry and drying areas within each hostel compound.</p></div></div>
      <div class="col-md-6 col-lg-4 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-house-medical"></i></div><h5>Clinic Access</h5><p class="text-muted small mb-0">A short walk to the University Teaching Hospital for medical emergencies.</p></div></div>
      <div class="col-md-6 col-lg-4 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-broom"></i></div><h5>Routine Cleaning</h5><p class="text-muted small mb-0">Scheduled cleaning of common areas, corridors, and washrooms.</p></div></div>
    </div>
  </div>
</section>

<section class="section-pad bg-cream">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 reveal"><img src="{{ asset('assets/images/campus-building.jpg') }}" class="img-fluid rounded-4 shadow-md" alt="SUMAS academic and clinical building"></div>
      <div class="col-lg-6 reveal">
        <span class="eyebrow">Beyond The Room</span>
        <h2 class="mb-3">Living close to where you learn</h2>
        <p class="text-muted">Because SUMAS trains students across medical and applied science disciplines, hostel blocks are positioned within a short walk of teaching hospitals, laboratories, and lecture theatres.</p>
        <a href="{{ route('hostels') }}" class="btn btn-sumas-primary mt-2">View Hostels Near You <i class="fa-solid fa-arrow-right ms-2"></i></a>
      </div>
    </div>
  </div>
</section>
@endsection
