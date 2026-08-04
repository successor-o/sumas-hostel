<nav class="navbar navbar-expand-lg sumas-nav @yield('nav-class', '')">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">
      <img src="{{ asset('assets/images/sumas-logo.png') }}" alt="SUMAS Logo">
      <span class="brand-text">
        <span class="name">SUMAS Hostels</span>
        <span class="tag">Igbo Eno &middot; Enugu State</span>
      </span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <i class="fa-solid fa-bars fs-4"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About Hostel</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('facilities') ? 'active' : '' }}" href="{{ route('facilities') }}">Facilities</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('hostels') ? 'active' : '' }}" href="{{ route('hostels') }}">Available Hostels</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('faq') ? 'active' : '' }}" href="{{ route('faq') }}">FAQs</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
        @auth('web')
          <li class="nav-item ms-lg-3 mt-2 mt-lg-0"><a class="btn btn-sumas-gold btn-sm-pill" href="{{ route('student.dashboard') }}"><i class="fa-solid fa-gauge me-1"></i> Dashboard</a></li>
        @else
          <li class="nav-item ms-lg-3 mt-2 mt-lg-0"><a class="btn btn-sumas-outline-light btn-sm-pill" href="{{ route('login') }}">Log In</a></li>
          <li class="nav-item mt-2 mt-lg-0"><a class="btn btn-sumas-gold btn-sm-pill" href="{{ route('register') }}">Register</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
