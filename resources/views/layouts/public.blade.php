<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'SUMAS Hostel Portal') | State University of Medical and Applied Sciences</title>
<meta name="description" content="Official hostel allocation and management portal for the State University of Medical and Applied Sciences (SUMAS), Igbo Eno, Enugu State.">

<link rel="icon" type="image/png" href="{{ asset('assets/images/sumas-logo.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@stack('styles')
</head>
<body>

<div id="preloader">
  <img src="{{ asset('assets/images/sumas-logo.png') }}" alt="SUMAS">
  <div class="loader-track"></div>
</div>

<button class="back-to-top" id="backToTop" aria-label="Back to top"><i class="fa-solid fa-arrow-up"></i></button>

@include('partials.public-nav')

{{ $slot ?? '' }}
@yield('content')

@include('partials.public-footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@stack('scripts')
</body>
</html>
