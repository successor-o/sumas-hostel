@extends('layouts.public')
@section('title', 'Home')

@section('content')
<header class="hero">
  <div class="hero-bg" style="background-image:url('{{ asset('assets/images/campus-gate.jpg') }}');"></div>
  <div class="hero-overlay"></div>
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <span class="eyebrow" style="color:var(--sumas-gold-light)">Hostel Allocation &amp; Management System</span>
        <h1>A safer, simpler place to <span class="accent">apply, get allocated,</span> and settle in.</h1>
        <p class="lead mt-3">SUMAS Hostels gives every student of the State University of Medical and Applied Sciences a transparent way to apply for accommodation, track allocation status, and manage their stay &mdash; all from one portal.</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <a href="{{ route('register') }}" class="btn btn-sumas-gold btn-lg px-4"><i class="fa-solid fa-house-circle-check me-2"></i>Apply for a Hostel</a>
          <a href="{{ route('hostels') }}" class="btn btn-sumas-outline-light btn-lg px-4">View Available Hostels</a>
        </div>
        <div class="hero-stats">
          <div class="stat"><b data-counter="{{ $stats['hostels'] ?? 6 }}">0</b><span>Hostel Blocks</span></div>
          <div class="stat"><b data-counter="{{ $stats['beds'] ?? 904 }}" data-suffix="+">0</b><span>Beds Managed</span></div>
          <div class="stat"><b data-counter="98" data-suffix="%">0</b><span>Allocation Accuracy</span></div>
          <div class="stat"><b data-counter="24" data-suffix="/7">0</b><span>Security Coverage</span></div>
        </div>
      </div>
    </div>
  </div>
  <div class="hero-scroll"><span>Scroll</span><span class="dot-track"></span></div>
</header>

<section class="py-4" style="background:var(--sumas-white); border-bottom:1px solid var(--sumas-line); margin-top:-1px;">
  <div class="container">
    <div class="row g-3 justify-content-center text-center">
      <div class="col-6 col-md-3">
        <a href="{{ route('register') }}" class="d-block text-decoration-none p-3 rounded-3 h-100" style="color:var(--sumas-text);">
          <i class="fa-solid fa-user-plus fs-4 text-brown mb-2 d-block"></i>
          <span class="small fw-600">Register</span>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="{{ route('login') }}" class="d-block text-decoration-none p-3 rounded-3 h-100" style="color:var(--sumas-text);">
          <i class="fa-solid fa-right-to-bracket fs-4 text-brown mb-2 d-block"></i>
          <span class="small fw-600">Student Login</span>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="{{ route('hostels') }}" class="d-block text-decoration-none p-3 rounded-3 h-100" style="color:var(--sumas-text);">
          <i class="fa-solid fa-building fs-4 text-brown mb-2 d-block"></i>
          <span class="small fw-600">Browse Hostels</span>
        </a>
      </div>
      <div class="col-6 col-md-3">
        <a href="{{ route('faq') }}" class="d-block text-decoration-none p-3 rounded-3 h-100" style="color:var(--sumas-text);">
          <i class="fa-solid fa-circle-question fs-4 text-brown mb-2 d-block"></i>
          <span class="small fw-600">Get Help</span>
        </a>
      </div>
    </div>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 reveal">
        <div class="row g-3">
          <div class="col-7"><img src="{{ asset('assets/images/campus-building.jpg') }}" class="img-fluid rounded-4 shadow-sm" style="height:340px;object-fit:cover;width:100%;" alt="SUMAS campus building"></div>
          <div class="col-5 d-flex flex-column gap-3">
            <img src="{{ asset('assets/images/male-hostel.jpg') }}" class="img-fluid rounded-4 shadow-sm" style="height:160px;object-fit:cover;width:100%;" alt="Male Hostel block">
            <img src="{{ asset('assets/images/female-hostel.jpg') }}" class="img-fluid rounded-4 shadow-sm" style="height:164px;object-fit:cover;width:100%;" alt="Female Hostel block">
          </div>
        </div>
      </div>
      <div class="col-lg-6 reveal">
        <span class="eyebrow">About Our Hostels</span>
        <h2 class="mb-3">Secure, supervised accommodation built around student wellbeing</h2>
        <p class="text-muted">The SUMAS Hostel scheme provides gender-separated residential blocks within the Igbo Eno campus, each supervised by resident hall officers and protected by perimeter fencing and 24-hour security.</p>
        <ul class="list-unstyled mt-4">
          <li class="d-flex gap-3 mb-3"><i class="fa-solid fa-circle-check text-gold mt-1"></i><span>Separate Male and Female hostel blocks with dedicated wardens</span></li>
          <li class="d-flex gap-3 mb-3"><i class="fa-solid fa-circle-check text-gold mt-1"></i><span>Fully digitised application, allocation, and renewal process</span></li>
          <li class="d-flex gap-3 mb-3"><i class="fa-solid fa-circle-check text-gold mt-1"></i><span>Real-time room availability so you always know what's open</span></li>
        </ul>
        <a href="{{ route('about') }}" class="btn btn-sumas-primary mt-2">Learn More About Our Hostels <i class="fa-solid fa-arrow-right ms-2"></i></a>
      </div>
    </div>
  </div>
</section>

<section class="stat-strip py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-6 col-lg-3 stat-item"><b data-counter="{{ $stats['hostels'] ?? 6 }}">0</b><span>Hostel Blocks</span></div>
      <div class="col-6 col-lg-3 stat-item"><b data-counter="{{ $stats['rooms'] ?? 242 }}">0</b><span>Rooms Managed</span></div>
      <div class="col-6 col-lg-3 stat-item"><b data-counter="{{ $stats['students'] ?? 0 }}">0</b><span>Students Housed</span></div>
      <div class="col-6 col-lg-3 stat-item"><b data-counter="4" data-suffix=" min">0</b><span>Avg. Application Time</span></div>
    </div>
  </div>
</section>

<section class="section-pad bg-cream">
  <div class="container">
    <div class="text-center mb-5 reveal">
      <span class="eyebrow justify-content-center">Why Live On Campus</span>
      <h2>Everything a resident student needs</h2>
      <p class="text-muted mx-auto" style="max-width:560px;">From round-the-clock security to reliable power and water, our hostels are built for focus and comfort.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-shield-halved"></i></div><h5>24/7 Security</h5><p class="text-muted small mb-0">Perimeter fencing, gated access, and round-the-clock security personnel at every block.</p></div></div>
      <div class="col-md-6 col-lg-3 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-bolt"></i></div><h5>Stable Power &amp; Water</h5><p class="text-muted small mb-0">Backup generators and borehole-fed water supply keep every room comfortable.</p></div></div>
      <div class="col-md-6 col-lg-3 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-wifi"></i></div><h5>Campus Wi-Fi</h5><p class="text-muted small mb-0">Subsidised internet access in common areas and reading rooms within each hostel.</p></div></div>
      <div class="col-md-6 col-lg-3 reveal"><div class="feature-card"><div class="icon-wrap"><i class="fa-solid fa-house-medical"></i></div><h5>Nearby Clinic</h5><p class="text-muted small mb-0">Direct footpath access to the University Teaching Hospital for emergencies.</p></div></div>
    </div>
    <div class="text-center mt-4"><a href="{{ route('facilities') }}" class="btn btn-sumas-outline">See All Facilities</a></div>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between mb-5 flex-wrap gap-3 reveal">
      <div><span class="eyebrow">Available Hostels</span><h2 class="mb-0">Choose the block that fits you</h2></div>
      <a href="{{ route('hostels') }}" class="btn btn-sumas-outline">View All Hostels <i class="fa-solid fa-arrow-right ms-2"></i></a>
    </div>
    <div class="row g-4">
      @forelse($previewHostels as $hostel)
      <div class="col-md-6 col-lg-4 reveal">
        <div class="hostel-card">
          <div class="img-wrap">
            <img src="{{ $hostel->imageUrl() }}" alt="{{ $hostel->name }}">
            <span class="tag">{{ $hostel->category }}</span>
            <span class="status-pill status-{{ strtolower($hostel->occupancyStatusLabel()) }}">{{ $hostel->occupancyStatusLabel() }}</span>
          </div>
          <div class="body">
            <h5 class="mb-1">{{ $hostel->name }}</h5>
            <p class="text-muted small mb-2">{{ $hostel->rooms->first()?->capacity ?? 4 }} students per room &middot; Multiple floors</p>
            <div class="meta"><span><i class="fa-solid fa-bed"></i> {{ $hostel->totalBeds() }} Beds</span><span><i class="fa-solid fa-door-open"></i> {{ $hostel->rooms->count() }} Rooms</span></div>
            <div class="occ-bar mb-2"><div class="fill" style="width:{{ $hostel->occupancyPercent() }}%;"></div></div>
            <div class="d-flex justify-content-between small text-muted"><span>{{ $hostel->occupancyPercent() }}% Occupied</span><span>{{ $hostel->availableBeds() }} beds open</span></div>
          </div>
        </div>
      </div>
      @empty
      <p class="text-muted text-center">Hostel information will appear here once seeded.</p>
      @endforelse
    </div>
  </div>
</section>

<section class="section-pad bg-cream">
  <div class="container">
    <div class="text-center mb-5 reveal"><span class="eyebrow justify-content-center">How It Works</span><h2>Get allocated in four simple steps</h2></div>
    <div class="row g-4">
      <div class="col-6 col-lg-3 reveal"><div class="process-step"><div class="num">1</div><h6>Create an Account</h6><p class="text-muted small">Register with your matriculation number and school email.</p></div></div>
      <div class="col-6 col-lg-3 reveal"><div class="process-step"><div class="num">2</div><h6>Submit Application</h6><p class="text-muted small">Pick your preferred hostel and complete the application form.</p></div></div>
      <div class="col-6 col-lg-3 reveal"><div class="process-step"><div class="num">3</div><h6>Get Allocated</h6><p class="text-muted small">Track your application status and receive your room assignment.</p></div></div>
      <div class="col-6 col-lg-3 reveal"><div class="process-step"><div class="num">4</div><h6>Move In</h6><p class="text-muted small">Download your allocation slip and check in at your hostel block.</p></div></div>
    </div>
  </div>
</section>

<section class="section-pad">
  <div class="container">
    <div class="text-center mb-5 reveal"><span class="eyebrow justify-content-center">Student Voices</span><h2>What residents say</h2></div>
    <div class="row g-4">
      <div class="col-md-4 reveal"><div class="testimonial-card"><div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><p>"I applied on a Sunday night and had my allocation slip before the week was out."</p><div class="who"><div class="avatar">CN</div><div><strong class="d-block small">Chidera N.</strong><span class="text-muted small">300L Nursing Science</span></div></div></div></div>
      <div class="col-md-4 reveal"><div class="testimonial-card"><div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div><p>"Being able to see how full a hostel is before applying saved me from wasting an application."</p><div class="who"><div class="avatar">EO</div><div><strong class="d-block small">Emeka O.</strong><span class="text-muted small">200L Medical Laboratory Science</span></div></div></div></div>
      <div class="col-md-4 reveal"><div class="testimonial-card"><div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div><p>"The hostel is secure and quiet enough to study. Maintenance requests get resolved fast."</p><div class="who"><div class="avatar">FA</div><div><strong class="d-block small">Faith A.</strong><span class="text-muted small">400L Physiotherapy</span></div></div></div></div>
    </div>
  </div>
</section>

<section class="section-pad-sm bg-brown position-relative overflow-hidden">
  <div class="container text-center reveal" style="position:relative;z-index:1;">
    <h2 class="text-white mb-3">Ready to secure your room for the new session?</h2>
    <p class="text-white-50 mx-auto mb-4" style="max-width:520px;">Registration for the current academic session is open. Applications are processed on a first-come, first-served basis.</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="{{ route('register') }}" class="btn btn-sumas-gold btn-lg px-4">Create an Account</a>
      <a href="{{ route('contact') }}" class="btn btn-sumas-outline-light btn-lg px-4">Talk to Hostel Office</a>
    </div>
  </div>
</section>
@endsection
