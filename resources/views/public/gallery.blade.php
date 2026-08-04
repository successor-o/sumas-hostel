@extends('layouts.public')
@section('title', 'Hostel Gallery')

@section('content')
<header class="page-header">
  <div class="container">
    <span class="eyebrow">A Look Around Campus</span>
    <h1>Hostel Gallery</h1>
    <nav class="breadcrumb-sumas small mt-2"><a href="{{ route('home') }}">Home</a> <i class="fa-solid fa-angle-right mx-2" style="color:rgba(255,255,255,0.4)"></i> <span class="active">Gallery</span></nav>
  </div>
</header>

<section class="section-pad-sm">
  <div class="container">
    <div class="filter-pills mb-4">
      <button class="btn btn-sumas-primary btn-sm-pill" data-filter="all">All</button>
      <button class="btn btn-sumas-outline btn-sm-pill" data-filter="exterior">Exteriors</button>
      <button class="btn btn-sumas-outline btn-sm-pill" data-filter="campus">Campus</button>
    </div>
    <div class="row">
      <div class="col-md-6 col-lg-4 gallery-item" data-category="exterior"><img src="{{ asset('assets/images/male-hostel.jpg') }}" alt="Male Hostel entrance"><div class="caption"><small>Male Hostel</small><h6 class="mb-0 text-white">Block A Entrance</h6></div></div>
      <div class="col-md-6 col-lg-4 gallery-item" data-category="exterior"><img src="{{ asset('assets/images/female-hostel.jpg') }}" alt="Female Hostel entrance"><div class="caption"><small>Female Hostel</small><h6 class="mb-0 text-white">Block A Entrance</h6></div></div>
      <div class="col-md-6 col-lg-4 gallery-item" data-category="campus"><img src="{{ asset('assets/images/campus-building.jpg') }}" alt="Academic and clinical building"><div class="caption"><small>Campus</small><h6 class="mb-0 text-white">ICU / Step Down Unit Building</h6></div></div>
      <div class="col-md-6 col-lg-4 gallery-item" data-category="campus"><img src="{{ asset('assets/images/campus-gate.jpg') }}" alt="SUMAS main gate"><div class="caption"><small>Campus</small><h6 class="mb-0 text-white">Main Entrance Gate</h6></div></div>
      <div class="col-md-6 col-lg-4 gallery-item" data-category="exterior"><img src="{{ asset('assets/images/male-hostel.jpg') }}" alt="Male Hostel courtyard"><div class="caption"><small>Male Hostel</small><h6 class="mb-0 text-white">Courtyard View</h6></div></div>
      <div class="col-md-6 col-lg-4 gallery-item" data-category="exterior"><img src="{{ asset('assets/images/female-hostel.jpg') }}" alt="Female Hostel courtyard"><div class="caption"><small>Female Hostel</small><h6 class="mb-0 text-white">Courtyard View</h6></div></div>
    </div>
  </div>
</section>

<div class="modal fade" id="lightboxModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content bg-transparent border-0"><img id="lightboxImg" src="" class="img-fluid rounded-4" alt="Gallery preview"></div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.gallery-item img').forEach(function(img){
  img.addEventListener('click', function(){
    document.getElementById('lightboxImg').src = img.src;
    new bootstrap.Modal(document.getElementById('lightboxModal')).show();
  });
});
</script>
@endpush
