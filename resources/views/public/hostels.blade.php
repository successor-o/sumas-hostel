@extends('layouts.public')
@section('title', 'Available Hostels')

@section('content')
<header class="page-header">
  <div class="container">
    <span class="eyebrow">Real-Time Occupancy</span>
    <h1>Available Hostels</h1>
    <nav class="breadcrumb-sumas small mt-2"><a href="{{ route('home') }}">Home</a> <i class="fa-solid fa-angle-right mx-2" style="color:rgba(255,255,255,0.4)"></i> <span class="active">Available Hostels</span></nav>
  </div>
</header>

<section class="section-pad-sm">
  <div class="container">
    <div class="row g-3 align-items-center mb-4">
      <div class="col-lg-5">
        <div class="dash-search" style="max-width:none;"><i class="fa-solid fa-magnifying-glass"></i><input type="text" class="form-control" id="hostelSearch" placeholder="Search hostel blocks..."></div>
      </div>
      <div class="col-lg-7">
        <div class="filter-pills text-lg-end">
          <button class="btn btn-sumas-primary btn-sm-pill" data-cat-filter="all">All Hostels</button>
          <button class="btn btn-sumas-outline btn-sm-pill" data-cat-filter="Male">Male</button>
          <button class="btn btn-sumas-outline btn-sm-pill" data-cat-filter="Female">Female</button>
          <button class="btn btn-sumas-outline btn-sm-pill" data-cat-filter="Postgraduate">Postgraduate</button>
        </div>
      </div>
    </div>

    <div class="row g-4" id="hostelGrid">
      @foreach($hostels as $hostel)
      <div class="col-md-6 col-lg-4 reveal hostel-grid-item" data-category="{{ $hostel->category }}" data-name="{{ strtolower($hostel->name) }}">
        <div class="hostel-card">
          <div class="img-wrap">
            <img src="{{ $hostel->imageUrl() }}" alt="{{ $hostel->name }}">
            <span class="tag">{{ $hostel->category }} Hostel</span>
            <span class="status-pill status-{{ strtolower($hostel->occupancyStatusLabel()) }}">{{ $hostel->occupancyStatusLabel() }}</span>
          </div>
          <div class="body">
            <h5 class="mb-1">{{ $hostel->name }}</h5>
            <p class="text-muted small mb-2">{{ $hostel->rooms->first()?->capacity ?? 4 }} students per room &middot; Multiple floors</p>
            <div class="meta"><span><i class="fa-solid fa-bed"></i> {{ $hostel->totalBeds() }} Beds</span><span><i class="fa-solid fa-door-open"></i> {{ $hostel->rooms->count() }} Rooms</span></div>
            <div class="occ-bar mb-2"><div class="fill" style="width:{{ $hostel->occupancyPercent() }}%;"></div></div>
            <div class="d-flex justify-content-between small text-muted mb-3"><span>{{ $hostel->occupancyPercent() }}% Occupied</span><span>{{ $hostel->availableBeds() }} beds open</span></div>
            @if($hostel->availableBeds() > 0)
              <a href="{{ route('register') }}" class="btn btn-sumas-outline w-100 btn-sm">Apply to This Hostel</a>
            @else
              <button class="btn btn-sumas-outline w-100 btn-sm" disabled>Fully Booked</button>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var items = document.querySelectorAll('.hostel-grid-item');
  var buttons = document.querySelectorAll('[data-cat-filter]');
  var search = document.getElementById('hostelSearch');

  function applyFilters() {
    var activeCat = document.querySelector('[data-cat-filter].btn-sumas-primary').getAttribute('data-cat-filter');
    var term = search.value.toLowerCase();
    items.forEach(function (item) {
      var matchesCat = activeCat === 'all' || item.getAttribute('data-category') === activeCat;
      var matchesSearch = item.getAttribute('data-name').indexOf(term) > -1;
      item.style.display = (matchesCat && matchesSearch) ? '' : 'none';
    });
  }

  buttons.forEach(function (btn) {
    btn.addEventListener('click', function () {
      buttons.forEach(function (b) { b.classList.remove('btn-sumas-primary'); b.classList.add('btn-sumas-outline'); });
      btn.classList.add('btn-sumas-primary');
      btn.classList.remove('btn-sumas-outline');
      applyFilters();
    });
  });

  search.addEventListener('input', applyFilters);
});
</script>
@endpush
