<footer class="sumas-footer">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="brand-block">
          <img src="{{ asset('assets/images/sumas-logo.png') }}" alt="SUMAS logo">
          <div>
            <strong class="d-block text-white">SUMAS Hostels</strong>
            <span class="small">State University of Medical &amp; Applied Sciences</span>
          </div>
        </div>
        <p class="small">Official hostel allocation and management portal serving students of SUMAS, Igbo Eno, Enugu State.</p>
        <div class="social-icons mt-3">
          <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="#"><i class="fa-brands fa-instagram"></i></a>
          <a href="#"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6>Explore</h6>
        <a href="{{ route('about') }}">About Hostel</a>
        <a href="{{ route('facilities') }}">Facilities</a>
        <a href="{{ route('hostels') }}">Available Hostels</a>
        <a href="{{ route('gallery') }}">Gallery</a>
      </div>
      <div class="col-6 col-lg-2">
        <h6>Support</h6>
        <a href="{{ route('faq') }}">FAQs</a>
        <a href="{{ route('contact') }}">Contact Us</a>
        <a href="{{ route('login') }}">Student Login</a>
        <a href="{{ route('register') }}">Register</a>
      </div>
      <div class="col-lg-4">
        <h6>Hostel Office</h6>
        <a href="#"><i class="fa-solid fa-location-dot me-2"></i>{{ config('sumas.campus_address') }}</a>
        <a href="tel:+2348012345678"><i class="fa-solid fa-phone me-2"></i>{{ config('sumas.support_phone') }}</a>
        <a href="mailto:{{ config('sumas.support_email') }}"><i class="fa-solid fa-envelope me-2"></i>{{ config('sumas.support_email') }}</a>
      </div>
    </div>
    <div class="footer-bottom d-flex flex-wrap justify-content-between gap-2">
      <span>&copy; {{ date('Y') }} {{ config('sumas.institution_name') }}. All rights reserved.</span>
      <span>Hostel Allocation &amp; Management System</span>
    </div>
  </div>
</footer>
